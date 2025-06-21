@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Penyewaan</h3>
            <small class="text-muted">Daftar penyewaan <strong>{{ $renter->renter_name }}</strong></small>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/sewa/draft/penyewa/{{ $renter->renter_id }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Draft Penyewaan Baru</a>
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
                                    <th>Status</th>
                                    <th>Nomor</th>
                                    <th>Proyek</th>
                                    <th>Alamat Proyek</th>
                                    <th>Total Biaya Sewa</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($renter->rent as $r)
                                <tr>
                                    <td>
                                        @if($r->rent_status == 'Draft')
                                        <span class="badge bg-secondary">{{ $r->rent_status }}</span>
                                        @elseif($r->rent_status == 'Berjalan')
                                        <span class="badge bg-primary">{{ $r->rent_status }}</span>
                                        @elseif($r->rent_status == 'Selesai')
                                        <span class="badge bg-success">{{ $r->rent_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($r->rent_status == 'Draft')
                                        #{{ HID::genId($r->rent_id) }}
                                        @else
                                        {{ HID::genNumberRent($r->rent_id) }}
                                        @endif
                                    </td>
                                    <td>{{ $r->rent_project_name }}</td>
                                    <td>{{ $r->rent_project_address }}</td>
                                    <td class="text-nowrap">Rp <span class="currency">{{ $r->rent_total_payment }}</span></td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rent_start_date) }}</td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rent_end_date) }}</td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Penyewaan" href="/sewa/draft/detail/{{ $r->rent_id }}"><span class="icon-eye"></span></a>
                                        @if($r->rent_status == 'Draft')
                                        <a class="btn btn-primary btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah Penyewaan" href="/sewa/draft/edit/{{ $r->rent_id }}"><span class="icon-pencil"></span></a>
                                        @endif
                                    </td>
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
