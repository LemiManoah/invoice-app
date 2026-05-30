@props([
    'name',
    'id' => null,
    'required' => false,
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select...',
])

@php
    $safeId = $id ?? $name;
    $safeSelected = (string) $selected;
    $safeOptions = collect($options)
        ->map(fn($o) => ['value' => (string) ($o['value'] ?? ''), 'label' => (string) ($o['label'] ?? '')])
        ->values()
        ->toArray();
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }}
    x-data="searchableSelect({{ Illuminate\Support\Js::from($safeOptions) }}, {{ Illuminate\Support\Js::from($placeholder) }}, {{ Illuminate\Support\Js::from($safeSelected) }})"
    @click.outside="open = false"
>
    <input type="hidden" name="{{ $name }}" :value="selectedValue">

    <button
        type="button"
        id="{{ $safeId }}"
        @click="toggleOpen()"
        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 dark:text-white text-sm text-left flex items-center justify-between focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
        @if($required) aria-required="true" @endif
    >
        <span x-text="displayLabel" :class="!selectedValue ? 'text-gray-500 dark:text-gray-400' : ''"></span>
        <svg class="w-4 h-4 shrink-0 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-75"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-50"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md shadow-lg"
    >
        <div class="p-1.5 border-b border-gray-100 dark:border-gray-700">
            <input
                type="text"
                x-model="search"
                x-ref="searchInput"
                placeholder="Type to search..."
                class="w-full px-2.5 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                @click.stop
                @keydown.escape="open = false"
                @keydown.enter.prevent="selectFirst()"
            >
        </div>
        <ul class="max-h-52 overflow-y-auto py-1">
            <template x-for="opt in filteredOptions" :key="opt.value || '__empty__'">
                <li
                    @click="selectOption(opt)"
                    class="px-3 py-1.5 text-sm cursor-pointer"
                    :class="String(opt.value) === String(selectedValue)
                        ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 font-medium'
                        : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700'"
                    x-text="opt.label"
                ></li>
            </template>
            <li
                x-show="filteredOptions.length === 0"
                class="px-3 py-2 text-sm text-gray-400 dark:text-gray-500 text-center italic"
            >No options found</li>
        </ul>
    </div>
</div>
