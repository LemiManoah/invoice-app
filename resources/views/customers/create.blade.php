<x-layouts.app title="Create Customer">
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Customers
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Add New Customer</h1>
    </div>

    <div x-data="{
        showMeasurements: false,
        gender: '{{ old('gender') }}',
        selectedPieces: [],
        toggle(piece) {
            if (this.selectedPieces.includes(piece)) {
                this.selectedPieces = this.selectedPieces.filter(p => p !== piece);
            } else {
                this.selectedPieces.push(piece);
            }
        },
        has(piece) { return this.selectedPieces.includes(piece); }
    }">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <!-- Customer Info Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number *</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alternative Phone -->
                    <div>
                        <label for="alternative_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alternative Phone</label>
                        <input type="text" name="alternative_phone" id="alternative_phone" value="{{ old('alternative_phone') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                        <select name="gender" id="gender" x-model="gender"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Address -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('address') }}</textarea>
                    </div>

                    <!-- Notes -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Record Measurements Toggle -->
            <div class="mb-4">
                <button type="button" @click="showMeasurements = !showMeasurements"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-dashed border-blue-400 text-blue-600 dark:text-blue-400 dark:border-blue-600 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition text-sm font-medium">
                    <i class="fas fa-ruler-combined"></i>
                    <span x-text="showMeasurements ? 'Hide Measurements' : 'Record Measurements'"></span>
                    <i class="fas fa-chevron-down transition-transform" :class="showMeasurements ? 'rotate-180' : ''"></i>
                </button>
                <span class="ml-2 text-xs text-gray-400 dark:text-gray-500">Optional</span>
            </div>

            <!-- Measurements Panel (inside same form) -->
            <template x-if="showMeasurements">
                <div>
                    <input type="hidden" name="record_measurements" value="1">

                    <!-- Piece Selection -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-blue-200 dark:border-blue-900/50 mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                            <i class="fas fa-ruler-combined text-blue-500"></i> Garment Measurements
                            <span class="text-xs font-normal text-gray-500">(all measurements in inches)</span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Select the pieces to measure:</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <button type="button" @click="toggle('jacket')"
                                :class="has('jacket') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-1.5 rounded text-xs font-medium border transition">Jacket</button>
                            <button type="button" @click="toggle('trouser')"
                                :class="has('trouser') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-1.5 rounded text-xs font-medium border transition">Trouser</button>
                            <button type="button" @click="toggle('waistcoat')"
                                :class="has('waistcoat') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-1.5 rounded text-xs font-medium border transition">Waistcoat</button>
                            <button type="button" @click="toggle('skirt')" x-show="gender === 'Female'"
                                :class="has('skirt') ? 'bg-pink-600 text-white border-pink-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-1.5 rounded text-xs font-medium border transition">Skirt</button>
                            <button type="button" @click="toggle('shirt')"
                                :class="has('shirt') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                class="px-4 py-1.5 rounded text-xs font-medium border transition">Shirt</button>
                        </div>

                        <!-- Jacket -->
                        <div x-show="has('jacket')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                            <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Jacket</h4>
                            <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                                @foreach(['jacket_shoulder'=>'Shoulder','jacket_chest'=>'Chest','jacket_stomach_waist'=>'Stomach','jacket_sleeve'=>'Sleeve','jacket_length'=>'Length','jacket_biceps'=>'Biceps','jacket_wrist'=>'Wrist','jacket_lower_arm'=>'Lower Arm','jacket_hip_line'=>'Hip Line'] as $f=>$l)
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                    <input type="number" step="0.1" name="{{ $f }}" value="{{ old($f) }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Trouser -->
                        <div x-show="has('trouser')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                            <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Trouser</h4>
                            <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                                @foreach(['trouser_waist'=>'Waist','trouser_thigh_cuff'=>'Thigh Cuff','trouser_length_fit'=>'Length','trouser_ankle_fit'=>'Ankle/Hem','trouser_knee_fit'=>'Knee','trouser_fly_fit'=>'Fly','trouser_hips'=>'Hips'] as $f=>$l)
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                    <input type="number" step="0.1" name="{{ $f }}" value="{{ old($f) }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Waistcoat -->
                        <div x-show="has('waistcoat')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                            <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Waistcoat</h4>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(['waistcoat_chest'=>'Chest Width','waistcoat_waist'=>'Waist Width','waistcoat_length'=>'Length'] as $f=>$l)
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                    <input type="number" step="0.1" name="{{ $f }}" value="{{ old($f) }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Skirt -->
                        <div x-show="has('skirt')" x-transition class="mb-4 p-4 bg-pink-50 dark:bg-pink-900/10 rounded-lg border border-pink-100 dark:border-pink-900/30">
                            <h4 class="text-xs font-semibold text-pink-700 dark:text-pink-400 uppercase tracking-wider mb-3">Skirt</h4>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(['skirt_waist'=>'Waist','skirt_hip_line'=>'Hip Line','skirt_full_length'=>'Full Length'] as $f=>$l)
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                    <input type="number" step="0.1" name="{{ $f }}" value="{{ old($f) }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Shirt -->
                        <div x-show="has('shirt')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                            <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Shirt</h4>
                            <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                                @foreach(['shirt_chest'=>'Chest','shirt_waist'=>'Waist','shirt_shoulder'=>'Shoulder','shirt_full_length'=>'Length','shirt_bottom_cut'=>'Bottom/Cut'] as $f=>$l)
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                    <input type="number" step="0.1" name="{{ $f }}" value="{{ old($f) }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Measurement Date & Notes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Measurement Date *</label>
                                <input type="date" name="measurement_date" value="{{ old('measurement_date', date('Y-m-d')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex items-center pt-5">
                                <input type="checkbox" name="is_current" id="is_current_create" value="1" checked
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_current_create" class="ml-2 text-xs text-gray-600 dark:text-gray-400">Set as current measurements</label>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Additional Notes</label>
                                <textarea name="fitting_notes" rows="2" placeholder="Special fit notes..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('fitting_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="flex justify-end space-x-3">
                <button type="reset" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Reset
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
