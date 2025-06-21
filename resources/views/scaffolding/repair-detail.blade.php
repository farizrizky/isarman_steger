@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Perbaikan</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID Perbaikan</th>
                            <td>: {{ HID::genId($repair->repair_id) }}</td>
                        <tr>
                            <th>Tanggal Perbaikan</th>
                            <td>: {{ HDate::dateFormat($repair->repair_date) }}</td>
                        </tr>
                        <tr>
                            <th>Pelaksana Perbaikan</th>
                            <td>: <strong>{{ $repair->repair_provider }}</strong>
                            (<a href="/wa/chat/{{ $repair->repair_provider_phone }}" target="_blank">{{ $repair->repair_provider_phone }}</a>)
                        </tr>
                        <tr>
                            <th>Biaya Perbaikan</th>
                            <td>: Rp <span class="currency">{{ $repair->repair_price }}</span></td>
                        </tr>
                        @if($repair->repair_status != "Draft")
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>: 
                                @if($repair->repair_payment_status == "Belum Bayar")
                                    <span class="badge badge-danger">Belum Bayar</span>
                                @elseif($repair->repair_payment_status == "Lunas")
                                    <span class="badge badge-success">Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @if($repair->repair_payment_status == "Belum Bayar")
                        <tr>
                            <th>Upload Kwitansi Pembayaran</th>
                            <td>
                                <form method="POST" action="/scaffolding/perbaikan/upload-kwitansi/{{ $repair->repair_id }}" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group form-inline row">
                                        <div class="col-md-6 col-sm-12 p-0 me-2">
                                            <div class="input-group mb-3">
                                                <input type="file" class="form-control input-full"  name="repair_receipt_file_file" id="repair_receipt_file_file" required>
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
                        @else
                        <tr>
                            <th>Kwitansi Pembayaran</th>
                            <td>: <a href="{{ asset('storage/'.$repair->repair_receipt_file) }}" target="_blank">Lihat Kwitansi</a></td>
                        </tr>
                        <tr>
                            <th>Upload Ulang Kwitansi Pembayaran</th>
                            <td>
                                <form method="POST" action="/scaffolding/perbaikan/upload-kwitansi/{{ $repair->repair_id }}" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group form-inline row">
                                        <div class="col-md-6 col-sm-12 p-0 me-2">
                                            <div class="input-group mb-3">
                                                <input type="file" class="form-control input-full"  name="repair_receipt_file_file" id="repair_receipt_file_file" required>
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
                        @endif
                        <tr>
                            <th>Status Perbaikan</th>
                            <td>: 
                                @if($repair->repair_status == "Draft")
                                    <span class="badge badge-danger">Draft</span>
                                @elseif($repair->repair_status == "Dalam Perbaikan")
                                    <span class="badge badge-primary">Dalam Perbaikan</span>
                                @elseif($repair->repair_status == "Selesai")
                                    <span class="badge badge-success">Selesai</span>

                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Item yang diperbaiki</th>
                            <td></td>
                        </tr>
                    </table>
                    
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repair->repairItem as $item)
                            <tr>
                                <td>({{ HID::genId($repair->repair_id) }}){{ $item->item->item_name }}</td>
                                <td>{{ $item->repair_item_quantity }}</td>
                                <td>{{ $item->repair_item_description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="/scaffolding/perbaikan" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    @if ($repair->repair_status == 'Draft')
                        <a class="btn btn-info float-end me-2" href="/scaffolding/perbaikan/edit/{{ $repair->repair_id }}"><span class="icon-pencil"></span> Ubah</a>
                        <a class="btn btn-danger float-end me-2" onclick="confirmAlert('/scaffolding/perbaikan/hapus/{{ $repair->repair_id }}', 'Anda yakin akan menghapus data ini?')"><span class="icon-trash"></span> Hapus</a>
                        <a class="btn btn-success float-end me-2" onclick="confirmAlert('/scaffolding/perbaikan/proses/{{ $repair->repair_id }}','Anda yakin akan memproses perbaikan ini?')"><span class="icon-check"></span> Proses</a>
                    @elseif($repair->repair_status == 'Dalam Perbaikan')
                        <a class="btn btn-success float-end me-2" onclick="confirmAlert('/scaffolding/perbaikan/selesai/{{ $repair->repair_id }}','Anda yakin akan menyelesaikan perbaikan ini?')"><span class="icon-check"></span> Selesaikan</a>
                    @else
                        <i class="float-end me-2">Perbaikan Telah Selesai, {{ HDate::fullDateFormat($repair->repair_completed_at) }}</i>
                    @endif
                  </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

