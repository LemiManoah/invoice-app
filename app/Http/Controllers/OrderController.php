<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Measurement\CreateMeasurementAction;
use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\DeleteOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
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

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Order::class);

        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $orders = Order::query()
            ->with(['customer', 'invoice'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($orderQuery) use ($search): void {
                    $orderQuery->where('order_number', 'like', sprintf('%%%s%%', $search))
                        ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                            $customerQuery->where('full_name', 'like', sprintf('%%%s%%', $search))
                                ->orWhere('email', 'like', sprintf('%%%s%%', $search))
                                ->orWhere('phone', 'like', sprintf('%%%s%%', $search));
                        });
                });
            })
            ->when($status !== null && $status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search', 'status'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Order::class);

        $customers = Customer::query()->orderBy('full_name')
            ->with(['measurements' => fn ($q) => $q->where('is_current', true)->latest()])
            ->get();
        $currencies = Currency::active()->ordered()->get();
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $selected_customer_id = $request->query('customer_id');

        return view('orders.create', compact('customers', 'currencies', 'products', 'selected_customer_id'));
    }

    public function store(StoreOrderRequest $request, CreateOrderAction $action, CreateMeasurementAction $measurementAction): RedirectResponse
    {
        $this->authorize('create', Order::class);

        $order = $action->handle($request->validated());

        if ($request->boolean('record_measurements') && $request->filled('measurement_date') && $order->customer_id) {
            $measurementAction->handle($order->customer, $request->only([
                'jacket_shoulder', 'jacket_chest', 'jacket_stomach_waist', 'jacket_sleeve',
                'jacket_length', 'jacket_biceps', 'jacket_wrist', 'jacket_lower_arm', 'jacket_hip_line',
                'trouser_waist', 'trouser_thigh_cuff', 'trouser_length_fit', 'trouser_ankle_fit',
                'trouser_knee_fit', 'trouser_fly_fit', 'trouser_hips',
                'waistcoat_chest', 'waistcoat_waist', 'waistcoat_length',
                'skirt_waist', 'skirt_hip_line', 'skirt_full_length',
                'shirt_chest', 'shirt_waist', 'shirt_shoulder', 'shirt_full_length', 'shirt_bottom_cut',
                'measurement_date', 'is_current', 'fitting_notes',
            ]));
        }

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
