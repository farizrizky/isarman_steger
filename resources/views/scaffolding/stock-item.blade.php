@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div class=" mb-3">
            <h3 class="fw-bold">Stok {{ $stock->item->item_name }} ({{ HID::genId($stock->item->item_id) }})</h3>
            Update Terakhir <i>{{ HDate::fullDateFormat($stock->updated_at) }}</i>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Kondisi Stok Terkini</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Total</th>
                                <th>Tersedia</th>
                                <th>Tersewa</th>
                                <th>Hilang</th>
                                <th>Rusak</th>
                                <th>Perbaikan</th>
                                <th>Sengketa</th>
                                <th>Tidak Diketahui</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>{{ $stock->stock_total }}</strong></td>
                                    <td><strong>{{ $stock->stock_available }}</strong></td>
                                    <td>{{ $stock->stock_rented }}</td>
                                    <td>{{ $stock->stock_lost }}</td>
                                    <td>{{ $stock->stock_damaged }}</td>
                                    <td>{{ $stock->stock_on_repair }}</td>
                                    <td>{{ $stock->stock_unknown }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Riwayat Arus Item</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-sm">
                            <thead>
                                <tr class="table-primary">
                                    <th class="d-none">Tanggal</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Kuantitas</th>
                                    <th>Keterangan</th>
                                    <th>Diproses Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stock_flow as $sf)
                                @if($sf->item)
                                <tr>
                                    <td class="d-none">{{ $sf->updated_at }}</td>
                                    <td>{{ HDate::fullDateFormat($sf->updated_at) }}</td>
                                    <td>
                                        @if($sf->stock_flow_status == "Masuk")
                                        <span class="badge bg-success">{{ $sf->stock_flow_status }}</span>
                                        @elseif($sf->stock_flow_status == "Keluar")
                                        <span class="badge bg-danger">{{ $sf->stock_flow_status }}</span>
                                        @elseif($sf->stock_flow_status == "Diubah")
                                        <span class="badge bg-warning">{{ $sf->stock_flow_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sf->stock_flow_quantity }} {{ $sf->item->item_unit }}</td>
                                    <td>
                                        <strong>{{ $sf->stock_flow_description }}</strong><br>
                                        @if($sf->stock_flow_reference_model == "Purchase")
                                        Pembelian ID <a href="/scaffolding/pembelian/detail/{{ $sf->stock_flow_reference_id }}" target="_blank">{{ HID::genId($sf->stock_flow_reference_id) }}</a>
                                        @elseif($sf->stock_flow_reference_model == "Rent")
                                        Penyewaan No. <a href="/sewa/penyewaan/detail/{{ $sf->stock_flow_reference_id }}" target="_blank">{{ HID::genNumberRent($sf->stock_flow_reference_id) }}</a>
                                        @elseif($sf->stock_flow_reference_model == "Repair")
                                        Perbaikan ID <a href="/scaffolding/perbaikan/detail/{{ $sf->stock_flow_reference_id }}" target="_blank">{{ HID::genId($sf->stock_flow_reference_id) }}</a>
                                        @elseif($sf->stock_flow_reference_model == "RentReturn")
                                        Penyewaan No. <a href="/sewa/penyewaan/detail/{{ $sf->stock_flow_reference_id }}" target="_blank">{{ HID::genNumberRent($sf->stock_flow_reference_id) }}</a>
                                        @elseif($sf->stock_flow_reference_model == "Item")
                                        Item ID <a href="/scaffolding/item/detail/{{ $sf->stock_flow_reference_id }}" target="_blank">{{ HID::genId($sf->stock_flow_reference_id) }}</a>
                                        @endif
                                    </td>
                                    <td>{{ $sf->user->first()->fullname}}</td>
                                </tr>
                                @endif
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
   var table = $('#basic-datatables').DataTable();

    $(document).ready(function() {
        table.order(['0', 'desc']).draw();
    });

</script>
@endsection
