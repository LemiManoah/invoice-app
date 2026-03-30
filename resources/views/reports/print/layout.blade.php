<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 24px; }
        h1, h2, h3, p { margin: 0; }
        .document-brand { display: flex; align-items: center; gap: 16px; padding-bottom: 18px; margin-bottom: 24px; border-bottom: 2px solid #0f172a; }
        .document-brand__logo-wrap { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .document-brand__logo { max-width: 72px; max-height: 72px; object-fit: contain; }
        .document-brand__logo-fallback { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; border-radius: 16px; background: #e2e8f0; color: #0f172a; font-size: 24px; font-weight: 700; }
        .document-brand__content { display: flex; flex-direction: column; gap: 6px; }
        .document-brand__name { font-size: 28px; font-weight: 700; color: #0f172a; }
        .document-brand__meta { font-size: 12px; color: #475569; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .meta { color: #6b7280; font-size: 12px; }
        .actions { margin-bottom: 16px; }
        .actions button, .actions a { padding: 8px 12px; border: 0; background: #111827; color: white; text-decoration: none; cursor: pointer; margin-right: 8px; }
        .cards { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .card { border: 1px solid #d1d5db; padding: 12px; min-width: 180px; }
        .label { font-size: 11px; text-transform: uppercase; color: #6b7280; margin-bottom: 6px; }
        .value { font-size: 20px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; vertical-align: top; font-size: 13px; }
        th { background: #f3f4f6; }
        .text-right { text-align: right; }
        .section { margin-top: 24px; }
        .section-title { margin-bottom: 12px; font-size: 16px; font-weight: 700; }
        .muted { color: #6b7280; }
        .document-footer { margin-top: 28px; padding-top: 14px; border-top: 1px solid #cbd5e1; font-size: 11px; color: #475569; display: flex; flex-wrap: wrap; gap: 8px 14px; }
        @media print {
            .actions { display: none; }
            body { margin: 12px; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button type="button" onclick="window.print()">Print</button>
        <a href="javascript:window.close()">Close</a>
    </div>

    @include('print.partials.business-header')

    @yield('content')

    @include('print.partials.business-footer')
</body>
</html>
