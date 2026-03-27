<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\PaymentMethod\CreatePaymentMethodAction;
use App\Actions\PaymentMethod\DeletePaymentMethodAction;
use App\Actions\PaymentMethod\UpdatePaymentMethodAction;
use App\Http\Requests\DeletePaymentMethodRequest;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class PaymentMethodController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:payment-methods.view', only: ['index']),
            new Middleware('permission:payment-methods.create', only: ['create', 'store']),
            new Middleware('permission:payment-methods.update', only: ['edit', 'update']),
            new Middleware('permission:payment-methods.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', PaymentMethod::class);

        $search = trim((string) $request->query('search', ''));

        $paymentMethods = PaymentMethod::query()
            ->withCount(['payments', 'expenses'])
            ->when($search !== '', static function (Builder $query) use ($search): void {
                $query->where(function (Builder $paymentMethodQuery) use ($search): void {
                    $paymentMethodQuery
                        ->where('name', 'like', sprintf('%%%s%%', $search))
                        ->orWhere('slug', 'like', sprintf('%%%s%%', $search));
                });
            })
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('payment_methods.index', compact('paymentMethods', 'search'));
    }

    public function create(): View
    {
        $this->authorize('create', PaymentMethod::class);

        return view('payment_methods.create');
    }

    public function store(StorePaymentMethodRequest $request, CreatePaymentMethodAction $action): RedirectResponse
    {
        $action->handle($request->validated());

        return to_route('payment-methods.index')->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        $this->authorize('update', $paymentMethod);

        return view('payment_methods.edit', compact('paymentMethod'));
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod, UpdatePaymentMethodAction $action): RedirectResponse
    {
        $action->handle($paymentMethod, $request->validated());

        return to_route('payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy(DeletePaymentMethodRequest $request, PaymentMethod $paymentMethod, DeletePaymentMethodAction $action): RedirectResponse
    {
        $action->handle($paymentMethod);

        return to_route('payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }
}
