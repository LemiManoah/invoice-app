<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 24px; }
        h1, h2, h3, p { margin: 0; }
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

    @yield('content')
</body>
</html>
