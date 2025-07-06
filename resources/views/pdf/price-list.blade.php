<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Price List</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h3.title {
            text-align: center;
            margin-top: 0;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #555;
        }

        th, td {
            padding: 3px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
    <img src="/assets/img/Kop.png" width="500">
    <hr>
    <h3 class="title">DAFTAR HARGA SEWA SCAFFOLDING</h3>
    <i> {{ \HDate::fullDateFormatWithoutTime(now()) }}</i>
</div>
    <h3>Harga Sewa Item</h3>
    <hr>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Satuan</th>
                <th>Harga Sewa 2 Minggu</th>
                <th>Harga Sewa 1 Bulan</th>
                <th>Klaim Ganti Rugi Kerusakan</th>
                <th>Klaim Ganti Rugi Kehilangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="white-space:nowrap;"><strong>{{ $item->item_name }}</strong></td>
                    <td style="text-align: center;">{{ $item->item_unit }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->item_price_2_weeks, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->item_price_per_month, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->item_fine_damaged, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->item_fine_lost, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <h3>Harga Sewa Set</h3>
    <hr>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Set</th>
                <th>Item Dalam Set</th>
                <th>Harga Sewa 2 Minggu</th>
                <th>Harga Sewa 1 Bulan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($set as $index => $set)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="white-space:nowrap;"><strong>{{ $set->set_name }}</strong></td>
                    <td>
                        @foreach ($set->itemSet as $itemSet)
                            {{ $itemSet->item->item_name }} ({{$itemSet->item_set_quantity}} {{ $itemSet->item->item_unit }})<br>
                        @endforeach
                    </td>
                    <td style="text-align: right;">Rp {{ number_format($set->set_price_2_weeks, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($set->set_price_per_month, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada {{ \HDate::fullDateFormat(now()) }}<br>
    </div>
</body>
</html>
