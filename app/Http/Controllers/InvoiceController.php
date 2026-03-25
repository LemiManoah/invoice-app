<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();
        $invoice = (new CreateInvoiceAction)($data);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'order', 'items', 'payments.receiver']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }

        $invoice->load('items');
        $customers = Customer::orderBy('full_name')->get();

        return view('invoices.edit', compact('invoice', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }
        $data = $request->validated();
        (new UpdateInvoiceAction)($invoice, $data);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Issue the invoice.
     */
    public function issue(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Invoice is already issued or cancelled.');
        }

        $invoice->update([
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        return back()->with('success', 'Invoice issued successfully.');
    }

    /**
     * Cancel the invoice.
     */
    public function cancel(Request $request, Invoice $invoice)
    {
        $request->validate(['cancellation_reason' => 'required|string']);

        $invoice->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return back()->with('success', 'Invoice cancelled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be deleted.');
        }
        (new DeleteInvoiceAction)($invoice);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}
