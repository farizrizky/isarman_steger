<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas</title>
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
    <h2>LAPORAN ARUS KAS <br> CV. ISARMAN STEGER BENGKULU</h2>
    @if(isset($cash_flow_start_date) && isset($cash_flow_end_date))
        <p class="sub-title">Periode: {{ \HDate::dateFormat($cash_flow_start_date) }} s.d. {{ \HDate::dateFormat($cash_flow_end_date) }}</p>
    @endif

    <p><strong>Saldo Kas Terakhir: </strong>Rp <span class="currency">{{ number_format($cash_balance_before, 0, ',', '.') }}</span></p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Saldo Kas</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($cash_flow as $cf)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \HDate::dateFormat($cf->created_at) }}</td>
                <td>{{ $cf->cash_flow_category }}</td>
                <td>
                    {{ $cf->cash_flow_description }}<br>
                    @if($cf->cash_flow_category == 'Pemasukan')
                        @if(in_array($cf->cash_flow_income_category, ['Penyewaan', 'Pembayaran Denda']))
                            No. {{ HID::genNumberRent($cf->cash_flow_reference_id) }}
                        @endif
                    @else
                        @if($cf->cash_flow_expense_category == 'Pengembalian Deposit')
                            No. {{ HID::genNumberRent($cf->cash_flow_reference_id) }}
                        @elseif(in_array($cf->cash_flow_expense_category, ['Operasional', 'Non Operasional']))
                            ID. {{ HID::genId($cf->cash_flow_reference_id) }}
                        @elseif($cf->cash_flow_expense_category == 'Perbaikan Item')
                            ID. {{ HID::genId($cf->cash_flow_reference_id) }}
                        @endif
                    @endif
                </td>
                <td class="{{ $cf->cash_flow_category == 'Pemasukan' ? 'text-success' : 'text-danger' }}">
                    {{ $cf->cash_flow_category == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($cf->cash_flow_amount, 0, ',', '.') }}
                </td>
                <td class="text-end">Rp {{ number_format($cf->cash_flow_balance_after, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5"><strong>Saldo Akhir Kas</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($cash_balance_after, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
