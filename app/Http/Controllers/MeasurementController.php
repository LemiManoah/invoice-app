<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Measurement\CreateMeasurementAction;
use App\Actions\Measurement\DeleteMeasurementAction;
use App\Actions\Measurement\UpdateMeasurementAction;
use App\Http\Requests\StoreMeasurementRequest;
use App\Http\Requests\UpdateMeasurementRequest;
use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class MeasurementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:measurements.view', only: ['index', 'show']),
            new Middleware('permission:measurements.create', only: ['create', 'store']),
            new Middleware('permission:measurements.update', only: ['edit', 'update']),
            new Middleware('permission:measurements.delete', only: ['destroy']),
        ];
    }

    public function index(Customer $customer): View
    {
        $this->authorize('viewAny', Measurement::class);

        $measurements = $customer->measurements()->latest()->get();

        return view('measurements.index', compact('customer', 'measurements'));
    }

    public function create(Customer $customer): View
    {
        $this->authorize('create', Measurement::class);

        return view('measurements.create', compact('customer'));
    }

    public function store(
        StoreMeasurementRequest $request,
        Customer $customer,
        CreateMeasurementAction $action,
    ): RedirectResponse {
        $this->authorize('create', Measurement::class);

        $action->handle($customer, $request->validated());

        return to_route('customers.show', $customer)
            ->with('success', 'Measurements recorded successfully.');
    }

    public function show(Measurement $measurement): View
    {
        $this->authorize('view', $measurement);

        return view('measurements.show', compact('measurement'));
    }

    public function edit(Measurement $measurement): View
    {
        $this->authorize('update', $measurement);

        $customer = $measurement->customer;

        return view('measurements.edit', compact('measurement', 'customer'));
    }

    public function update(
        UpdateMeasurementRequest $request,
        Measurement $measurement,
        UpdateMeasurementAction $action,
    ): RedirectResponse {
        $this->authorize('update', $measurement);

        $action->handle($measurement, $request->validated());

        return to_route('customers.show', $measurement->customer)
            ->with('success', 'Measurements updated successfully.');
    }

    public function destroy(Measurement $measurement, DeleteMeasurementAction $action): RedirectResponse
    {
        $this->authorize('delete', $measurement);

        $customer = $measurement->customer;
        $action->handle($measurement);

        return to_route('customers.show', $customer)
            ->with('success', 'Measurement record deleted.');
    }
}
