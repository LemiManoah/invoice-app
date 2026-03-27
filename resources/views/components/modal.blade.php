@props(['name', 'show' => false, 'maxWidth' => '2xl'])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{ show: @js($show) }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
>
    <template x-teleport="body">
        <div
            x-cloak
            x-show="show"
            class="fixed inset-0 z-50"
            style="display: none;"
        >
            <div
                x-show="show"
                class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-900/80"
                x-on:click="show = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            <div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0">
                <div class="flex min-h-full items-center justify-center">
                    <div
                        x-show="show"
                        x-on:click.stop
                        class="relative z-10 w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all {{ $maxWidth }} sm:mx-auto"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
