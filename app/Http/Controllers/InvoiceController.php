<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Invoice\CancelInvoiceAction;
use App\Actions\Invoice\CreateInvoiceAction;
use App\Actions\Invoice\IssueInvoiceAction;
use App\Actions\Invoice\SyncInvoiceStatusesAction;
use App\Actions\Invoice\UpdateInvoiceAction;
use App\Http\Requests\CancelInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class InvoiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:invoices.view', only: ['index', 'show']),
            new Middleware('permission:invoices.create', only: ['create', 'store']),
            new Middleware('permission:invoices.update', only: ['edit', 'update']),
            new Middleware('permission:invoices.issue', only: ['issue']),
            new Middleware('permission:invoices.cancel', only: ['cancel']),
            new Middleware('permission:invoices.print', only: ['print']),
        ];
    }

    public function index(Request $request, SyncInvoiceStatusesAction $syncInvoiceStatuses): View
    {
        $this->authorize('viewAny', Invoice::class);

        $syncInvoiceStatuses->handle();
        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));

        $invoices = Invoice::query()
            ->with('customer')
            ->when($status, static fn (Builder $query, string $value) => $query->where('status', $value))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $invoiceQuery) use ($search): void {
                    $invoiceQuery->where('invoice_number', 'like', sprintf('%%%s%%', $search))
                        ->orWhereHas('customer', function (Builder $customerQuery) use ($search): void {
                            $customerQuery->where('full_name', 'like', sprintf('%%%s%%', $search))
                                ->orWhere('phone', 'like', sprintf('%%%s%%', $search));
                        });
                });
            })
            ->latest('invoice_date')
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'status', 'search'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Invoice::class);

        $customers = Customer::query()->orderBy('full_name')->get();
        $selected_customer_id = $request->query('customer_id');
        $orders = [];

        if ($selected_customer_id !== null) {
            $orders = Order::query()
                ->where('customer_id', $selected_customer_id)
                ->whereDoesntHave('invoice')
                ->get();
        }

        return view('invoices.create', compact('customers', 'selected_customer_id', 'orders'));
    }

    public function store(StoreInvoiceRequest $request, CreateInvoiceAction $action): RedirectResponse
    {
        $this->authorize('create', Invoice::class);

        $invoice = $action->handle($request->validated());

        return to_route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice, SyncInvoiceStatusesAction $syncInvoiceStatuses): View
    {
        $this->authorize('view', $invoice);

        $syncInvoiceStatuses->handle();
        $invoice->load(['customer', 'order', 'items', 'payments.receiver', 'payments.receipt', 'payments.voider']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View|RedirectResponse
    {
        $this->authorize('update', $invoice);

        if ($invoice->status !== 'draft') {
            return to_route('invoices.show', $invoice)->with('error', 'Only draft invoices can be edited.');
        }

        $invoice->load('items');
        $customers = Customer::query()->orderBy('full_name')->get();
        $orders = Order::query()
            ->where('customer_id', $invoice->customer_id)
            ->where(function (Builder $query) use ($invoice): void {
                $query->whereDoesntHave('invoice')
                    ->orWhere('id', $invoice->order_id);
            })
            ->get();

        return view('invoices.edit', compact('invoice', 'customers', 'orders'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice, UpdateInvoiceAction $action): RedirectResponse
    {
        $this->authorize('update', $invoice);

        if ($invoice->status !== 'draft') {
            return to_route('invoices.show', $invoice)->with('error', 'Only draft invoices can be edited.');
        }

        $action->handle($invoice, $request->validated());

        return to_route('invoices.show', $invoice)->with('success', 'Invoice updated successfully.');
    }

    public function issue(Invoice $invoice, IssueInvoiceAction $action): RedirectResponse
    {
        $this->authorize('issue', $invoice);

        $action->handle($invoice);

        return back()->with('success', 'Invoice issued successfully.');
    }

    public function cancel(CancelInvoiceRequest $request, Invoice $invoice, CancelInvoiceAction $action): RedirectResponse
    {
        $this->authorize('cancel', $invoice);

        $action->handle($invoice, $request->validated('cancellation_reason'));

        return back()->with('success', 'Invoice cancelled successfully.');
    }

    public function print(Invoice $invoice): View
    {
        $this->authorize('print', $invoice);

        $invoice->load(['customer', 'items']);

        return view('invoices.print', compact('invoice'));
    }
}
