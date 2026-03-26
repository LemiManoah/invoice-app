<x-layouts.app title="Measurement Details">
    <div class="mb-6">
        <a href="{{ route('customers.show', $measurement->customer) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Customer
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Measurement Details</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                'Neck' => $measurement->neck,
                'Chest' => $measurement->chest,
                'Waist' => $measurement->waist,
                'Hips' => $measurement->hips,
                'Shoulder' => $measurement->shoulder,
                'Sleeve Length' => $measurement->sleeve_length,
                'Jacket Length' => $measurement->jacket_length,
                'Trouser Waist' => $measurement->trouser_waist,
                'Trouser Length' => $measurement->trouser_length,
                'Inseam' => $measurement->inseam,
                'Thigh' => $measurement->thigh,
                'Knee' => $measurement->knee,
                'Cuff' => $measurement->cuff,
                'Height' => $measurement->height,
                'Weight' => $measurement->weight,
            ] as $label => $value)
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">{{ $label }}</p>
                    <p class="text-gray-900 dark:text-white">{{ $value ?? '-' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
