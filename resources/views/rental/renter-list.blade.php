@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Penyewa</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/sewa/penyewa/input" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Penyewa Baru</a>
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
                                    <th>ID</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Foto Identitas</th>
                                    <th>Pekerjaan</th>
                                    <th>Draft Penyewaan</th>
                                    <th>Total Penyewaan Berjalan</th>
                                    <th>Total Penyewaan Selesai</th>
                                    <th>Total Penyewaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($renter as $r)
                                <tr>
                                    <td>{{ HID::genId($r->renter_id) }}</td>
                                    <td>{{ $r->renter_identity_number }}</td>
                                    <td>{{ $r->renter_name }}</td>
                                    <td>
                                        <a href="/wa/chat/{{ $r->renter_phone }}" target="_blank">{{ $r->renter_phone }}</a>
                                    </td>
                                    <td>{{ $r->renter_address }}</td>
                                    <td>
                                        @if($r->renter_identity_photo)
                                        <a href="{{ asset('storage/'.$r->renter_identity_photo) }}" target="_blank">Lihat Identitas</a>
                                        @else
                                        Tidak ada foto identitas
                                        @endif
                                    </td>
                                    <td>{{ $r->renter_job }}</td>
                                    <td>{{ $r->rent->where('rent_status', 'Draft')->count() }}</td>
                                    <td>{{ $r->rent->where('rent_status', 'Berjalan')->count() }}</td>
                                    <td>{{ $r->rent->where('rent_status', 'Selesai')->count() }}</td>
                                    <td>{{ $r->rent->count() }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Penyewa" href="/sewa/penyewa/detail/{{ $r->renter_id }}"><span class="icon-eye"></span></a>
                                        <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah Penyewa" href="/sewa/penyewa/edit/{{ $r->renter_id }}"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Penyewa" onclick="confirmAlert('/sewa/penyewa/hapus/{{ $r->renter_id }}', 'Anda yakin akan menghapus penyewa ini?')"><span class="icon-trash"></span></a>
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
