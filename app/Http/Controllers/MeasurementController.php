<?php

namespace App\Http\Controllers;

use App\Actions\Measurement\CreateMeasurementAction;
use App\Actions\Measurement\DeleteMeasurementAction;
use App\Actions\Measurement\UpdateMeasurementAction;
use App\Http\Requests\StoreMeasurementRequest;
use App\Http\Requests\UpdateMeasurementRequest;
use App\Models\Customer;
use App\Models\Measurement;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer)
    {
        $measurements = $customer->measurements()->latest()->get();

        return view('measurements.index', compact('customer', 'measurements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer)
    {
        return view('measurements.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeasurementRequest $request, Customer $customer)
    {
        $data = $request->validated();
        (new CreateMeasurementAction)($customer, $data);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Measurements recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Measurement $measurement)
    {
        return view('measurements.show', compact('measurement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Measurement $measurement)
    {
        $customer = $measurement->customer;

        return view('measurements.edit', compact('measurement', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMeasurementRequest $request, Measurement $measurement)
    {
        $data = $request->validated();
        (new UpdateMeasurementAction)($measurement, $data);

        return redirect()->route('customers.show', $measurement->customer)
            ->with('success', 'Measurements updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Measurement $measurement)
    {
        (new DeleteMeasurementAction)($measurement);

        return redirect()->route('customers.show', $measurement->customer)
            ->with('success', 'Measurement record deleted.');
    }
}
