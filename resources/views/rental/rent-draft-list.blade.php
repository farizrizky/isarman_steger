@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Draft Sewa</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/sewa/draft/input" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Draft Sewa</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="table table-bordered table-striped" width="100%">
                            <thead> 
                                <tr class="table-primary">
                                    <th>Nomor Invoice</th>
                                    <th>Penyewa</th>
                                    <th>Total Sewa</th>
                                    <th>Durasi Sewa</th>
                                    <th>Mulai Sewa</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rent as $r)
                                <tr>
                                    <td>#{{ HID::genId($r->rent_id) }}</td>
                                    <td>
                                        <b>{{ $r->renter->renter_name }}</b><hr>
                                        Telepon : <a target="_blank" href="/wa/chat/{{ $r->renter->renter_phone }}">{{ $r->renter->renter_phone }}</a>
                                    </td>
                                    <td class="text-nowrap">Rp <span class="currency">{{ $r->rent_total_payment }}</span></td>
                                    <td class="text-nowrap">
                                        @if($r->rent_duration == '2 Minggu')
                                            2 Minggu
                                        @elseif($r->rent_duration == 'Per Bulan')
                                            {{ $r->rent_total_duration }} Bulan
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rent_start_date) }}</td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->created_at) }}</td>
                                    <td class="text-nowrap">
                                        <select class="form-select" id="action_table" onchange="actionTable(this.value, '{{ $r->rent_id }}')">
                                            <option value="">Pilih</option>
                                            <option value="detail">Detail</option>
                                            <option value="edit">Ubah</option>
                                            <option value="delete">Hapus</option>
                                            <option value="wa-invoice">Kirim Invoice via WhatsApp</option>
                                        </select>
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
  
    $(document).ready(function() {
        $("#basic-datatables").DataTable({
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [
                {responsivePriority: 1, targets: 0}, // Nomor Invoice
                {responsivePriority: 2, targets: 1}, // Penyewa
                {responsivePriority: 3, targets: 2}, // Total Sewa
                {responsivePriority: 4, targets: 3}, // Tanggal Dibuat
                {responsivePriority: 5, targets: 4}, // Durasi Sewa
                {responsivePriority: 6, targets: 5}  // Aksi
            ]

        });
    });

    function actionTable(action, rentId) {
       switch (action) {
            case 'detail':
                window.location.href = '/sewa/draft/detail/' + rentId;
                break;
            case 'edit':
                window.location.href = '/sewa/draft/edit/' + rentId;
                break;
            case 'delete':
                confirmAlert('/sewa/draft/hapus/' + rentId, 'Apakah Anda yakin ingin menghapus draft sewa ini?');
                break;
            case 'wa-invoice':
                window.location.href = '/wa/invoice-penyewaan/' + rentId;
                break;
            default:
                break;
        }
        $('#action_table').val('');
    }
   
</script>
@endsection
