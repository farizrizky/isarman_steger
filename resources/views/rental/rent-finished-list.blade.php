@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Penyewaan Selesai</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/sewa/draft/input" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Draft Sewa</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row">
                    <div class="col-md-4">
                        <div class="form-group mb-3 bg-primary p-2 text-white rounded">
                            <label class="text-white">Status Pembayaran Sewa</label>
                            <select class="form-select" id="status_payment_rent">
                                <option value="">Semua</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Bayar">Belum Bayar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3 bg-success p-2 text-white rounded">
                            <label class="text-white">Status Kwitansi Pengembalian Sewa</label>
                            <select class="form-select" id="receipt_status">
                                <option value="">Semua</option>
                                <option value="Nihil">Nihil</option>
                                <option value="Klaim Ganti Rugi">Klaim Ganti Rugi</option>
                                <option value="Pengembalian Deposit">Pengembalian Deposit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3 bg-warning p-2 text-white rounded">
                            <label class="text-white">Status Pembayaran Pengembalian Sewa</label>
                            <select class="form-select" id="status_payment_rent_return">
                                <option value="">Semua</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Bayar">Belum Bayar</option>
                                <option value="Pending">Pending</option>
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
                                    <th>Selesai Sewa</th>
                                    <th>Pengembalian Sewa</th>
                                    <th>Total Sewa</th>
                                    <th>Total Biaya Pengembalian Sewa</th>
                                    <th>Status Kwitansi</th>
                                    <th>Sewa Lanjut</th>
                                    <th>No. Sewa Lanjutan</th>
                                    <th>Aksi</th>
                                    <th class="d-none">Status Pembayaran Sewa</th>
                                    <th class="d-none">Status Pembayaran Pengembalian</th>
                                    <th class="d-none">Status Pembayaran</th>
                                    <th class="d-none">Status Sort</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rent as $r)
                                <tr>
                                    <td class="text-nowrap"><a href="/sewa/penyewaan/detail/{{ $r->rent_id }}">{{ HID::genNumberRent($r->rent_id) }}</a></td>
                                    <td><b>{{ $r->renter->renter_name }}</b> <br> <a target="_blank" href="/wa/chat/{{ $r->renter->renter_phone }}">{{ $r->renter->renter_phone }}</a></td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rent_end_date) }}</td>
                                    <td class="text-nowrap">{{ HDate::dateFormat($r->rentReturn->rent_return_date) }}</td>
                                    <td class="text-nowrap">Rp <span class="currency">{{ $r->rent_total_payment }}</span> </td>
                                    <td class="text-nowrap">Rp <span class="currency">{{ $r->rentReturn->rent_return_total_payment }}</span></td>
                                    <td class="text-nowrap">{{ $r->rentReturn->rent_return_receipt_status }}</td>
                                    <td>
                                        @if ($r->rentReturn->rent_return_status == 'Selesai')
                                            Tidak
                                        @else
                                            Ya
                                        @endif
                                    </td>
                                    <td>
                                        @php $rentExtend = HData::getRentExtend($r->rent_id); @endphp
                                        @if($rentExtend == null)
                                            -
                                        @elseif ($rentExtend['rent_status'] == 'Draft')
                                           Draft No. # <a href="/sewa/draft/detail/{{ $rentExtend['rent_id'] }}">{{ HID::genId($rentExtend['rent_id']) }}</a>
                                        @else
                                            Sewa No. <a href="/sewa/penyewaan/detail/{{ $rentExtend['rent_id'] }}">{{ HID::genNumberRent($rentExtend['rent_id']) }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail" href="/sewa/penyewaan/detail/{{ $r->rent_id}}"><span class="icon-eye"></span></a>
                                        @if($r->rentReturn->rent_return_status == 'Selesai')
                                            <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Buat Sewa Lanjutan" href="/sewa/draft/input/{{ $r->rent_id}}"><span class="fas fa-redo"></span></a>
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
                                        @if ($r->rentReturn->rent_return_payment_status == 'Lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif ($r->rentReturn->rent_return_payment_status == 'Belum Bayar')
                                            <span class="badge badge-danger">Belum Bayar</span>
                                        @elseif ($r->rentReturn->rent_return_payment_status == 'Pending')
                                            <span class="badge badge-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td class="d-none">
                                        @if ($r->rentReturn->rent_return_payment_status == 'Lunas' && $r->rent_status_payment == 'Lunas')
                                            success
                                        @elseif ($r->rentReturn->rent_return_payment_status == 'Belum Bayar' || $r->rent_status_payment == 'Belum Bayar')
                                            danger
                                        @elseif ($r->rentReturn->rent_return_payment_status == 'Pending')
                                            secondary
                                        @endif
                                    </td>
                                    <td class="d-none">
                                        @if($r->rentReturn->rent_return_payment_status == 'Belum Bayar' || $r->rent_status_payment == 'Belum Bayar')
                                            0
                                        @elseif($r->rentReturn->rent_return_payment_status == 'Lunas' && $r->rent_status_payment == 'Lunas')
                                            1
                                        @else
                                            2
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
    function format(rowData) {
        return `
        <table class="table table-sm">
            <tr class="table-${rowData[12]}">
                <td><strong>Status Pembayaran Sewa ${rowData[10]}</strong></td>
                <td colspan="5" class="text-end">
                    <strong>Status Pembayaran Pengembalian Sewa (${rowData[6]})</strong> ${rowData[11]}
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
            "order": [[13, 'asc'], [0, 'asc']],
        });

        table.rows().every(function () {
            this.child(format(this.data())).show();
            $(this.node()).addClass('shown');
            $(this.child().get(0)).addClass('child-row');
        });

        table.on('responsive-display', function (e, datatable, row, showHide, update) {
            if (showHide) {
                // Re-init tooltip di dalam child row
                row.child().find('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        $("#receipt_status, #status_payment_rent, #status_payment_rent_return").on('change', function() {
            var receiptStatus = $('#receipt_status').val();
            var statusPaymentRent = $('#status_payment_rent').val();
            var statusPaymenRentReturntFilter = $('#status_payment_rent_return').val();
            filterRentReturn(receiptStatus, statusPaymentRent, statusPaymenRentReturntFilter);
        });
    });

    function filterRentReturn(receiptStatus, statusPaymentRent, statusPaymentRentReturn) {
        $.fn.dataTable.ext.search = [];
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                if ((receiptStatus === '' || data[6] === receiptStatus) &&
                    (statusPaymentRent === '' || data[10] === statusPaymentRent) &&
                    (statusPaymentRentReturn === '' || data[11] === statusPaymentRentReturn)) {
                    return true;
                }
                return false;
            }
        );
        table.draw();
    }
</script>
@endsection
