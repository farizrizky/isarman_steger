@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Perbaikan Item</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/scaffolding/perbaikan/input" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Perbaikan</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-bordered table-striped">
                            <thead>
                                <tr class="table-primary">
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Pelaksana Perbaikan</th>
                                    <th>Status Perbaikan</th>
                                    <th>Biaya Perbaikan</th>
                                    <th>Status Pembayaran Perbaikan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repair as $r)
                                <tr>
                                    <td>{{  HID::genId($r->repair_id) }}</td>
                                    <td>{{ HDate::dateFormat($r->repair_date) }}</td>
                                    <td>
                                        <strong>{{ $r->repair_provider }}</strong><br>
                                        <a href="/wa/chat/{{ $r->repair_provider_phone }}" target="_blank">{{ $r->repair_provider_phone }}</a>
                                    </td>
                                    <td>
                                        @if($r->repair_status == "Draft")
                                            <span class="badge badge-danger">Draft</span>
                                        @elseif($r->repair_status == "Dalam Perbaikan")
                                            <span class="badge badge-primary">Dalam Perbaikan</span>
                                        @elseif($r->repair_status == "Selesai")
                                            <span class="badge badge-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="currency">{{ $r->repair_price }}</span>
                                    </td>
                                    <td>
                                        @if($r->repair_payment_status == "Belum Bayar")
                                            <span class="badge badge-danger">Belum Bayar</span>
                                        @elseif($r->repair_payment_status == "Lunas")
                                            <span class="badge badge-success">Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm m-1" href="/scaffolding/perbaikan/detail/{{ $r->repair_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"><span class="icon-eye"></span></a>
                                        @if($r->repair_status == "Draft")  
                                        <a class="btn btn-success btn-sm m-1" href="/scaffolding/perbaikan/edit/{{ $r->repair_id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" onclick="confirmAlert('/scaffolding/perbaikan/hapus/{{ $r->repair_id }}', 'Anda yakin akan menghapus data ini?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><span class="icon-trash"></span></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $("#basic-datatables").DataTable();
    });

</script>
@endsection
