@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Stok Scaffolding</h3>
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
                                    <th>Item</th>
                                    <th>Stok Total</th>
                                    <th>Rusak</th>
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
                                    <td><span class="currency">{{ $s->stock_damaged }}</span></td>
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
