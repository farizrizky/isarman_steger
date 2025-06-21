<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan</title>
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .sub-title { text-align: center; margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #cccccc; border: 1px solid #000; padding: 6px; text-align: center; }
        .text-end { text-align: right; }
        .text-success { color: green; }
        .text-danger { color: red; }
    </style>
</head>
<body>
    <h2>LAPORAN PEMASUKAN <br> CV. ISARMAN STEGER BENGKULU</h2>
    @if(isset($cash_flow_start_date) && isset($cash_flow_end_date))
        <p class="sub-title">Periode: {{ \HDate::dateFormat($cash_flow_start_date) }} s.d. {{ \HDate::dateFormat($cash_flow_end_date) }}</p>
    @endif

    <p><strong>Total Pemasukan Terakhir: </strong>Rp <span class="currency">{{ number_format($income_total_before, 0, ',', '.') }}</span></p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Total Pemasukan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($cash_flow as $cf)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \HDate::dateFormat($cf->created_at) }}</td>
                <td>{{ $cf->cash_flow_income_category }}</td>
                <td>
                    {{ $cf->cash_flow_description }}<br>
                    @if(in_array($cf->cash_flow_income_category, ['Penyewaan', 'Pembayaran Denda']))
                        No. {{ HID::genNumberRent($cf->cash_flow_reference_id) }}
                    @endif
                </td>
                <td>
                    Rp {{ number_format($cf->cash_flow_amount, 0, ',', '.') }}
                </td>
                <td class="text-end">Rp {{ number_format($cf->cash_flow_income_total_after, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5"><strong>Total Akhir Pemasukan</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($income_total_after, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
