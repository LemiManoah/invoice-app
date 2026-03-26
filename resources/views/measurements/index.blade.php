<x-layouts.app title="Measurements">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Customer
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Measurement History</h1>
        </div>
        @can('create', \App\Models\Measurement::class)
            <a href="{{ route('customers.measurements.create', $customer) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                <i class="fas fa-plus mr-2"></i> Add Measurement
            </a>
        @endcan
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chest</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waist</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($measurements as $measurement)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $measurement->measurement_date?->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $measurement->chest ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $measurement->waist ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $measurement->is_current ? 'Current' : 'Archived' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-3">
                                @can('view', $measurement)
                                    <a href="{{ route('measurements.show', $measurement) }}" class="text-blue-600 dark:text-blue-400">View</a>
                                @endcan
                                @can('update', $measurement)
                                    <a href="{{ route('measurements.edit', $measurement) }}" class="text-yellow-600 dark:text-yellow-400">Edit</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No measurements recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
