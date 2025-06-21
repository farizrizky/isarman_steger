@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Stok Scaffolding</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/pdf/stok-item" target="_blank" class="btn btn-primary"><span class="fas fa-print"></span> Cetak</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Stok Total</th>
                                    <th class="table-primary">Tersedia (Belum Tersewa)</th>
                                    <th class="table-success">Tersewa</th>
                                    <th class="table-info">Perbaikan</th>
                                    <th class="table-warning">Rusak</th>
                                    <th class="table-danger">Hilang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock as $s)
                                @if($s->item)
                                <tr>
                                    <td>{{ HID::genId($s->item_id) }}</td>
                                    <td>
                                        <b><a href="/scaffolding/stok/item/{{$s->item_id}}">{{ $s->item?->item_name }}</a></b>
                                    </td>
                                    <td><span class="currency">{{ $s->stock_total }}</span></td>
                                    <td class="table-primary"><span class="currency">{{ $s->stock_available }}</span></td>
                                    <td class="table-success"><span class="currency">{{ $s->stock_rented }}</span></td>
                                    <td class="table-info"><span class="currency">{{ $s->stock_on_repair }}</span></td>
                                    <td class="table-warning"><span class="currency">{{ $s->stock_damaged }}</span></td>
                                    <td class="table-danger"><span class="currency">{{ $s->stock_lost }}</span></td>
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
    $(document).ready(function(){
        $("#basic-datatables").DataTable({});
    });
</script>
@endsection
