<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\DeleteOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly CreateOrderAction $createOrder,
        private readonly UpdateOrderAction $updateOrder,
        private readonly DeleteOrderAction $deleteOrder,
    ) {
    }

    public function index(): View
    {
        $orders = Order::with('customer')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $customers = Customer::orderBy('full_name')->get();
        $selected_customer_id = $request->query('customer_id');

        return view('orders.create', compact('customers', 'selected_customer_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $order = ($this->createOrder)($data);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load(['customer', 'items', 'invoice', 'creator', 'assignee']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): View
    {
        $customers = Customer::orderBy('full_name')->get();
        $order->load('items');

        return view('orders.edit', compact('order', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $data = $request->validated();
        ($this->updateOrder)($order, $data);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        ($this->deleteOrder)($order);

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
