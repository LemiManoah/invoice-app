<x-layouts.app title="Edit Customer">
    <div class="mb-6">
        <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Customer
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Customer</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $customer->full_name) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number *</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="alternative_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alternative Phone</label>
                    <input type="text" name="alternative_phone" id="alternative_phone" value="{{ old('alternative_phone', $customer->alternative_phone) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                    <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        <option value="">Select Gender</option>
                        <option value="Male" @selected(old('gender', $customer->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $customer->gender) === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender', $customer->gender) === 'Other')>Other</option>
                    </select>
                </div>
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <textarea name="address" id="address" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">{{ old('address', $customer->address) }}</textarea>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">{{ old('notes', $customer->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
