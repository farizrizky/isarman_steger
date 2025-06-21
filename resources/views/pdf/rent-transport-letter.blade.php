<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Barang</title>
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

        #letter_number {
            font-size: 12px;
            margin-top: 0px;
            text-align: right;
        }

        #data {
            margin-bottom: 0px;
        }

        .extend_id {
            text-align: right;
            font-size: 12px;
            margin-bottom: 20px;
        }

        #data td {
            padding: 0px;
            text-align: left;
            border: none;
            vertical-align: top;
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
    <h3 class="title">SURAT JALAN BARANG</h3>
    <h3 class="number">Nomor : {{ HID::genNumberRent($rent->rent_id) }}</h3>
</div>

<div id="letter_number">
    Bengkulu, {{ date('d', strtotime($rent->rent_approved_at)) }} {{ HDate::monthName(date('m', strtotime($rent->rent_approved_at))) }} {{ date('Y', strtotime($rent->rent_approved_at)) }}
</div>

<div class="description">
    <table id="data">
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td>Pengantaran dan Serah Terima Barang Sewa<br>dari CV. Isarman Steger Bengkulu</td>
        </tr>
        <tr>
            <td>Nomor Plat Kendaraan</td>
            <td>:</td>
            <td></td>
        </tr>
    </table>
</div>
<div class="section">
    
 
@php
    $item = $rent->rentItem->groupBy('item_id');
    $deposit = $rent->rent_deposit ? 1 : 0;
    $discount = $rent->rent_discount ? 1 : 0;
    $item = $item->map(function($item) use ($rent) {
        return [
            'item_id' => HID::GenID('item', $item[0]->item_id),
            'item_name' => $item[0]->item->item_name,
            'item_unit' => $item[0]->item->item_unit,
            'rent_item_quantity' => $item->sum('rent_item_quantity'),
        ];
    });
    $item = $item->sortBy('item_id')->values()->all();
@endphp
<div class="section">
    <p>Dengan Hormat, <br>Mohon di terima barang sewa sebagai berikut:</p>
    <table id="item_list">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah Barang</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody>
            @php $note = ""; @endphp
            @foreach ($item as $i => $it)
            
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="text-align:left;">{{ $it['item_name'] }}</td>
                    <td>{{ number_format($it['rent_item_quantity'], 0, ',', '.') }} {{ $it['item_unit'] }}</td>
                    @if($note == "")
                    <td rowspan="{{ count($item) }}"></td>
                    @endif     
                    @php $note = "created"; @endphp
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <p style="text-align:justify;">
        Dengan ini barang yang diserahkan sudah sesuai dengan pesanan yang diminta oleh penyewa dan barang yang keluar dari Gudang CV. Isarman Steger Bengkulu dalam keadaan baik.
    </p>
</div>

<table id="signature">
      <tr>
        <td id="signature-left">
          <p><br>Penyewa</p>
          <br><br>
          <p><strong>{{ $rent->renter->renter_name }}</strong></p>
        </td>
        <td id="signature-middle">
          <p>Pengemudi<br>CV. Isarman Steger Bengkulu</p>
          <br><br>
          <p>............................</p>
        </td>
        <td id="signature-right">
          <p>{{ HUser::userSigned('LoggedIn User')['level'] }}<br>CV. Isarman Steger Bengkulu</p>
          <br><br>
          <p><strong>{{ HUser::userSigned('LoggedIn User')['fullname'] }}</strong></p>  
        </td>
      </tr>
</table>
</body>
</html>
