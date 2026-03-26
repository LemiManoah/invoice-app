<?php

namespace App\Http\Controllers;

use App\Actions\Measurement\CreateMeasurementAction;
use App\Actions\Measurement\DeleteMeasurementAction;
use App\Actions\Measurement\UpdateMeasurementAction;
use App\Http\Requests\StoreMeasurementRequest;
use App\Http\Requests\UpdateMeasurementRequest;
use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MeasurementController extends Controller
{
    public function __construct(
        private readonly CreateMeasurementAction $createMeasurement,
        private readonly UpdateMeasurementAction $updateMeasurement,
        private readonly DeleteMeasurementAction $deleteMeasurement,
    ) {
    }

    public function index(Customer $customer): View
    {
        $measurements = $customer->measurements()->latest()->get();

        return view('measurements.index', compact('customer', 'measurements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer): View
    {
        return view('measurements.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeasurementRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();
        ($this->createMeasurement)($customer, $data);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Measurements recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Measurement $measurement): View
    {
        return view('measurements.show', compact('measurement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Measurement $measurement): View
    {
        $customer = $measurement->customer;

        return view('measurements.edit', compact('measurement', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMeasurementRequest $request, Measurement $measurement): RedirectResponse
    {
        $data = $request->validated();
        ($this->updateMeasurement)($measurement, $data);

        return redirect()->route('customers.show', $measurement->customer)
            ->with('success', 'Measurements updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Measurement $measurement): RedirectResponse
    {
        ($this->deleteMeasurement)($measurement);

        return redirect()->route('customers.show', $measurement->customer)
            ->with('success', 'Measurement record deleted.');
    }
}
