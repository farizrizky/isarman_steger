@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Set Scaffolding</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/scaffolding/set/input" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Set</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-bordered table-striped">
                            <thead>
                                <tr class="table-primary">
                                    <th>ID</th>
                                    <th>Set</th>
                                    <th>Item</th>
                                    <th>Biaya Sewa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($set as $s)
                                <tr>
                                    <td>{{  HID::genId($s->set_id) }}</td>
                                    <td><b>{{ $s->set_name }}</b></td>
                                    <td>
                                        <ul>
                                        @foreach ($s->itemSet as $is)
                                            <li><small>{{ $is->item->item_name }} ( {{ $is->item_set_quantity }} {{ $is->item->item_unit }} ) @if($is->item_set_optional == 1) ({{ "Optional" }}) @endif</small></li>
                                        @endforeach 
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li><small>2 Minggu : Rp <span class="currency">{{ $s->set_price_2_weeks }}</span></small></li>
                                            <li><small>Per Bulan : Rp <span class="currency">{{ $s->set_price_per_month }}</span></small></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-sm m-1" href="/scaffolding/set/edit/{{ $s->set_id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" onclick="confirmAlert('/scaffolding/set/hapus/{{ $s->set_id }}', 'Anda yakin akan menghapus data ini?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><span class="icon-trash"></span></a>
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
