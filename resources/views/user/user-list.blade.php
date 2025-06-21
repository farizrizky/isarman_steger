@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">User</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/user/input" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-sm">
                            <thead>
                                <tr class="table-primary">
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th>Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user as $u)
                                <tr>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->fullname }}</td>
                                    <td><a href="/wa/chat/{{ $u->phone }}">{{ $u->phone }}</a></td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->roles->first()->name }}</td>
                                    <td>
                                        @if($u->is_active)
                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                        @else
                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah" href="/user/edit/{{ $u->id}}"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" onclick="confirmAlert('/user/hapus/{{ $u->id }}', 'Anda yakin akan menghapus data ini?')"><span class="icon-trash"></span></a>
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
