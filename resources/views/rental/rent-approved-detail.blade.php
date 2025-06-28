@extends('template.dashboard')
@section('content')
@php
    
@endphp
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold">Detail Penyewaan</h3>
            <strong>Nomor : {{ HID::genNumberRent($rent->rent_id) }}</strong>
            <br>
            @if($rent->rent_is_extension==1)
            <small class="text-muted">Sewa Lanjutan No. <a href="/sewa/penyewaan/detail/{{ $rent->rent_extend_id }}">{{ HID::genNumberRent($rent->rent_id_extend) }}</a></small>
            @endif
        </div>
        <div class="ms-md-auto py-md-0 text-end">
            @if($rent->rent_status_payment == 'Belum Bayar')
            <h4 class="text-danger"><strong>Rp <span class="currency">{{ $rent->rent_total_payment }}</span> ({{ $rent->rent_status_payment }})</strong></h4>
            @else
            <h4 class="text-success"><strong>Rp <span class="currency">{{ $rent->rent_total_payment }}</span> ({{ $rent->rent_status_payment }})</strong></h4>
            @endif
            <strong>
                Sewa
                @if($rent->rent_duration == '2 Minggu')
                2 Minggu
                @elseif($rent->rent_duration == 'Per Bulan')
                {{ $rent->rent_total_duration }} Bulan
                @endif
            </strong>
          
            ({{ HDate::dateFormat($rent->rent_start_date) }} s/d {{ HDate::dateFormat($rent->rent_end_date) }})
            <br>
            @if($rent->rentReturn == null)
                @php $daysDiffFromNow = HDate::daysDiffFromNow($rent->rent_end_date, date('Y-m-d')); @endphp
                @if($daysDiffFromNow < 0)
                    <span class="badge badge-danger">Lewat {{ abs($daysDiffFromNow) }} hari</span>
                @elseif($daysDiffFromNow == 0)
                    <span class="badge badge-secondary">Berakhir Hari Ini</span>
                @elseif($daysDiffFromNow >=1 && $daysDiffFromNow <= 3)
                    <span class="badge badge-warning">Berakhir dalam {{ $daysDiffFromNow }} hari</span>
                @else
                    <span class="badge badge-success">Berakhir dalam {{ $daysDiffFromNow }} hari</span>
                @endif
            @else
                <button class="badge badge-primary">Diselesaikan pada {{ HDate::dateFormat($rent->rentReturn->rent_return_date) }}</button>
            @endif
        </div>
    </div>
    @if($rent->rent_status_payment == 'Belum Bayar')
    <div class="alert alert-danger" role="alert">
        <strong>Perhatian!</strong> Status pembayaran <strong>Belum Bayar</strong>. Silahkan upload kwitansi penyewaan untuk mengubah status pembayaran menjadi <strong>Lunas</strong>.
        @if($rent->rent_invoice_photo != null)
        <a target="_blank" href="/wa/invoice-penyewaan/{{ $rent->rent_id }}" class="btn btn-success btn-sm"><span class="fab fa-whatsapp"></span> Kirim Invoice Via WhatsApp</a>
        @endif
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills nav-fill nav-primary" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-renter-tab" data-bs-toggle="pill" href="#pills-renter" role="tab" aria-controls="pills-renter" aria-selected="true"><strong><span class="fa fa-user"></span> Penyewa</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-item-tab" data-bs-toggle="pill" href="#pills-item" role="tab" aria-controls="pills-item" aria-selected="false"><strong><span class="fas fa-dolly"></span> Scaffolding & Biaya</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-files-tab" data-bs-toggle="pill" href="#pills-files" role="tab" aria-controls="pills-files" aria-selected="false"><strong><span class="fas fa-paperclip"></span> Berkas</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-return-tab" data-bs-toggle="pill" href="#pills-return" role="tab" aria-controls="pills-return" aria-selected="false"><strong><span class="fas fa-undo"></span> Pengembalian</strong></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content mt-3" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-renter" role="tabpanel" aria-labelledby="pills-renter-tab">
                            <div class="row">
                                <div class="col-md-8">
                                     <div class="table-responsive">
                                        <table class="table table-stripped">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Nama Penyewa</strong></td>
                                                    <td>: {{ $rent->renter->renter_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>No. Identitas Penyewa</strong></td>
                                                    <td>: {{ $rent->renter->renter_identity }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Telepon Penyewa</strong></td>
                                                    <td>: <a href="/wa/chat/{{ $rent->renter->renter_phone }}" target="_blank">{{ $rent->renter->renter_phone }}</a></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alamat Penyewa</strong></td>
                                                    <td>: {{ $rent->renter->renter_address }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama Proyek</strong></td>
                                                    <td>: {{ $rent->rent_project_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alamat Proyek</strong></td>
                                                    <td>: {{ $rent->rent_project_address }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Telepon Petugas Proyek</strong></td>
                                                    <td>: <a target="_blank" href="/wa/chat/{{ $rent->rent_project_phone }}">{{ $rent->rent_project_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Saldo Deposit</strong></td>
                                                    <td>: Rp <span class="currency">{{ $rent->rentDeposit->rent_deposit_balance }}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <img src="{{ asset('storage/'.$rent->renter->renter_identity_photo) }}" class="img-fluid" alt="Identitas Penyewa">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-item" role="tabpanel" aria-labelledby="pills-item-tab">
                            <strong>Rincian Sewa Scaffolding</strong>
                            <hr>
                            <div class="table-responsive mb-5">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Nama</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan / {{ $rent->rent_duration }}</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($rent->rentSet as $rs)
                                        <tr>
                                            <td><small class="text-muted">({{ HID::genId($rs->set_id) }})</small> {{ $rs->set->set_name }}</td>  
                                            <td>{{ $rs->rent_set_quantity }} Set</td>
                                            <td>Rp <span class="currency">{{ $rs->rent_set_price }}</span></td>
                                            <td class="text-end"><strong>Rp <span class="currency">{{ $rs->rent_set_total_price }}</span></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <i>Item Dalam Set {{ $rs->set->set_name }}</i> :
                                                <ul>
                                                    @foreach($rs->rentItem as $ri)
                                                    <li>
                                                        <small class="text-muted">({{ HID::genId($ri->item_id) }})</small> {{ $ri->item->item_name }} {{ $ri->rent_item_quantity }} {{ $ri->item->item_unit }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @foreach($rent->rentItem as $ri)
                                        @if($ri->rent_set_id == null)
                                        <tr>
                                            <td><small class="text-muted">({{ HID::genId($ri->item_id) }})</small> {{ $ri->item->item_name }}</td>  
                                            <td>{{ $ri->rent_item_quantity }} {{ $ri->item->item_unit }}</td>
                                            <td>Rp <span class="currency">{{ $ri->rent_item_price }}</span></td>
                                            <td class="text-end"><strong>Rp <span class="currency">{{ $ri->rent_item_total_price }}</span></strong></td>  
                                        </tr>
                                        @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="3"><strong>Subtotal</strong></td>
                                            @php $subtotal = $rent->rent_total_price - $rent->rent_transport_price - $rent->rent_deposit + $rent->rent_discount; @endphp
                                            <td class="text-end"><strong>Rp {{ number_format($subtotal, 0, ',', '.');}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Biaya Transport</strong></td>
                                            <td colspan="1" class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_transport_price }}</span></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Deposit</strong></td>
                                            <td colspan="1" class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_deposit }}</span></strong></td> 
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Diskon</strong></td>
                                            <td colspan="1" class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_discount }}</span></strong></td>
                                        </tr>
                                         @if($rent->rent_is_extension==1)
                                            <tr>
                                                <td colspan="3"><strong>Total Biaya Sewa</strong></td>
                                                <td class="text-end"><strong>Rp {{ number_format($rent->rent_total_price, 0, ',', '.');}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">Deposit dari Sewa Sebelumnya</td>
                                                <td class="text-end">Rp {{ number_format($rent->rent_last_deposit, 0, ',', '.');}}</td>
                                            </tr>
                                        @endif
                                        <tr class="table-info">
                                            <td colspan="3"><strong>Total</strong></td>
                                            <td class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_total_payment}}</span></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <strong>Rekap Jumlah Item Disewa</strong>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-success">
                                            <th>Item</th>
                                            <th>Total Disewa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item as $i)
                                        <tr>
                                            <td><small class="text-muted">({{ HID::genId($i['item_id']) }})</small> {{ $i['item_name'] }}</td>  
                                            <td>{{ $i['item_quantity'] }} {{ $i['item_unit'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-files" role="tabpanel" aria-labelledby="pills-files-tab">
                            <form method="POST" action="/sewa/penyewaan/detail/upload-berkas/{{ $rent->rent_id }}" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Berkas Penyewaan</strong>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama Berkas</th>
                                                        <th>Upload Foto</th>
                                                        <th>Foto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            Kwitansi<hr>
                                                            <a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Kwitansi Penyewaan" data-pdf="/pdf/kwitansi-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info"><i class="fas fa-print"></i> Cetak Kwitansi</a>
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control input-full"  name="rent_receipt_file" id="rent_receipt_file">
                                                        </td>
                                                        <td>
                                                            @if($rent->rent_receipt_photo != null)
                                                            <a target="_blank" href="/file/{{ encrypt($rent->rent_receipt_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>
                                                           Surat Pernyataan<hr>
                                                            <a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Surat Pernyataan Penyewaan" data-pdf="/pdf/surat-pernyataan-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info "><i class="fas fa-print"></i> Cetak Surat Pernyataan</a>
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control input-full"  name="rent_statement_letter_file" id="rent_statement_letter_file">
                                                        </td>
                                                        <td>
                                                            @if($rent->rent_statement_letter_photo != null)
                                                            <a target="_blank" href="/file/{{ encrypt($rent->rent_statement_letter_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>
                                                            Berita Acara<hr>
                                                            <a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Berita Acara Penyewaan" data-pdf="/pdf/berita-acara-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info"><i class="fas fa-print"></i> Cetak Berita Acara</a>
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control input-full"  name="rent_event_report_file" id="rent_event_report_file">
                                                        </td>
                                                        <td>
                                                            @if($rent->rent_event_report_photo != null)
                                                            <a target="_blank" href="/file/{{ encrypt($rent->rent_event_report_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>
                                                            Surat Jalan<hr>
                                                            <a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Surat Jalan Penyewaan" data-pdf="/pdf/surat-jalan-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info"><i class="fas fa-print"></i> Cetak Surat Jalan</a>
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control input-full"  name="rent_transport_letter_file" id="rent_transport_letter_file">
                                                        </td>
                                                        <td>
                                                            @if($rent->rent_transport_letter_photo != null)
                                                            <a target="_blank" data-image="/file/{{ encrypt($rent->rent_transport_letter_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>
                                                            Invoice Penyewaan<hr>
                                                            <a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Invoice Penyewaan" data-pdf="/pdf/invoice-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info"><i class="fas fa-print"></i> Cetak Invoice Penyewaan</a>
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control input-full"  name="rent_invoice_file" id="rent_invoice_file">
                                                        </td>
                                                        <td>
                                                            @if($rent->rent_invoice_photo != null)
                                                            <a target="_blank" href="/file/{{ encrypt($rent->rent_invoice_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="submit" class="btn btn-success mt-3 float-end"><i class="fas fa-upload"></i> Upload Berkas</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-return" role="tabpanel" aria-labelledby="pills-return-tab">
                            <strong>Pengembalian</strong>
                            <hr>
                            @if($rent->rentReturn != null)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tanggal Pengembalian</strong></td>
                                                    <td>: {{ HDate::dateFormat($rent->rentReturn->rent_return_date) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Denda</strong></td>
                                                    <td>: Rp <span class="currency">{{ $rent->rentReturn->rent_return_total_fine }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dispensasi Denda</strong></td>
                                                    <td>: Rp <span class="currency">{{ $rent->rentReturn->rent_return_dispensation_fine }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Deposit</strong></td>
                                                    <td>: Rp <span class="currency">{{ $rent->rentReturn->rent_return_deposit_saldo }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Akhir Biaya Pengembalian Sewa</strong></td>
                                                    <td>: Rp <span class="currency">{{ $rent->rentReturn->rent_return_total_payment }}</span></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td><strong>Status Kwitansi</strong></td>
                                                    <td>: {{ $rent->rentReturn->rent_return_receipt_status }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status Pembayaran</strong></td>
                                                    <td>:
                                                        @if($rent->rentReturn->rent_return_payment_status == 'Belum Bayar')
                                                        <span class="badge badge-danger">Belum Bayar</span>
                                                        @elseif($rent->rentReturn->rent_return_payment_status == 'Lunas')
                                                        <span class="badge badge-success">Lunas</span>
                                                        @elseif($rent->rentReturn->rent_return_payment_status == 'Bayar Sebagian')
                                                        <span class="badge badge-warning">Bayar Sebagian</span>
                                                        @elseif($rent->rentReturn->rent_return_payment_status == 'Pending')
                                                        <span class="badge badge-secondary">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Invoice Pengembalian</strong></td>
                                                    <td><a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Invoice Pengembalian Sewa" data-pdf="/pdf/invoice-pengembalian-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info"><i class="fas fa-print"></i> Cetak Invoice Pengembalian</a></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Foto Invoice Pengembalian</strong></td>
                                                    <td>
                                                        @if($rent->rentReturn->rent_return_invoice_photo != null)
                                                        <a target="_blank" href="/file/{{ encrypt($rent->rentReturn->rent_return_invoice_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td ><strong>Upload Invoice Pengembalian</strong></td>
                                                    <td>
                                                        <form method="POST" action="/sewa/pengembalian/upload-invoice/{{ $rent->rent_id }}" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group form-inline row">
                                                                <div class="col-md-6 col-sm-12 p-0 me-2">
                                                                    <div class="input-group mb-3">
                                                                        <input type="file" class="form-control input-full"  name="rent_return_invoice_file" id="rent_return_invoice_file" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 p-0">
                                                                    <div class="input-group mb-3 d-grid gap-2">
                                                                        <button class="btn btn-success text-nowrap" type="submit"><i class="fas fa-upload"></i> Upload Invoice Pengembalian</button>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kwitansi Pengembalian</strong></td>
                                                    <td><a data-bs-toggle="modal" data-bs-target="#pdf_modal" data-title="Kwitansi Pengembalian Sewa" data-pdf="/pdf/kwitansi-pengembalian-penyewaan/{{ encrypt($rent->rent_id) }}" class="btn btn-info"><i class="fas fa-print"></i> Cetak Kwitansi</a></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Foto Kwitansi</strong></td>
                                                    <td>
                                                        @if($rent->rentReturn->rent_return_receipt_photo != null)
                                                        <a target="_blank" href="/file/{{ encrypt($rent->rentReturn->rent_return_receipt_photo) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($rent->rentReturn->rent_return_payment_status != 'Pending')
                                                <tr>
                                                    <td ><strong>Upload Kwitansi</strong></td>
                                                    <td>
                                                        <form method="POST" action="/sewa/pengembalian/upload-kwitansi/{{ $rent->rent_id }}" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group form-inline row">
                                                                <div class="col-md-6 col-sm-12 p-0 me-2">
                                                                    <div class="input-group mb-3">
                                                                        <input type="file" class="form-control input-full"  name="rent_return_receipt_file" id="rent_return_receipt_file" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 p-0">
                                                                    <div class="input-group mb-3 d-grid gap-2">
                                                                        <button class="btn btn-success text-nowrap" type="submit"><i class="fas fa-upload"></i> Upload Kwitansi</button>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($rent->rentReturn->rent_return_payment_status != 'Pending')
                                        @if($rent->rentReturn->rent_return_status == 'Selesai')
                                            <a href="/sewa/draft/input/{{ $rent->rent_id }}" class="btn btn-primary"><span class="fas fa-redo"></span> Lanjutkan Sewa</a>
                                        @endif
                                    @endif
                                    @if(!is_null($rent->rentReturn->rent_return_invoice_photo))
                                    <a target="_blank" href="/wa/invoice-penyewaan/{{ $rent->rent_id }}" class="btn btn-success"><span class="fab fa-whatsapp"></span> Kirim Invoice Via WhatsApp</a>
                                    @endif
                                </div>
                            </div>
                            @else
                            <form method="POST" action="/sewa/pengembalian/{{ $rent->rent_id }}" class="needs-validation" id="form_return" novalidate>
                                @csrf
                                <div class="form-group form-inline row">
                                    <label for="rent_return_date" class="col-md-3 m-0 col-form-label text-wrap"><b>Tanggal Pengembalian</b></label>
                                    <div class="col-md-3 p-0">
                                        <input type="date" class="form-control" name="rent_return_date" id="rent_return_date" required> 
                                        <div class="invalid-feedback">Tanggal pengembalian harus diisi</div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 p-0">
                                        <div class="form-check">
                                            <input type="hidden" id="rent_return_is_complete" name="rent_return_is_complete" value="0">
                                            <input class="form-check-input" type="checkbox" id="is_complete">
                                            <label class="form-check-label" for="flexCheckDefault">Kembali Lengkap</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-success">
                                                <th>Item Disewa</th>
                                                <th>Jumlah</th>
                                                <th>Jumlah Item Hilang</th>
                                                <th>Jumlah Item Rusak</th>
                                                <th>Total Denda Hilang</th>
                                                <th>Total Denda Rusak</th>
                                                <th>Total Denda</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($item as $i)
                                            <tr>
                                                <td><small class="text-muted">({{ HID::genId($i['item_id']) }})</small> {{ $i['item_name'] }}</td>
                                                <input type="hidden" id="item_quantity_{{ $i['item_id'] }}" value="{{ $i['item_quantity'] }}">
                                                <td>{{ $i['item_quantity'] }} {{ $i['item_unit'] }}</td>
                                                <input type="hidden" name="rent_return_item[]" id="item_{{ $i['item_id'] }}" value="{{ $i['item_id'] }}_0_0">
                                                <td>
                                                    <input type="number" class="form-control fine" onkeyup="calculateFine({{ $i['item_id'] }})" id="rent_return_item_lost_{{ $i['item_id'] }}" value="0" min="0">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control fine" onkeyup="calculateFine({{ $i['item_id'] }})" id="rent_return_item_damaged_{{ $i['item_id'] }}" value="0" min="0">
                                                </td>
                                                <td>
                                                    <input type="hidden" id="item_fine_lost_{{ $i['item_id'] }}" value="{{ $i['item_fine_lost'] }}">
                                                    Rp <span class="currency fine-price total_rent_item_lost_{{ $i['item_id'] }}" id="total_rent_item_lost_{{ $i['item_id'] }}">0</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" id="item_fine_damaged_{{ $i['item_id'] }}" value="{{ $i['item_fine_damaged'] }}">
                                                    Rp <span class="currency fine-price total_rent_item_damaged_{{ $i['item_id'] }}" id="total_rent_item_damaged_{{ $i['item_id'] }}">0</span>
                                                </td>
                                                <td>
                                                    Rp <span class="currency subtotal_fine total_rent_item_fine_{{ $i['item_id'] }}" id="total_rent_item_fine_{{ $i['item_id'] }}">0</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5"><strong>Total Denda</strong></td>
                                                <td colspan="2" class="text-end"><strong>Rp <span class="total_fine" id="total_fine">0</span></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><strong>Dispensasi Denda</strong></td>
                                                <td colspan="2" class="text-end">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control currency" id="rent_return_dispensation_fine" name="rent_return_dispensation_fine" value="0" min="0">
                                                    </div>
                                                    <span id="dispensation_invalid" style="display: none;" class="text-danger">Dispensasi denda tidak boleh melebihi total denda.</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><strong>Total Akhir Denda</strong></td>
                                                <td colspan="2" class="text-end"><strong>Rp <span class="grand_total_fine" id="grand_total_fine">0</span></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><strong>Saldo Deposit</strong></td>
                                                <td colspan="2" class="text-end"><strong>Rp <span class="currency" id="deposit_saldo">{{ $rent->rentDeposit->rent_deposit_balance }}</span></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><strong>Total Akhir Biaya Pengembalian Sewa</strong></td>
                                                <td colspan="2" class="text-end">
                                                    <strong>Rp <span class="total_payment" id="total_payment">0</span></strong><br>
                                                    <span class="badge badge-danger" id="receipt_status"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-end">
                                    <a class="btn btn-primary" id="return_action"><span class="fas fa-undo"></span> Proses Pengembalian</a>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-left align-items-md-center flex-column flex-md-row pt-4 pb-4">
                    <div>
                        
                    </div>
                    <div class="ms-md-auto p-2 py-md-0">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="image_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img class="img-fluid" id="image_show">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pdf_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdf_show" width="100%" height="600px"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let depositSaldo = parseInt("{{ $rent->rentDeposit->rent_deposit_balance }}");
    $(document).ready(function() {
        $('#rent_return_date').val('{{ date('Y-m-d') }}');
        $('#rent_return_dispensation_fine').attr('readOnly', true);
        calculateTotalPayment();
    });

    $('#return_action').click(function(e){
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin memproses pengembalian sewa ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Proses Pengembalian!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_return').submit();
            }
        });
    });

    $('#image_modal').on('show.bs.modal', function (event) {
        var data = $(event.relatedTarget);
        var image = data.data('image');

        $('#image_show').attr("src", image);
    });

    $('#pdf_modal').on('show.bs.modal', function (event) {
        var data = $(event.relatedTarget);
        var title = data.data('title');
        var pdf = data.data('pdf');
        var modal = $(this);

        $('.modal-title').html(title);
        $('#pdf_show').attr("src", pdf);
    });

    $('#is_complete').change(function() {
        if($(this).is(':checked')) {
            $('.fine').val(0);
            $('.fine').attr('disabled', true);
            $('.fine-price').text(0);
            $('.subtotal_fine').text(0);
            $('#total_fine').text(0);
            $('#grand_total_fine').text(0);
            $('#rent_return_dispensation_fine').val(0);
            $('#rent_return_dispensation_fine').attr('readOnly', true);
            $('#rent_return_is_complete').val(1);
        } else {
            $('.fine').attr('disabled', false);
            $('#rent_return_dispensation_fine').attr('readOnly', false);
            $('#rent_return_is_complete').val(0);
        }
        calculateTotalPayment();
    });

    $('#rent_return_dispensation_fine').on('keyup', function() {
        var dispensationFine = parseInt($(this).val().replace(/[^0-9]/g, ''));
        if(dispensationFine > parseInt($('#total_fine').text().replace(/[^0-9]/g, ''))) {
            $('#dispensation_invalid').css('display', '');
            $(this).val(0);
           
        }
        calculateTotalFine();
    });

    function calculateFine(itemId) {
        var itemQuantity = parseInt($('#item_quantity_' + itemId).val());
        console.log(itemQuantity);

        if($('#rent_return_item_lost_' + itemId).val() == '') {
            $('#rent_return_item_lost_' + itemId).val(0);
        }

        if($('#rent_return_item_damaged_' + itemId).val() == '') {
            $('#rent_return_item_damaged_' + itemId).val(0);
        }

        if(parseInt($('#rent_return_item_lost_' + itemId).val()) > itemQuantity) {
            $('#rent_return_item_lost_' + itemId).val(itemQuantity);
        }

        if(parseInt($('#rent_return_item_damaged_' + itemId).val()) > itemQuantity) {
            $('#rent_return_item_damaged_' + itemId).val(itemQuantity);
        }

        if(parseInt($('#rent_return_item_lost_' + itemId).val()) + parseInt($('#rent_return_item_damaged_' + itemId).val()) > itemQuantity) {
            alert('Jumlah item hilang dan rusak tidak boleh lebih dari jumlah item disewa');
            $('#rent_return_item_lost_' + itemId).val(0);
            $('#rent_return_item_damaged_' + itemId).val(0);
        }

        var itemLost = parseInt($('#rent_return_item_lost_' + itemId).val());
        var itemDamaged = parseInt($('#rent_return_item_damaged_' + itemId).val());
        var itemFineLost = parseInt($('#item_fine_lost_' + itemId).val());
        var itemFineDamaged = parseInt($('#item_fine_damaged_' + itemId).val());

        var totalFineLost = itemLost * itemFineLost;
        var totalFineDamaged = itemDamaged * itemFineDamaged;
        var totalFine = totalFineLost + totalFineDamaged;

        $('#total_rent_item_lost_' + itemId).html(totalFineLost);
        $('#total_rent_item_damaged_' + itemId).html(totalFineDamaged);
        $('#total_rent_item_fine_' + itemId).html(totalFine);
        $('#item_' + itemId).val(itemId + '_' + itemLost + '_' + itemDamaged);

        formatingNumber('total_rent_item_lost_' + itemId);
        formatingNumber('total_rent_item_damaged_' + itemId);
        formatingNumber('total_rent_item_fine_' + itemId);
        calculateTotalFine();
    }

    function calculateTotalFine() {
        var totalFine = 0;
        $('.subtotal_fine').each(function() {
            totalFine += parseInt($(this).text().replace(/[^0-9]/g, ''));
        });
        if(totalFine == 0) {
            $('#rent_return_dispensation_fine').val(0);
            $('#rent_return_dispensation_fine').attr('readOnly', true);
        }else{
            $('#rent_return_dispensation_fine').attr('readOnly', false);
        }
        $('#total_fine').html(totalFine);
        $('#grand_total_fine').html(totalFine - parseInt($('#rent_return_dispensation_fine').val().replace(/[^0-9]/g, '')));
        formatingNumber('total_fine');
        formatingNumber('grand_total_fine');
        calculateTotalPayment();
    }

    function calculateTotalPayment(){
        var totalFine = parseInt($('#grand_total_fine').text().replace(/[^0-9]/g, ''));
        var totalPayment = depositSaldo - totalFine;
        $('#receipt_status').removeClass();
        
        if(totalPayment < 0) {
           $('#receipt_status').html('Klaim Ganti Rugi');
           $('#receipt_status').addClass('badge badge-danger');
        } else {
            $('#receipt_status').html('Pengembalian Deposit');
            $('#receipt_status').addClass('badge badge-success');
        }

        if(depositSaldo == 0 && totalFine == 0) {
            $('#receipt_status').html('Nihil');
            $('#receipt_status').addClass('badge badge-success');
        }

        $('#total_payment').html(Math.abs(totalPayment));
        formatingNumber('total_payment');
    }
</script>    
@endsection

