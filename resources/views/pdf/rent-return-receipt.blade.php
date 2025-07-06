<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kwitansi Pengembalian</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            color: #000;
            margin: 0;
        }

        header {
            text-align: center;
        }

        .title {
            font-size: 20px;
            margin: 0;
        }

        .number, .return_date {
            font-size: 13px;
            margin: 0;
        }

        .extend_id {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .data, .duration {
            font-size: 12px;
            margin: 0;
        }

        .duration {
            text-align: right;
        }

        #table_rent {
            width: 100%;
            border-collapse: collapse;
        }

        #table_rent td {
            vertical-align: top;
            padding: 10px;
        }

        #table_subtotal {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
        }

        #table_subtotal th, #table_subtotal td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        #table_subtotal thead th {
            background-color: #ddd;
        }

        #table_subtotal tfoot td {
            background-color: #f2f2f2;
        }

        .note {
            font-size: 13px;
            text-align: center;
            margin-top: 20px;
        }

        #signature {
            width: 100%;
            margin-top: 10px;
            font-size: 13px;
        }

        #signature-left,
        #signature-right {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        @if($rent->rent_is_extension==1)
            <div class="extend_id">
                <small>Sewa Lanjutan No. {{ HID::genNumberRent($rent_extend->rent_id) }}</small>
            </div>
        @endif

        <header>
            <img src="/assets/img/Kop.png" width="600">
            <hr>
            @if ($rent->rentReturn->rent_return_receipt_status == 'Klaim Ganti Rugi')
                <h2 class="title">KWITANSI KLAIM GANTI RUGI</h2>
            @elseif ($rent->rentReturn->rent_return_receipt_status == 'Pengembalian Deposit')
                <h2 class="title">KWITANSI PENGEMBALIAN DEPOSIT</h2>
            @else
                <h2 class="title">KWITANSI PENGEMBALIAN SEWA</h2>
            @endif
            <h3 class="number">Nomor: {{ HID::genNumberRent($rent->rent_id) }}</h3>
            <h3 class="return_date">Tanggal Pengembalian Sewa: {{ HDate::dateFormat($rent->rentReturn->rent_return_date) }}</h3>
        </header>

        <table id="table_rent">
            <tr>
                <td class="data">
                    <i><small>Penyewa:</small></i><br>
                    <strong>{{ $rent->renter->renter_name }}</strong><br>
                    Telepon : {{ $rent->renter->renter_phone }}<br>
                    {{ $rent->rent_project_name }}<br>
                    <i>{{ $rent->rent_project_address }}</i>
                </td>
                <td class="duration">
                    <i><small>Durasi Sewa:</small></i><br>
                    <strong>
                        @if($rent->rent_duration == '2 Minggu')
                            2 Minggu
                        @elseif($rent->rent_duration == 'Per Bulan')
                            {{ $rent->rent_total_duration }} Bulan
                        @endif
                    </strong><br>
                    {{ date('d-m-Y', strtotime($rent->rent_start_date)) }} s.d {{ date('d-m-Y', strtotime($rent->rent_end_date)) }}
                </td>
            </tr>
        </table>

        <table id="table_subtotal">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Item</th>
                    <th>Disewa</th>
                    <th>Hilang</th>
                    <th>Rusak</th>
                    <th>Total Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item as $i => $it)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>({{ $it['item_id'] }}) {{ $it['item_name'] }}</td>
                        <td>{{ number_format($it['item_quantity'], 0, ',', '.') }} {{ $it['item_unit'] }}</td>
                        <td>{{ number_format($it['item_lost'], 0, ',', '.') }} {{ $it['item_unit'] }}</td>
                        <td>{{ number_format($it['item_damaged'], 0, ',', '.') }} {{ $it['item_unit'] }}</td>
                        <td>Rp {{ number_format($it['total_fine'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">Total Denda</td>
                    <td>Rp {{ number_format($rent->rentReturn->rent_return_total_fine, 0, ',', '.') }}</td>
                </tr>
                @if($rent->rentReturn->rent_return_dispensation_fine > 0)
                    <tr>
                        <td colspan="5">Dispensasi Denda</td>
                        <td>Rp {{ number_format($rent->rentReturn->rent_return_dispensation_fine, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if($rent->rentReturn->rent_return_deposit_saldo > 0)
                    <tr>
                        <td colspan="5">Deposit</td>
                        <td>Rp {{ number_format($rent->rentReturn->rent_return_deposit_saldo, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5"><strong>Total {{ $rent->rentReturn->rent_return_receipt_status }}</strong></td>
                    <td><strong>Rp {{ number_format($rent->rentReturn->rent_return_total_payment, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <table id="signature">
            <tr>
                <td id="signature-left">
                    <br><br><br>
                    <p>Penyewa</p><br><br>
                    <strong>{{ $rent->renter->renter_name }}</strong>
                </td>
                <td id="signature-right">
                    <p>Bengkulu, .............................<br><br>{{ HUser::userSigned('LoggedIn User')['level'] }}<br>CV. Isarman Steger Bengkulu</p><br><br>
                    <strong>{{ HUser::userSigned('LoggedIn User')['fullname'] }}</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
