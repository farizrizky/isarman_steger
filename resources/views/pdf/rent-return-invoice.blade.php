<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon" />
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #ffffff;
            color: #333;
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

        .data {
            font-size: 12px;
        }

        .duration {
            font-size: 12px;
            text-align: right;
        }

        .extend_id {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th, td {
            padding: 8px;
            vertical-align: top;
        }

        #detail-table th, #detail-table td {
            border: 1px solid #000;
        }

        #detail-table thead {
            background-color: #e0e0e0;
            color: #000;
        }

        .highlight-total {
            background-color: #dcdcdc;
            font-weight: bold;
        }

        .note {
            font-size: 13px;
            text-align: center;
            margin-top: 20px;
        }

        #signature {
            width: 100%;
            margin-top: 20px;
            font-size: 13px;
        }

        #signature-left, #signature-right {
            width: 50%;
            text-align: center;
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
            <img src="/assets/img/Kop.png" width="600" />
            <hr />
            @if ($rent->rentReturn->rent_return_receipt_status == 'Klaim Ganti Rugi')
                <h2 class="title">INVOICE KLAIM GANTI RUGI</h2>
            @elseif ($rent->rentReturn->rent_return_receipt_status == 'Pengembalian Deposit')
                <h2 class="title">INVOICE PENGEMBALIAN DEPOSIT</h2>
            @else
                <h2 class="title">INVOICE PENGEMBALIAN SEWA</h2>
            @endif
            <h3 class="number">No. #{{ HID::genId($rent->rent_id) }}</h3>
            <h3 class="return_date">Tanggal Pengembalian: {{ HDate::dateFormat($rent->rentReturn->rent_return_date) }}</h3>
        </header>

        <table style="margin-top: 10px;">
            <tr>
                <td class="data" style="width: 50%;">
                    <strong>Penyewa:</strong><br />
                    {{ $rent->renter->renter_name }}<br />
                    Telp: <span style="color:red">{{ $rent->renter->renter_phone }}</span><br />
                    {{ $rent->rent_project_name }}<br />
                    <i>{{ $rent->rent_project_address }}</i>
                </td>
                <td style="width: 50%;"class="duration">
                    <div class="duration">
                        <strong>Durasi Sewa:</strong><br />
                        @if($rent->rent_duration == '2 Minggu')
                            2 Minggu
                        @elseif($rent->rent_duration == 'Per Bulan')
                            {{ $rent->rent_total_duration }} Bulan
                        @endif
                        <br />
                        {{ date('d-m-Y', strtotime($rent->rent_start_date)) }} s.d {{ date('d-m-Y', strtotime($rent->rent_end_date)) }}
                    </div>
                </td>
            </tr>
        </table>

        <table id="detail-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th>Item</th>
                    <th>Disewa</th>
                    <th>Hilang</th>
                    <th>Rusak</th>
                    <th>Total Denda Item</th>
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
                    <td colspan="5"><strong>Total Denda</strong></td>
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
                <tr class="highlight-total">
                    <td colspan="5">Total {{ $rent->rentReturn->rent_return_receipt_status }}</td>
                    <td>Rp {{ number_format($rent->rentReturn->rent_return_total_payment, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <table id="signature">
            <tr>
                <td id="signature-left">
                    <br />
                    <p>Penyewa</p><br /><br />
                    <strong>{{ $rent->renter->renter_name }}</strong>
                </td>
                <td id="signature-right">
                    <p>Hormat Kami,<br />{{ HUser::userSigned('LoggedIn User')['level'] }}<br />CV. Isarman Steger Bengkulu</p>
                    <br /><br />
                    <strong>{{ HUser::userSigned('LoggedIn User')['fullname'] }}</strong>
                </td>
            </tr>
        </table>

        @if($rent->rentReturn->rent_return_receipt_status == 'Pengembalian Deposit')
            <div class="note">
                <strong>Silakan hubungi CV. ISARMAN STEGER BENGKULU<br />untuk pengembalian deposit</strong>
                <p>Terima kasih atas kelancarannya, semoga sukses!</p>
            </div>
        @elseif($rent->rentReturn->rent_return_receipt_status == 'Klaim Ganti Rugi')
            <div class="note">
                <p><i>Harap melakukan pembayaran ke:</i><br />
                    <strong>Rek. BCA: 6557006763<br />a.n Isarman</strong></p>
                <p>Terima kasih atas kelancarannya, semoga sukses!</p>
            </div>
        @endif
    </div>
</body>
</html>
