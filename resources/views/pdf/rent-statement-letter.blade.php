<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan</title>
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px;
            font-size: 12pt;
        }
        h2 {
            margin-bottom: 0px;
            text-align: center;
            text-decoration: underline;
            font-size: 14px;
        }
        h5 {
            margin-top: 0px;
            text-align: center;
            font-size: 12px;
        }
        .content {
            text-align: justify;
            font-size: 12px;
            line-height: 1.6;
        }
        .indent {
            text-indent: 40px;
        }

        .extend_id {
            text-align: right;
            font-size: 12px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 12px;
            
        }
        #signature {
            width: 100%;
            margin-top: 30px;
            font-size: 12px;
        }
    
        #signature td {
            vertical-align: top;
        }
        
        #signature-left, #signature-middle, #signature-right {
            width: 33%;
            text-align: center;
        }

        .materai {
            margin: 33px;
            padding-right: 100px;
            font-size: 7px;
        }
    </style>
</head>
<body>
    @if($rent->rent_is_extension==1)
        <div class="extend_id">
            <small>Sewa Lanjutan No. {{ HID::genNumberRent($rent_extend->rent_id) }}</small>
        </div>
    @endif
    <h2>SURAT PERNYATAAN</h2>
    <h5>Nomor {{ HID::genNumberRent($rent->rent_id) }}</h5>
    <p class="content">Bengkulu, Hari <b>{{HDate::dayName(now())}}</b> Tanggal <b>{{ date('d')}}</b> <b>{{HDate::monthName(now())}}</b> Tahun <b>{{ date('Y') }}</b>. Yang bertanda tangan di bawah ini, selaku Penyewa Scaffolding dari <strong>CV. ISARMAN STEGER BENGKULU</strong>, menerangkan:</p>

    <table>
        <tr><td>Nama</td><td>: {{ $rent->renter->renter_name }}</td></tr>
        <tr><td>NIK</td><td>: {{ $rent->renter->renter_identity }}</td></tr>
        <tr><td>Hp/Wa</td><td>: {{ $rent->renter->renter_phone }}</td></tr>
        <tr><td>Pekerjaan</td><td>: {{ $rent->renter->renter_job }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $rent->renter->renter_address }}</td></tr>
    </table>

    <div class="content">
        <p>Dengan ini menyatakan bahwa saya bersedia sebagai berikut:</p>
        <ol>
            <li>Melengkapi administrasi pengajuan penyewaan berupa fotokopi KTP/SIM/Tanda Pengenal.</li>
            <li>Melakukan pembayaran di muka uang sewa sebelum barang dikirim/diantar ke lokasi penyewa.</li>
            <li>Memeriksa dan meneliti barang yang diterima sesuai pesanan (berkas terlampir).</li>
            <li>Jika melakukan perpanjangan kontrak atau mengakhiri penyewaan (off), maka wajib memberitahukan kepada penyewa agar jelas proses selanjutnya ataupun kelancaran pengembalian.</li>
            <li>Melakukan pemberitahuan kepada pihak CV. ISARMAN STEGER jika kontrak/sewa akan selesai.</li>
            <li>Jika memperpanjang kontrak/sewa, melakukan pembayaran kembali kepada CV. ISARMAN STEGER.</li>
            <li>Dilarang keras melakukan pindah tangan di luar perjanjian kecuali ada persetujuan dari penyedia.</li>
            <li>Penyewa wajib mengganti kerugian pihak pertama bila mana barang tersebut hilang atau rusak berat (hancur).</li>
        </ol>

        <p class="indent">Demikianlah pernyataan ini saya buat dengan sungguh-sungguh dan dapat dipergunakan sebagaimana mestinya serta dapat dipertanggungjawabkan di depan hukum yang berlaku.</p>
    </div>

    <table id="signature">
      <tr>
        <td id="signature-left">
            <i>Saksi-saksi</i>
            <br><br>
            <p>........ (..................)</p>
            <p>........ (..................)</p>
            <p>........ (..................)</p>
        </td>
        <td id="signature-middle">
             <p>CV. ISARMAN STEGER</p><br><br><br><br>
            <p><strong>{{ HUser::userSigned('Direktur')['fullname'] }}</strong><br>{{ HUser::userSigned('Direktur')['level'] }}</p>
        </td>
        <td id="signature-right">
            Penanggung Jawab<br>    
            ( Pengguna Sewa )
            <div class="materai">Materai<br>Rp.10.000</div>
            <p><strong>{{ $rent->renter->renter_name }}</strong></p>
        </td>
      </tr>
    </table>
</body>
</html>
