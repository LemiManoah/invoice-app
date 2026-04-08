<x-layouts.app title="Edit Measurement">
    <div class="mb-6">
        <a href="{{ route('customers.show', $measurement->customer) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to {{ $measurement->customer->full_name }}
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Measurements</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All measurements in inches</p>
    </div>

    <div x-data="{
        selectedPieces: {{ json_encode($measurement->pieces()) }},
        gender: '{{ $measurement->customer->gender }}',
        toggle(piece) {
            if (this.selectedPieces.includes(piece)) {
                this.selectedPieces = this.selectedPieces.filter(p => p !== piece);
            } else {
                this.selectedPieces.push(piece);
            }
        },
        has(piece) { return this.selectedPieces.includes(piece); }
    }">
        <form action="{{ route('measurements.update', $measurement) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Piece Selection -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Select Garment Pieces to Measure</h2>
                <div class="flex flex-wrap gap-3">
                    <button type="button" @click="toggle('Jacket')"
                        :class="has('Jacket') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-blue-400'"
                        class="px-5 py-2 rounded-md text-sm font-medium border transition">
                        <i class="fas fa-vest mr-1"></i> Jacket
                    </button>
                    <button type="button" @click="toggle('Trouser')"
                        :class="has('Trouser') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-blue-400'"
                        class="px-5 py-2 rounded-md text-sm font-medium border transition">
                        <i class="fas fa-arrows-alt-v mr-1"></i> Trouser
                    </button>
                    <button type="button" @click="toggle('Waistcoat')"
                        :class="has('Waistcoat') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-blue-400'"
                        class="px-5 py-2 rounded-md text-sm font-medium border transition">
                        <i class="fas fa-tshirt mr-1"></i> Waistcoat
                    </button>
                    <button type="button" @click="toggle('Skirt')" x-show="gender === 'Female'"
                        :class="has('Skirt') ? 'bg-pink-600 text-white border-pink-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-pink-400'"
                        class="px-5 py-2 rounded-md text-sm font-medium border transition">
                        <i class="fas fa-female mr-1"></i> Skirt
                    </button>
                    <button type="button" @click="toggle('Shirt')"
                        :class="has('Shirt') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-blue-400'"
                        class="px-5 py-2 rounded-md text-sm font-medium border transition">
                        <i class="fas fa-tshirt mr-1"></i> Shirt
                    </button>
                </div>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Pieces with existing measurements are pre-selected. Deselecting a piece will clear its values on save.</p>
            </div>

            <!-- Jacket Measurements -->
            <div x-show="has('Jacket')" x-transition class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="fas fa-vest text-blue-500 mr-2"></i> Jacket Measurements
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        'jacket_shoulder' => 'Shoulder Length',
                        'jacket_chest' => 'Chest Width',
                        'jacket_stomach_waist' => 'Stomach Waist',
                        'jacket_sleeve' => 'Sleeve Length',
                        'jacket_length' => 'Jacket Length',
                        'jacket_biceps' => 'Biceps',
                        'jacket_wrist' => 'Wrist',
                        'jacket_lower_arm' => 'Lower Arm',
                        'jacket_hip_line' => 'Hip Line',
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="number" step="0.1" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" placeholder="0.0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Trouser Measurements -->
            <div x-show="has('Trouser')" x-transition class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="fas fa-arrows-alt-v text-blue-500 mr-2"></i> Trouser Measurements
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        'trouser_waist' => 'Waist',
                        'trouser_thigh_cuff' => 'Thigh Cuff',
                        'trouser_length_fit' => 'Length Fit',
                        'trouser_ankle_fit' => 'Ankle Fit / Hem',
                        'trouser_knee_fit' => 'Knee Fit',
                        'trouser_fly_fit' => 'Fly Fit',
                        'trouser_hips' => 'Hips',
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="number" step="0.1" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" placeholder="0.0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Waistcoat Measurements -->
            <div x-show="has('Waistcoat')" x-transition class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="fas fa-tshirt text-blue-500 mr-2"></i> Waistcoat Measurements
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        'waistcoat_chest' => 'Chest Width',
                        'waistcoat_waist' => 'Waist Width',
                        'waistcoat_length' => 'Length',
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="number" step="0.1" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" placeholder="0.0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Skirt Measurements -->
            <div x-show="has('Skirt')" x-transition class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-pink-200 dark:border-pink-900/40 mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="fas fa-female text-pink-500 mr-2"></i> Skirt Measurements
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        'skirt_waist' => 'Waist',
                        'skirt_hip_line' => 'Hip Line',
                        'skirt_full_length' => 'Full Length',
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="number" step="0.1" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" placeholder="0.0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shirt Measurements -->
            <div x-show="has('Shirt')" x-transition class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="fas fa-tshirt text-blue-500 mr-2"></i> Shirt Measurements
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        'shirt_chest' => 'Chest Line',
                        'shirt_waist' => 'Waist Line',
                        'shirt_shoulder' => 'Shoulder Line',
                        'shirt_full_length' => 'Full Length',
                        'shirt_bottom_cut' => 'Bottom / Cut',
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
                            <input type="number" step="0.1" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $measurement->{$field}) }}" placeholder="0.0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Date, Status & Notes -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="measurement_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Measurement Date *</label>
                        <input type="date" name="measurement_date" id="measurement_date" value="{{ old('measurement_date', $measurement->measurement_date?->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center pt-6">
                        <input type="checkbox" name="is_current" id="is_current" value="1" @checked(old('is_current', $measurement->is_current))
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_current" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Set as current measurements</label>
                    </div>
                    <div class="md:col-span-2">
                        <label for="fitting_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Notes</label>
                        <textarea name="fitting_notes" id="fitting_notes" rows="3" placeholder="Any special notes about fit, posture, or style preferences..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('fitting_notes', $measurement->fitting_notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('customers.show', $measurement->customer) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                        Update Measurements
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
