@php
    $businessName = $businessProfile?->name ?: config('app.name');
    $topMeta = collect([
        $businessProfile?->location,
        $businessProfile?->email,
        $businessProfile?->contacts,
    ])->filter()->implode(' | ');
@endphp

<div class="document-brand">
    <div class="document-brand__logo-wrap">
        @if($businessProfile?->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->logo_path) }}" alt="{{ $businessName }} logo" class="document-brand__logo">
        @else
            <div class="document-brand__logo-fallback">
                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($businessName, 0, 2)) }}
            </div>
        @endif
    </div>

    <div class="document-brand__content">
        <div class="document-brand__name">{{ $businessName }}</div>
        @if($topMeta !== '')
            <div class="document-brand__meta">{{ $topMeta }}</div>
        @endif
    </div>
</div>
