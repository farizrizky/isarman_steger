<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #ffffff;
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
        .number {
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
        #table_subtotal th,
        #table_subtotal td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        #table_subtotal thead th {
            background-color: #ddd;
            font-weight: bold;
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
        #signature-middle,
        #signature-right {
            width: 33%;
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
            <h2 class="title">KWITANSI PENYEWAAN</h2>
            <h3 class="number">Nomor : {{ HID::genNumberRent($rent->rent_id) }}</h3>
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
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rent->rentSet as $rs)
                <tr>
                    <td>[SET] {{ $rs->set->set_name}}</td>
                    <td>{{ $rs->rent_set_quantity }} Set</td>
                    <td>Rp {{ number_format($rs->rent_set_subtotal_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($rs->rent_set_total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <strong>Item dalam Set:</strong><br>
                        @foreach($rs->rentItem as $ris)
                        - {{ $ris->item->item_name }} {{ $ris->rent_item_quantity }} {{ $ris->item->item_unit }}<br>
                        @endforeach
                    </td>
                </tr>
                @endforeach

                @foreach ($rent->rentItem as $ri)
                @if($ri->rent_set_id == null)
                <tr>
                    <td>[ITEM] {{ $ri->item->item_name }}</td>
                    <td>{{ $ri->rent_item_quantity }} {{ $ri->item->item_unit }}</td>
                    <td>Rp {{ number_format($ri->rent_item_subtotal_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($ri->rent_item_total_price, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Subtotal</td>
                    @php $subtotal = $rent->rent_total_price - $rent->rent_transport_price - $rent->rent_deposit + $rent->rent_discount; @endphp
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($rent->rent_deposit > 0)
                <tr>
                    <td colspan="3">Deposit</td>
                    <td>Rp {{ number_format($rent->rent_deposit, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3">Transport</td>
                    <td>Rp {{ number_format($rent->rent_transport_price, 0, ',', '.') }}</td>
                </tr>
                @if($rent->rent_discount > 0)
                <tr>
                    <td colspan="3">Diskon</td>
                    <td>Rp {{ number_format($rent->rent_discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($rent->rent_is_extension==1)
                <tr>
                    <td colspan="3"><strong>Total Biaya Sewa</strong></td>
                    <td><strong>Rp {{ number_format($rent->rent_total_price, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3">Deposit dari Sewa Sebelumnya</td>
                    <td>Rp {{ number_format($rent->rent_last_deposit, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>Rp {{ number_format($rent->rent_total_payment, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <table id="signature">
            <tr>
                <td id="signature-left">
                    <br><br>
                    <p>Penyewa</p><br><br>
                    <strong>{{ $rent->renter->renter_name }}</strong>
                </td>
                <td id="signature-middle">
                    <br>
                    <p>Pengemudi<br>CV. Isarman Steger Bengkulu</p><br><br>
                    <strong>........................</strong>
                </td>
                <td id="signature-right">
                    <p>Hormat Kami,<br>{{ HUser::userSigned('LoggedIn User')['level'] }}<br>CV. Isarman Steger Bengkulu</p><br><br>
                    <strong>{{ HUser::userSigned('LoggedIn User')['fullname'] }}</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
