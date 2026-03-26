<x-layouts.app title="Edit Expense">
    <div class="mb-6">
        <a href="{{ route('expenses.show', $expense) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Expense
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Expense</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('expenses.update', $expense) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="expense_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                    <select name="expense_category_id" id="expense_category_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('expense_category_id', $expense->expense_category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expense Date *</label>
                    <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount *</label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method *</label>
                    <select name="payment_method" id="payment_method" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @foreach(['Cash', 'Bank Transfer', 'Mobile Money', 'Card', 'Other'] as $method)
                            <option value="{{ $method }}" @selected(old('payment_method', $expense->payment_method) === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="vendor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vendor Name</label>
                    <input type="text" name="vendor_name" id="vendor_name" value="{{ old('vendor_name', $expense->vendor_name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number', $expense->reference_number) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $expense->description) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('notes', $expense->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Update Expense
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
