<x-layouts.app title="Edit Measurement">
    <div class="mb-6">
        <a href="{{ route('measurements.show', $measurement) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Measurement
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Measurement</h1>
    </div>

    <form action="{{ route('measurements.update', $measurement) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Upper Body</h2>
                <div class="space-y-4">
                    @foreach(['neck', 'chest', 'waist', 'shoulder', 'sleeve_length', 'jacket_length'] as $field)
                        <div>
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                            <input type="number" step="0.01" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Lower Body</h2>
                <div class="space-y-4">
                    @foreach(['trouser_waist', 'hips', 'trouser_length', 'inseam', 'thigh', 'knee', 'cuff'] as $field)
                        <div>
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                            <input type="number" step="0.01" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="space-y-4">
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Height</label>
                            <input type="number" step="0.01" name="height" id="height" value="{{ old('height', $measurement->height) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight</label>
                            <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $measurement->weight) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div>
                            <label for="measurement_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Measurement Date *</label>
                            <input type="date" name="measurement_date" id="measurement_date" value="{{ old('measurement_date', $measurement->measurement_date?->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_current" id="is_current" value="1" @checked(old('is_current', $measurement->is_current)) class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_current" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Set as Current Measurements</label>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="space-y-4">
                        <div>
                            <label for="posture_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Posture Notes</label>
                            <textarea name="posture_notes" id="posture_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('posture_notes', $measurement->posture_notes) }}</textarea>
                        </div>
                        <div>
                            <label for="fitting_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fitting Notes</label>
                            <textarea name="fitting_notes" id="fitting_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('fitting_notes', $measurement->fitting_notes) }}</textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition shadow-lg">Update Measurement</button>
            </div>
        </div>
    </form>
</x-layouts.app>
