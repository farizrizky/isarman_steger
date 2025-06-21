<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Scaffolding</title>
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #cccccc;
        }

        .table-primary {
            background-color: #cce5ff;
        }

        .table-success {
            background-color: #d4edda;
        }

        .table-info {
            background-color: #d1ecf1;
        }

        .table-warning {
            background-color: #fff3cd;
        }

        .table-danger {
            background-color: #f8d7da;
        }

        h3 {
            margin-bottom: 0;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">LAPORAN STOK ITEM <br> CV. ISARMAN STEGER BENGKULU</h2>
    <h3 class="mb-3" style="text-align:center;"><i>{{ HDate::fullDateFormat(now()) }}</i></h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Stok Total</th>
                <th class="table-primary">Belum Tersewa</th>
                <th class="table-success">Tersewa</th>
                <th class="table-info">Perbaikan</th>
                <th class="table-warning">Rusak</th>
                <th class="table-danger">Hilang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stock as $s)
            @if($s->item)
            <tr>
                <td>{{ HID::genId($s->item_id) }}</td>
                <td>{{ $s->item?->item_name }}</td>
                <td>{{ number_format($s->stock_total, 0, ',', '.') }}</td>
                <td class="table-primary">{{ number_format($s->stock_available, 0, ',', '.') }}</td>
                <td class="table-success">{{ number_format($s->stock_rented, 0, ',', '.') }}</td>
                <td class="table-info">{{ number_format($s->stock_on_repair, 0, ',', '.') }}</td>
                <td class="table-warning">{{ number_format($s->stock_damaged, 0, ',', '.') }}</td>
                <td class="table-danger">{{ number_format($s->stock_lost, 0, ',', '.') }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
