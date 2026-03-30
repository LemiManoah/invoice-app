@php
    $footerItems = collect([
        $businessProfile?->address,
        $businessProfile?->po_box ? 'PO Box: '.$businessProfile->po_box : null,
        $businessProfile?->contacts ? 'Contacts: '.$businessProfile->contacts : null,
        $businessProfile?->email ? 'Email: '.$businessProfile->email : null,
        $businessProfile?->location ? 'Location: '.$businessProfile->location : null,
    ])->filter()->values();
@endphp

@if($footerItems->isNotEmpty())
    <div class="document-footer">
        @foreach($footerItems as $item)
            <span>{{ $item }}</span>
        @endforeach
    </div>
@endif
