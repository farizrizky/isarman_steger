<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Besar Penyewaan</title>
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
        }
        #rent-book {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        #rent-book th, #rent-book td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        #rent-book th {
            vertical-align: middle;
            background-color: #cccccc
        }

        #note {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        #note td {
            padding: 0px;
            vertical-align: top;
            border: none;
        }

        #status {
            width: 100%;
            border-collapse: collapse;
        }
        #status th, #status td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .status-lunas {
            background-color: #d4edda;
        }
        .status-belum-lunas {
            background-color: #f8d7da;
        }
        .status-berjalan {
            background-color: #cce5ff;
        }
        h2, p {
            text-align: center;
            margin: 0;
        }
        ol, ul {
            margin: 0;
            padding-left: 15px;
        }
        .text-small {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h2>BUKU BESAR SEWA<br>CV. ISARMAN STEGER BENGKULU</h2>
    <p>Periode: {{ HDate::dateFormat($rent_start_date) }} s.d {{  HDate::dateFormat($rent_end_date) }}</p>

    <table id="rent-book">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nomor Penyewaan</th>
                <th rowspan="2">Penyewa</th>
                <th colspan="2">Daftar Item</th>
                <th rowspan="2">Waktu Sewa</th>
                <th rowspan="2">Proyek</th>
                <th rowspan="2">Keterangan Akhir</th>
            </tr>
            <tr>
                <th>Nama</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($rent as $r)
                @php
                    $statusClass = 'status-berjalan';
                    if (!is_null($r->rentReturn)) {
                        if ($r->rentReturn->rent_return_status == 'Selesai' || $r->rentReturn->rent_return_status == 'Lanjut') {
                            if ($r->rent_status_payment == 'Belum Bayar' || $r->rentReturn->rent_return_payment_status == 'Belum Bayar') {
                                $statusClass = 'status-belum-lunas';
                            } else {
                                $statusClass = 'status-lunas';
                            }
                        }
                    }

                    $item = $r->rentItem->groupBy('item_id')->map(function($item) {
                        return [
                            'item_name' => $item[0]->item->item_name,
                            'item_unit' => $item[0]->item->item_unit,
                            'item_quantity' => $item->sum('rent_item_quantity'),
                        ];
                    })->sortBy('item_name')->values();
                @endphp
                <tr>
                    <td rowspan="{{ sizeof($item) }}">{{ $no++ }}</td>
                    <td rowspan="{{ sizeof($item) }}">{{ HID::genNumberRent($r->rent_id) }}</td>
                    <td rowspan="{{ sizeof($item) }}">
                        {{ $r->renter->renter_name }}<br>
                        Telp. Penyewa {{ $r->renter->renter_phone }}
                    </td>
                    <td> {{ $item[0]['item_name'] }} </td>
                    <td>{{ $item[0]['item_quantity'] }} {{ $item[0]['item_unit'] }}</td>
                    <td rowspan="{{ sizeof($item) }}">
                        @if($r->rent_duration == '2 Minggu')
                            2 Minggu
                        @elseif($r->rent_duration == 'Per Bulan')
                            {{ $r->rent_total_duration }} Bulan
                        @endif
                        <br>
                        {{ HDate::dateFormat($r->rent_start_date) }} s.d {{ HDate::dateFormat($r->rent_end_date) }}
                    </td>
                    <td rowspan="{{ sizeof($item) }}">
                        <strong>{{ $r->rent_project_name }}</strong><br>
                        <i>{{ $r->rent_project_address }}</i><br>
                        Telp. Petugas Proyek {{ $r->rent_project_phone }}
                    </td>
                    <td rowspan="{{ sizeof($item) }}"  class="{{ $statusClass }}">
                        @if($r->rent_status == 'Selesai')
                            <strong>Status Penyewaan : Selesai</strong><br>
                            @if($r->rentReturn->rent_return_status == 'Selesai')
                                <strong>Status Sewa Lanjut : Tidak Lanjut</strong><br>
                            @else
                                    <strong>Status Sewa Lanjut : Lanjut</strong>
                                @php $rentExtend = HData::getRentExtend($r->rent_id); @endphp
                                @if ($rentExtend['rent_status'] == 'Draft')
                                    Draft No. # <a href="/sewa/draft/detail/{{ $rentExtend['rent_id'] }}">{{ HID::genId($rentExtend['rent_id']) }}</a>
                                @else
                                    Sewa No. <a href="/sewa/penyewaan/detail/{{ $rentExtend['rent_id'] }}">{{ HID::genNumberRent($rentExtend['rent_id']) }}</a>
                                @endif
                                <br>
                            @endif
                            @if($r->rentReturn->rent_return_is_complete == 1)
                                <strong>Status Barang Kembali : Kembali Lengkap</strong><br>
                            @else
                                <strong>Status Barang Kembali : Kembali Tidak Lengkap</strong><br>
                            @endif
                            <strong>Status Pembayaran : </strong><br>
                            <ul>
                                <li>
                                    Pembayaran Sewa : {{ $r->rent_status_payment }}    
                                </li>
                                <li>
                                    Pembayaran Pengembalian Sewa : {{ $r->rentReturn->rent_return_payment_status }} ({{ $r->rentReturn->rent_return_receipt_status }})
                                </li>
                            </ul>
                        @elseif($r->rent_status == 'Berjalan')
                            Sewa Berjalan<br>
                            <ul>
                                <li>
                                    Pembayaran Sewa : {{ $r->rent_status_payment }}    
                                </li>
                            </ul>
                        @endif
                    </td>
                </tr>
                @foreach ($item->slice(1) as $i)
                    <tr>
                        <td>{{ $i['item_name'] }}</td>
                        <td>{{ $i['item_quantity'] }} {{ $i['item_unit'] }}</td>
                    </tr>
                    @endforeach
            @endforeach
        </tbody>
    </table>
    <table id="note">
        <tr>
            <td>
                <a style="margin-top:20px;"><strong>Keterangan Filter Data :</strong></a><br>
                <small><i>Data diatas merupakan data penyewaan dengan status sebagai berikut :</i></small>
                <table id="status">
                    <tr>
                        <td>Status Penyewaan</td>
                        <td>: 
                            @if(sizeof($rent_status) > 0)
                                {{ implode(', ', $rent_status) }}
                            @else
                                {{ $rent_status[0]}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran Sewa</td>
                        <td>: 
                            @if(sizeof($rent_status_payment) > 0)
                                {{ implode(', ', $rent_status_payment) }}
                            @else
                                {{ $rent_status_payment[0]}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran Pengembalian</td>
                        <td>: 
                            @if(sizeof($rent_return_payment_status) > 0)
                                {{ implode(', ', $rent_return_payment_status) }}
                            @else
                                {{ $rent_return_payment_status[0]}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Kwitansi Pengembalian Sewa</td>
                        <td>: 
                            @if(sizeof($rent_return_receipt_status) > 0)
                                {{ implode(', ', $rent_return_receipt_status) }}
                            @else
                                {{ $rent_return_receipt_status[0]}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Pengembalian Barang</td>
                        <td>: 
                            @if(sizeof($rent_return_is_complete) > 0)
                                Kembali Lengkap, Kembali Tidak Lengkap
                            @else
                                @if($rent_return_is_complete[0] == '1')
                                    Kembali Lengkap
                                @else
                                    Kembali Tidak Lengkap
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Sewa Lanjut</td>
                        <td>: 
                            @if(sizeof($rent_return_status) > 0)
                                Lanjut, Tidak Lanjut
                            @else
                                @if($rent_return_status[0] == 'Lanjut')
                                    Lanjut
                                @else
                                    Tidak Lanjut
                                @endif
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding-left: 20px;">
                <a style="margin-top:20px;"><strong>Keterangan Warna :</strong></a><br>
                <ul class="text-small" style="margin-top:10px;">
                    <li><span style="background-color: #d4edda;">&nbsp;&nbsp;&nbsp;</span> Lunas: Semua pembayaran telah lunas</li>
                    <li><span style="background-color: #f8d7da;">&nbsp;&nbsp;&nbsp;</span> Belum Lunas: Ada pembayaran yang belum lunas</li>
                    <li><span style="background-color: #cce5ff;">&nbsp;&nbsp;&nbsp;</span> Berjalan: Sewa masih aktif</li>
                </ul>
            </td>
        </tr>
    </table>
    


    <br>
    

</body>
</html>
