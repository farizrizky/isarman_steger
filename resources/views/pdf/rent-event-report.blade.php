<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Sewa Barang</title>
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header, .footer {
            text-align: center;
            font-weight: bold;
        }
        .title {
            font-size: 16px;
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .number{
            font-size: 13px;
            margin-top: 0px;
        }
        .section {
            margin-bottom: 10px;
        }

        #data {
            margin-bottom: 0px;
        }

        .extend_id {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
        }

        #data td {
            padding: 0px;
            text-align: left;
            border: none;
        }

        #item_list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        #item_list th, td {
            padding: 4px;
            text-align: center;
            border: 1px solid black;
        }

        #item_list th {
            background-color: #cccccc;
        }
        #signature {
            width: 100%;
            margin-top: 10px;
            font-size: 13px;
            border:none;
        }

        #signature td {
            border:none;
        }

        #signature-left, #signature-middle, #signature-right {
            width: 33%;
            text-align: center;
        }
        .no-border td {
            border: none;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="header">
    @if($rent->rent_is_extension==1)
        <div class="extend_id">
            <small>Sewa Lanjutan No. {{ HID::genNumberRent($rent_extend->rent_id) }}</small>
        </div>
    @endif
    <img src="/assets/img/Kop.png" width="600">
    <hr>
    <h3 class="title">BERITA ACARA SEWA BARANG</h3>
    <h3 class="number">Nomor : {{ HID::genNumberRent($rent->rent_id) }}</h3>
</div>

<div class="section">
    <p>Pada hari ini, <b>{{HDate::dayName(now())}}</b> Tanggal <b>{{ date('d')}}</b> Bulan <b>{{HDate::monthName(now())}}</b> Tahun <b>{{ date('Y') }}</b>  dan masing masing yang bertanda tangan di bawah ini :</p>
    <table id="data">
        <tr>
            <td>1.</td>
            <td>Nama</td>
            <td>: {{ $rent->renter->renter_name }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td>: {{ $rent->renter->renter_job }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat</td>
            <td>: {{ $rent->renter->renter_address }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Adalah Pihak yang melakukan sewa disebut <strong>PIHAK PERTAMA</strong></td>
        </tr>
        <tr></tr>
        <tr>
            <td>2.</td>
            <td>Nama</td>
            <td>: {{ $rent->renter->renter_name }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td>: {{ $rent->renter->renter_job }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat</td>
            <td>: {{ $rent->renter->renter_address }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Adalah Pihak yang melakukan sewa disebut <strong>PIHAK KEDUA</strong></td>
        </tr>
    </table>
</div>
<div class="section">
    <p>Dengan ini para pihak sepakat mengadakan perjanjian sewa menyewa atas peralatan sebagai berikut:</p>
    <table id="item_list">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Item / Set</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Waktu Sewa</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $rent_duration_text = $rent->rent_duration == '2 Minggu' ? '2 Minggu' : $rent->rent_total_duration . ' Bulan';
                $deposit = $rent->rent_deposit > 0 ? 1 : 0;
                $discount = $rent->rent_discount > 0 ? 1 : 0;
                $rowAdditional = $rent->rent_is_extension ? 4 : 2;
                $item = $rent->rentItem->where('rent_set_id', null);
                $rowspan = $rent->rentSet->count() + $item->count() + $rowAdditional + $deposit + $discount;
                $i = 1;
                $hasDuration = false;
            @endphp

            @foreach ($rent->rentSet as $rs)
            <tr>
                <td>{{ $i++ }}</td>
                <td style="text-align:left;">{{ $rs->set->set_name }}</td>
                <td>{{ number_format($rs->rent_set_quantity, 0, ',', '.') }} Set</td>
                <td>Rp {{ number_format($rs->rent_set_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($rs->rent_set_total_price, 0, ',', '.') }}</td>
                @if (!$hasDuration)
                    <td rowspan="{{ $rowspan }}">{{ $rent_duration_text }}</td>
                    @php $hasDuration = true; @endphp
                @endif
            </tr>
            @endforeach

            @foreach ($item as $it)
            <tr>
                <td>{{ $i++ }}</td>
                <td style="text-align:left;">{{ $it->item->item_name }}</td>
                <td>{{ number_format($it->rent_item_quantity, 0, ',', '.') }} {{ $it->item->item_unit }}</td>
                <td>Rp {{ number_format($it->rent_item_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($it->rent_item_total_price, 0, ',', '.') }}</td>
                @if (!$hasDuration)
                    <td rowspan="{{ $rowspan }}">{{ $rent_duration_text }}</td>
                    @php $hasDuration = true; @endphp
                @endif
            </tr>
            @endforeach

            @if (!$hasDuration)
            <tr>
                <td>{{ $i++ }}</td>
                <td colspan="4">-</td>
                <td rowspan="{{ $rowspan }}">{{ $rent_duration_text }}</td>
            </tr>
            @endif

            <tr>
                <td>{{ $i++ }}</td>
                <td colspan="3" style="text-align:left;">Biaya Transport</td>
                <td>Rp {{ number_format($rent->rent_transport_price, 0, ',', '.') }}</td>
            </tr>

            @if ($deposit == 1)
            <tr>
                <td>{{ $i++ }}</td>
                <td colspan="3" style="text-align:left;">Deposit</td>
                <td>Rp {{ number_format($rent->rent_deposit, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if ($discount == 1)
            <tr>
                <td>{{ $i++ }}</td>
                <td colspan="3" style="text-align:left;">Diskon</td>
                <td>Rp {{ number_format($rent->rent_discount, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if ($rent->rent_is_extension == 1)
            <tr>
                <td>{{ $i++ }}</td>
                <td colspan="3"><strong>Total Biaya Sewa</strong></td>
                <td><strong>Rp {{ number_format($rent->rent_total_price, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>{{ $i++ }}</td>
                <td colspan="3">Deposit dari Sewa Sebelumnya</td>
                <td>Rp {{ number_format($rent->rent_last_deposit, 0, ',', '.') }}</td>
            </tr>
            @endif

            <tr>
                <td colspan="4" style="text-align:left;"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($rent->rent_total_price, 0, ',', '.') }}</strong></td>  
            </tr>
        </tbody>
    </table>

</div>

<div class="section">
    <p style="text-align:justify;">
        <strong>PIHAK PERTAMA</strong> dan <strong>PIHAK KEDUA</strong> sepakat untuk melakukan perjanjian sewa menyewa peralatan tersebut di atas untuk mendukung kenyamanan pelaksanaan pekerjaan lapangan. Kerusakan atau kehilangan barang menjadi tanggung jawab penyewa sesuai ketentuan.
    </p>
</div>

<table id="signature">
      <tr>
        <td id="signature-left">
          <p>Pihak Pertama</p>
          <br><br>
          <p><strong>{{ $rent->renter->renter_name }}</strong></p>
        </td>
      
        <td id="signature-right">
          <p>Pihak Kedua</p>
          <br><br>
          <p><strong>{{ HUser::userSigned('Direktur')['fullname'] }}</strong></p>  
        </td>
      </tr>
</table>
</body>
</html>
