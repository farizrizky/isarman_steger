@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Penyewaan Berjalan</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/sewa/draft/input" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Draft Sewa</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row">
                    <div class="col-md-6">
                        <div class="form-group mb-3 bg-primary p-2 text-white rounded">
                            <label class="text-white">Status Waktu Sewa</label>
                            <select class="form-select" id="duration_left">
                                <option value="">Semua</option>
                                <option value="expired">Lewat Tanggal Selesai</option>
                                <option value="today">Selesai Hari Ini</option>
                                <option value="warning">Selesai Dalam 7 Hari</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3 bg-success p-2 text-white rounded">
                            <label class="text-white">Status Pembayaran</label>
                            <select class="form-select" id="payment_status">
                                <option value="">Semua</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Bayar">Belum Bayar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="table table-bordered table-sm">
                            <thead> 
                                <tr class="table-primary">
                                    <th>Nomor Penyewaan</th>
                                    <th>Penyewa</th>
                                    <th>Mulai Sewa</th>
                                    <th>Selesai Sewa</th>
                                    <th>Total Sewa</th>
                                    <th>Aksi</th>
                                    <th class="d-none">Sisa Waktu Angka</th>
                                    <th class="d-none">Sisa Waktu Teks</th>
                                    <th class="d-none">Status Sisa Waktu</th>
                                    <th class="d-none">Status Pembayaran</th>
                                    <th class="d-none">WA Informasi Sisa Waktu Sewa</th>
                                    <th class="d-none">Status Sort</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rent as $r)
                                <tr>
                                    <td class="text-nowrap"><a href="/sewa/penyewaan/detail/{{ $r->rent_id }}">{{ HID::genNumberRent($r->rent_id) }}</a></td>
                                    <td><b>{{ $r->renter->renter_name }}</b> <br> <a target="_blank" href="/wa/chat/{{ $r->renter->renter_phone }}">{{ $r->renter->renter_phone }}</a></td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rent_start_date) }}</td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rent_end_date) }}</td>
                                    <td class="text-nowrap">Rp <span class="currency">{{ $r->rent_total_price }}</span> </td>
                                    <td><a class="btn btn-info btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail" href="/sewa/penyewaan/detail/{{ $r->rent_id}}"><span class="icon-eye"></span></a></td>
                                    <td class="d-none">
                                        @php $daysDiffFromNow = HDate::daysDiffFromNow($r->rent_end_date); @endphp
                                        {{ $daysDiffFromNow }}
                                    </td>
                                    <td class="d-none">
                                        @php $daysDiffFromNow = HDate::daysDiffFromNow($r->rent_end_date); @endphp
                                        @if($daysDiffFromNow < 0)
                                            Lewat {{ abs($daysDiffFromNow) }} hari</span>
                                        @elseif($daysDiffFromNow == 0)
                                            Berakhir Hari Ini
                                        @elseif($daysDiffFromNow >=1 && $daysDiffFromNow <= 7)
                                            Berakhir dalam {{ $daysDiffFromNow }} hari
                                        @else
                                            Berakhir dalam {{ $daysDiffFromNow }} hari
                                        @endif
                                        
                                    </td>
                                    <td class="d-none">
                                        @if($daysDiffFromNow < 0)
                                            danger
                                        @elseif($daysDiffFromNow == 0)
                                            secondary
                                        @elseif($daysDiffFromNow >=1 && $daysDiffFromNow <= 7)
                                            warning
                                        @else
                                            success
                                        @endif
                                    </td>
                                    <td class="d-none">
                                        @if ($r->rent_status_payment == 'Lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif ($r->rent_status_payment == 'Belum Bayar')
                                            <span class="badge badge-danger">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td class="d-none">
                                        <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim WhatsApp Informasi Sisa Waktu Sewa Kepada Penyewa" target="_blank" href="/wa/informasi-sisa-waktu-sewa/{{ $r->rent_id }}"><span class="fab fa-whatsapp"></span></a>
                                    </td>
                                    <td class="d-none">
                                        @if ($r->rent_status_payment == 'Lunas')
                                            1
                                        @elseif ($r->rent_status_payment == 'Belum Bayar')
                                            0
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
    let table;

    function format(rowData) {
        return `
        <table class="table table-sm">
            <tr class="table-${rowData[8]}">
                <td><strong>${rowData[7]}</strong>  ${rowData[10]}</td>
                <td colspan="5" class="text-end">
                    <strong>Status Pembayaran</strong> ${rowData[9]}
                   
                </td>
            </tr>
        </table>
        `;
    }
    
    $(document).ready(function() {
        table = $("#basic-datatables").DataTable({
            "responsive": {
                "details": {
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.hidden ? '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                '<td>' + col.title + ':' + '</td> ' +
                                '<td>' + col.data + '</td></tr>' : '';
                        }).join('');
                        return '<table class="table">' + data + '</table>' + format(api.row(rowIdx).data());
                    }
                }
            },
            "autoWidth": false,
            "order": [[11, 'asc'],[0, 'desc']],
        });

        table.rows().every(function () {
            this.child(format(this.data())).show();
            $(this.node()).addClass('shown');
            $(this.child().get(0)).addClass('child-row');
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        table.on('responsive-display', function (e, datatable, row, showHide, update) {
            if (showHide) {
                // Re-init tooltip di dalam child row
                row.child().find('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        table.on('draw', function () {
            
        });

        $('#duration_left').on('change', function() {
            var durationLeft = $(this).val();
            var paymentStatus = $('#payment_status').val();
            filterRent(durationLeft, paymentStatus);
        });

        $('#payment_status').on('change', function() {
            var paymentStatus = $(this).val();
            var durationLeft = $('#duration_left').val();
            filterRent(durationLeft, paymentStatus);
        });
    });

    function filterRent(durationLeft, paymentStatus) {
        $.fn.dataTable.ext.search = [];
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var durationLeftValue = parseFloat(data[6]) || 0;
                var paymentStatusValue = data[9];

                if (durationLeft === 'expired' && durationLeftValue >= 0) {
                    return false;
                } else if (durationLeft === 'today' && durationLeftValue !== 0) {
                    return false;
                } else if (durationLeft === 'warning' && (durationLeftValue < 1 || durationLeftValue > 7)) {
                    return false;
                }

                if (paymentStatus === 'Lunas' && paymentStatusValue !== 'Lunas') {
                    return false;
                } else if (paymentStatus === 'Belum Bayar' && paymentStatusValue !== 'Belum Bayar') {
                    return false;
                }

                return true;
            }
        );
        table.draw();
    }
</script>
@endsection
