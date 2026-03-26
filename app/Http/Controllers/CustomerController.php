<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Customer\CreateCustomerAction;
use App\Actions\Customer\DeleteCustomerAction;
use App\Actions\Customer\UpdateCustomerAction;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:customers.view', only: ['index', 'show']),
            new Middleware('permission:customers.create', only: ['create', 'store']),
            new Middleware('permission:customers.update', only: ['edit', 'update']),
            new Middleware('permission:customers.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Customer::class);

        $search = trim((string) $request->query('search', ''));

        $customers = Customer::query()
            ->when(
                $search !== '',
                static fn (Builder $query) => $query->where('full_name', 'like', sprintf('%%%s%%', $search))
                    ->orWhere('phone', 'like', sprintf('%%%s%%', $search))
                    ->orWhere('email', 'like', sprintf('%%%s%%', $search))
                    ->orWhere('customer_code', 'like', sprintf('%%%s%%', $search))
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    public function create(): View
    {
        $this->authorize('create', Customer::class);

        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request, CreateCustomerAction $action): RedirectResponse
    {
        $this->authorize('create', Customer::class);

        $customer = $action->handle($request->validated());

        $customer->update([
            'customer_code' => 'CUST-'.str_pad((string) $customer->id, 5, '0', STR_PAD_LEFT),
        ]);

        return to_route('customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $this->authorize('view', $customer);

        $customer->load([
            'measurements',
            'orders',
            'invoices.payments.receipt',
            'payments.invoice',
            'payments.receipt',
        ]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);

        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer, UpdateCustomerAction $action): RedirectResponse
    {
        $this->authorize('update', $customer);

        $action->handle($customer, $request->validated());

        return to_route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer, DeleteCustomerAction $action): RedirectResponse
    {
        $this->authorize('delete', $customer);

        $action->handle($customer);

        return to_route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
