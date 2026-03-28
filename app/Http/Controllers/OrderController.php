<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\DeleteOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:orders.view', only: ['index', 'show']),
            new Middleware('permission:orders.create', only: ['create', 'store']),
            new Middleware('permission:orders.update', only: ['edit', 'update']),
            new Middleware('permission:orders.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::query()->with(['customer', 'invoice'])->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Order::class);

        $customers = Customer::query()->orderBy('full_name')->get();
        $currencies = Currency::active()->ordered()->get();
        $selected_customer_id = $request->query('customer_id');

        return view('orders.create', compact('customers', 'currencies', 'selected_customer_id'));
    }

    public function store(StoreOrderRequest $request, CreateOrderAction $action): RedirectResponse
    {
        $this->authorize('create', Order::class);

        $order = $action->handle($request->validated());

        return to_route('orders.show', $order)
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['customer', 'items', 'invoice', 'creator', 'assignee', 'currency']);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        $this->authorize('update', $order);

        $customers = Customer::query()->orderBy('full_name')->get();
        $currencies = Currency::active()->ordered()->get();
        $order->load('items');

        return view('orders.edit', compact('order', 'customers', 'currencies'));
    }

    public function update(UpdateOrderRequest $request, Order $order, UpdateOrderAction $action): RedirectResponse
    {
        $this->authorize('update', $order);

        $action->handle($order, $request->validated());

        return to_route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order, DeleteOrderAction $action): RedirectResponse
    {
        $this->authorize('delete', $order);

        $action->handle($order);

        return to_route('orders.index')->with('success', 'Order deleted.');
    }
}
