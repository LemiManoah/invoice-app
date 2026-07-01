<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final readonly class QuotationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:quotations.view', only: ['index', 'show']),
            new Middleware('permission:quotations.create', only: ['create', 'store']),
            new Middleware('permission:quotations.update', only: ['edit', 'update']),
            new Middleware('permission:quotations.send', only: ['send']),
            new Middleware('permission:quotations.convert', only: ['convert']),
            new Middleware('permission:quotations.delete', only: ['destroy']),
            new Middleware('permission:quotations.print', only: ['print', 'printThermal', 'downloadPdf']),
        ];
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Quotation::class);

        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));

        $quotations = Quotation::query()
            ->with('customer', 'currency')
            ->when($status, static fn (Builder $q, string $v) => $q->where('status', $v))
            ->when($search !== '', function (Builder $q) use ($search): void {
                $q->where(function (Builder $inner) use ($search): void {
                    $inner->where('quotation_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function (Builder $cq) use ($search): void {
                            $cq->where('full_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('quotation_date')
            ->paginate(10)
            ->withQueryString();

        return view('quotations.index', compact('quotations', 'status', 'search'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Quotation::class);

        $customers  = Customer::query()->orderBy('full_name')->get();
        $currencies = Currency::active()->ordered()->get();
        $selectedCustomerId = $request->query('customer_id');

        return view('quotations.create', compact('customers', 'currencies', 'selectedCustomerId'));
    }

    public function store(StoreQuotationRequest $request): RedirectResponse
    {
        $this->authorize('create', Quotation::class);

        $quotation = DB::transaction(function () use ($request): Quotation {
            $data = $request->validated();
            $subtotal = collect($data['items'])->sum(
                static fn (array $item): float => (float) $item['quantity'] * (float) $item['unit_price']
            );

            $quotation = Quotation::query()->create([
                'quotation_number' => 'QT-' . strtoupper(uniqid()),
                'customer_id'      => $data['customer_id'],
                'currency_id'      => $data['currency_id'],
                'quotation_date'   => $data['quotation_date'],
                'valid_until'      => $data['valid_until'] ?? null,
                'status'           => 'draft',
                'notes'            => $data['notes'] ?? null,
                'subtotal_amount'  => $subtotal,
                'discount_amount'  => $data['discount_amount'] ?? 0,
                'tax_amount'       => $data['tax_amount'] ?? 0,
                'total_amount'     => ($subtotal - (float) ($data['discount_amount'] ?? 0)) + (float) ($data['tax_amount'] ?? 0),
                'created_by'       => Auth::id(),
            ]);

            foreach ($data['items'] as $item) {
                $quotation->items()->create([
                    'item_name'   => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'line_total'  => (float) $item['quantity'] * (float) $item['unit_price'],
                ]);
            }

            return $quotation;
        });

        return to_route('quotations.show', $quotation)->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation): View
    {
        $this->authorize('view', $quotation);

        $quotation->load(['customer', 'items', 'currency', 'invoice', 'creator']);

        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation): View|RedirectResponse
    {
        $this->authorize('update', $quotation);

        if (! in_array($quotation->status, ['draft', 'sent'], true)) {
            return to_route('quotations.show', $quotation)
                ->with('error', 'Only draft or sent quotations can be edited.');
        }

        $quotation->load('items');
        $customers  = Customer::query()->orderBy('full_name')->get();
        $currencies = Currency::active()->ordered()->get();

        return view('quotations.edit', compact('quotation', 'customers', 'currencies'));
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        DB::transaction(function () use ($request, $quotation): void {
            $data = $request->validated();
            $subtotal = collect($data['items'])->sum(
                static fn (array $item): float => (float) $item['quantity'] * (float) $item['unit_price']
            );

            $quotation->update([
                'customer_id'     => $data['customer_id'],
                'currency_id'     => $data['currency_id'],
                'quotation_date'  => $data['quotation_date'],
                'valid_until'     => $data['valid_until'] ?? null,
                'notes'           => $data['notes'] ?? null,
                'subtotal_amount' => $subtotal,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'tax_amount'      => $data['tax_amount'] ?? 0,
                'total_amount'    => ($subtotal - (float) ($data['discount_amount'] ?? 0)) + (float) ($data['tax_amount'] ?? 0),
            ]);

            $quotation->items()->delete();

            foreach ($data['items'] as $item) {
                $quotation->items()->create([
                    'item_name'   => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'line_total'  => (float) $item['quantity'] * (float) $item['unit_price'],
                ]);
            }
        });

        return to_route('quotations.show', $quotation)->with('success', 'Quotation updated successfully.');
    }

    public function send(Quotation $quotation): RedirectResponse
    {
        $this->authorize('send', $quotation);

        $quotation->update(['status' => 'sent']);

        return back()->with('success', 'Quotation marked as sent.');
    }

    public function convert(Quotation $quotation): RedirectResponse
    {
        $this->authorize('convert', $quotation);

        $invoice = DB::transaction(function () use ($quotation): Invoice {
            $invoice = Invoice::query()->create([
                'invoice_number'  => 'INV-' . strtoupper(uniqid()),
                'customer_id'     => $quotation->customer_id,
                'currency_id'     => $quotation->currency_id,
                'invoice_date'    => now()->toDateString(),
                'due_date'        => null,
                'status'          => 'draft',
                'subtotal_amount' => $quotation->subtotal_amount,
                'discount_amount' => $quotation->discount_amount,
                'tax_amount'      => $quotation->tax_amount,
                'total_amount'    => $quotation->total_amount,
                'amount_paid'     => 0,
                'balance_due'     => $quotation->total_amount,
                'notes'           => $quotation->notes,
                'created_by'      => Auth::id(),
            ]);

            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'item_name'   => $item->item_name,
                    'description' => $item->description,
                    'quantity'    => $item->quantity,
                    'unit_price'  => $item->unit_price,
                    'line_total'  => $item->line_total,
                ]);
            }

            $quotation->update([
                'status'     => 'converted',
                'invoice_id' => $invoice->id,
            ]);

            return $invoice;
        });

        return to_route('invoices.show', $invoice)
            ->with('success', "Quotation converted to invoice {$invoice->invoice_number}.");
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        $this->authorize('delete', $quotation);

        $quotation->items()->delete();
        $quotation->delete();

        return to_route('quotations.index')->with('success', 'Quotation deleted.');
    }

    public function print(Quotation $quotation): View
    {
        $this->authorize('print', $quotation);

        $quotation->load(['customer', 'items', 'currency']);

        return view('quotations.print', compact('quotation'));
    }

    public function printThermal(Request $request, Quotation $quotation): View
    {
        $this->authorize('print', $quotation);

        $quotation->load(['customer', 'items', 'currency']);

        $paperWidth = (int) $request->query('size', 80);
        $paperWidth = in_array($paperWidth, [58, 80], true) ? $paperWidth : 80;

        return view('quotations.print-thermal', compact('quotation', 'paperWidth'));
    }

    public function downloadPdf(Quotation $quotation): Response
    {
        $this->authorize('print', $quotation);

        $quotation->load(['customer', 'items', 'currency']);

        $pdf = Pdf::loadView('quotations.print', compact('quotation'));

        return $pdf->download('quotation-' . $quotation->number . '.pdf');
    }
}
