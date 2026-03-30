<x-layouts.app title="Business Profile">
    @php
        $canEdit = auth()->user()?->can('update', $businessProfile);
        $canDelete = auth()->user()?->can('delete', $businessProfile);
    @endphp

    {{-- Page header --}}
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $businessProfile->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Business profile used across invoices, receipts, and printed documents.
                <span class="ml-1">Last updated {{ $businessProfile->updated_at->format('d M Y') }}.</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if($canEdit)
                <a href="{{ route('business-profile.edit') }}"
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </a>
            @endif
            @if($canDelete)
                <button type="button"
                    @click="$dispatch('open-delete-modal', { url: '{{ route('business-profile.destroy') }}', item: 'the business profile' })"
                    class="inline-flex items-center rounded-md border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-900/20">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Business Information --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-building mr-2 text-gray-400"></i> Business Information
                    </h2>
                </div>
                <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="flex items-start gap-4 px-6 py-4">
                        <dt class="w-36 shrink-0 text-sm font-medium text-gray-500 dark:text-gray-400">Business Name</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $businessProfile->name }}</dd>
                    </div>
                    <div class="flex items-start gap-4 px-6 py-4">
                        <dt class="w-36 shrink-0 text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $businessProfile->location ?: '—' }}</dd>
                    </div>
                    <div class="flex items-start gap-4 px-6 py-4">
                        <dt class="w-36 shrink-0 text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="break-all text-sm text-gray-900 dark:text-white">{{ $businessProfile->email ?: '—' }}</dd>
                    </div>
                    <div class="flex items-start gap-4 px-6 py-4">
                        <dt class="w-36 shrink-0 text-sm font-medium text-gray-500 dark:text-gray-400">Contacts</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $businessProfile->contacts ?: '—' }}</dd>
                    </div>
                    <div class="flex items-start gap-4 px-6 py-4">
                        <dt class="w-36 shrink-0 text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                        <dd class="whitespace-pre-line text-sm leading-6 text-gray-900 dark:text-white">{{ $businessProfile->address ?: '—' }}</dd>
                    </div>
                    <div class="flex items-start gap-4 px-6 py-4">
                        <dt class="w-36 shrink-0 text-sm font-medium text-gray-500 dark:text-gray-400">PO Box</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $businessProfile->po_box ?: '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Brand Assets sidebar --}}
        <div class="space-y-6">

            {{-- Logo --}}
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-image mr-2 text-gray-400"></i> Business Logo
                    </h2>
                </div>
                <div class="flex h-44 items-center justify-center bg-white p-4">
                    @if($businessProfile->logo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->logo_path) }}"
                            alt="Business logo"
                            class="max-h-full max-w-full object-contain">
                    @else
                        <div class="text-center">
                            <i class="fas fa-image text-4xl text-gray-200"></i>
                            <p class="mt-2 text-sm text-gray-400">No logo uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Signature --}}
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-signature mr-2 text-gray-400"></i> Company Signature
                    </h2>
                </div>
                <div class="flex h-36 items-center justify-center bg-white p-4">
                    @if($businessProfile->signature_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->signature_path) }}"
                            alt="Company signature"
                            class="max-h-full max-w-full object-contain">
                    @else
                        <div class="text-center">
                            <i class="fas fa-signature text-4xl text-gray-200"></i>
                            <p class="mt-2 text-sm text-gray-400">No signature saved</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
