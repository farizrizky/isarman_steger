@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Pengeluran</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/keuangan/pengeluaran/draft/input" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengeluaran</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expense as $e)
                                <tr>
                                    <td>{{ HID::genId($e->expense_id) }}</td>
                                    <td>{{ $e->expense_category }}</td>
                                    <td>{{ $e->expense_description }}</td>
                                    <td class="text-end">Rp <span class="currency">{{ $e->expense_amount }}</span></td>
                                    <td>
                                        @if($e->expense_status == 'Draft')
                                            <span class="badge bg-danger">{{ $e->expense_status }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $e->expense_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail" href="/keuangan/pengeluaran/detail/{{ $e->expense_id }}"><span class="icon-eye"></span></a>
                                        @if($e->expense_status == 'Draft')
                                        <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah" href="/keuangan/pengeluaran/draft/edit/{{ $e->expense_id }}"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" onclick="confirmAlert('/keuangan/pengeluaran/draft/hapus/{{ $e->expense_id }}', 'Anda yakin akan menghapus data ini?')"><span class="icon-trash"></span></a>
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
        $("#basic-datatables").DataTable({});
    });

</script>
@endsection
