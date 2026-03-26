<?php

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
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly CreateInvoiceAction $createInvoice,
        private readonly UpdateInvoiceAction $updateInvoice,
        private readonly IssueInvoiceAction $issueInvoice,
        private readonly CancelInvoiceAction $cancelInvoice,
        private readonly SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ) {
    }

    public function index(Request $request): View
    {
        ($this->syncInvoiceStatuses)();

        $status = $request->input('status');
        $search = trim((string) $request->string('search'));

        $invoices = Invoice::with('customer')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($invoiceQuery) use ($search) {
                    $invoiceQuery->where('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('invoice_date')
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $customers = Customer::orderBy('full_name')->get();
        $selected_customer_id = $request->query('customer_id');
        $orders = [];

        if ($selected_customer_id) {
            $orders = Order::where('customer_id', $selected_customer_id)
                ->whereDoesntHave('invoice')
                ->get();
        }

        return view('invoices.create', compact('customers', 'selected_customer_id', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $invoice = ($this->createInvoice)($data);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): View
    {
        ($this->syncInvoiceStatuses)();

        $invoice->load(['customer', 'order', 'items', 'payments.receiver', 'payments.receipt', 'payments.voider']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice): View|RedirectResponse
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }

        $invoice->load('items');
        $customers = Customer::orderBy('full_name')->get();
        $orders = Order::where('customer_id', $invoice->customer_id)
            ->where(function ($query) use ($invoice) {
                $query->whereDoesntHave('invoice')
                    ->orWhere('id', $invoice->order_id);
            })
            ->get();

        return view('invoices.edit', compact('invoice', 'customers', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }
        $data = $request->validated();
        ($this->updateInvoice)($invoice, $data);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Issue the invoice.
     */
    public function issue(Invoice $invoice): RedirectResponse
    {
        ($this->issueInvoice)($invoice);

        return back()->with('success', 'Invoice issued successfully.');
    }

    /**
     * Cancel the invoice.
     */
    public function cancel(CancelInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        ($this->cancelInvoice)($invoice, $request->validated('cancellation_reason'));

        return back()->with('success', 'Invoice cancelled successfully.');
    }

    public function print(Invoice $invoice): View
    {
        $invoice->load(['customer', 'items']);

        return view('invoices.print', compact('invoice'));
    }
}
