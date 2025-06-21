@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Pembelian Scaffolding</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/scaffolding/pembelian/input" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pembelian</a>
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
                                    <th>Tanggal Pembelian</th>
                                    <th>Total Pembelian</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Tanggal Diterima</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase as $p)
                                <tr>
                                    <td>{{ HID::genId($p->purchase_id) }}</td>
                                    <td>{{ $p->purchase_date }}</td>
                                    <td>Rp <span class="currency">{{ $p->purchase_total }}</span></td>
                                    <td>{{ $p->purchase_supplier }}</td>
                                    <td>
                                        @if($p->purchase_status == "Diterima")
                                            <button class="btn btn-info btn-sm">{{ $p->purchase_status }}</button>
                                        @else
                                            <span class="badge bg-danger">{{ $p->purchase_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->purchase_accepted_date }}</td>
                                    <td>
                                        <a class="btn btn-success btn-sm m-1" href="/scaffolding/pembelian/edit/{{ $p->purchase_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Pembelian & Penerimaan"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" onclick="confirmAlert('/scaffolding/pembelian/hapus/{{ $p->purchase_id }}', 'Anda yakin akan menghapus data ini?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><span class="icon-trash"></span></a>
                                        <a class="btn btn-warning btn-sm m-1" href="/scaffolding/pembelian/detail/{{ $p->purchase_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail"><span class="icon-eye"></span></a>
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
        $("#basic-datatables").DataTable({});
    });

    
</script>
@endsection
