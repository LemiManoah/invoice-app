<x-layouts.app title="Add Measurements for {{ $customer->full_name }}">
    <div class="mb-6">
        <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to {{ $customer->full_name }}
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Record New Measurements</h1>
    </div>

    <form action="{{ route('customers.measurements.store', $customer) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Measurement Group: Upper Body -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Upper Body</h2>
                <div class="space-y-4">
                    <div>
                        <label for="neck" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Neck</label>
                        <input type="number" step="0.01" name="neck" id="neck" value="{{ old('neck') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="chest" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chest</label>
                        <input type="number" step="0.01" name="chest" id="chest" value="{{ old('chest') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="waist" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waist</label>
                        <input type="number" step="0.01" name="waist" id="waist" value="{{ old('waist') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="shoulder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Shoulder</label>
                        <input type="number" step="0.01" name="shoulder" id="shoulder" value="{{ old('shoulder') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="sleeve_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sleeve Length</label>
                        <input type="number" step="0.01" name="sleeve_length" id="sleeve_length" value="{{ old('sleeve_length') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="jacket_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jacket Length</label>
                        <input type="number" step="0.01" name="jacket_length" id="jacket_length" value="{{ old('jacket_length') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                </div>
            </div>

            <!-- Measurement Group: Lower Body -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Lower Body</h2>
                <div class="space-y-4">
                    <div>
                        <label for="trouser_waist" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trouser Waist</label>
                        <input type="number" step="0.01" name="trouser_waist" id="trouser_waist" value="{{ old('trouser_waist') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="hips" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hips</label>
                        <input type="number" step="0.01" name="hips" id="hips" value="{{ old('hips') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="trouser_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trouser Length</label>
                        <input type="number" step="0.01" name="trouser_length" id="trouser_length" value="{{ old('trouser_length') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="inseam" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Inseam</label>
                        <input type="number" step="0.01" name="inseam" id="inseam" value="{{ old('inseam') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="thigh" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thigh</label>
                        <input type="number" step="0.01" name="thigh" id="thigh" value="{{ old('thigh') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="knee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Knee</label>
                        <input type="number" step="0.01" name="knee" id="knee" value="{{ old('knee') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    <div>
                        <label for="cuff" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuff</label>
                        <input type="number" step="0.01" name="cuff" id="cuff" value="{{ old('cuff') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                </div>
            </div>

            <!-- Group: General & Notes -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">General</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Height</label>
                            <input type="number" step="0.01" name="height" id="height" value="{{ old('height') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight</label>
                            <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div>
                            <label for="measurement_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Measurement Date *</label>
                            <input type="date" name="measurement_date" id="measurement_date" value="{{ date('Y-m-d') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_current" id="is_current" value="1" checked
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_current" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Set as Current Measurements</label>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Notes</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="posture_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Posture Notes</label>
                            <textarea name="posture_notes" id="posture_notes" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('posture_notes') }}</textarea>
                        </div>
                        <div>
                            <label for="fitting_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fitting Notes</label>
                            <textarea name="fitting_notes" id="fitting_notes" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('fitting_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition shadow-lg">
                    Save Measurement Record
                </button>
            </div>
        </div>
    </form>
</x-layouts.app>
