@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Item Scaffolding</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/scaffolding/set" class="btn btn-secondary"><i class="fas fa-cog"></i> Pengaturan Set</a>
            <a href="/scaffolding/item/input" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Item</a>
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
                                    <th>Biaya Sewa</th>
                                    <th>Denda</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item as $i)
                                <tr>
                                    <td>{{ HID::genId($i->item_id) }}</td>
                                    <td>
                                        <b><a href="/scaffolding/stok/item/{{$i->item_id}}">{{ $i->item_name }}</a></b>
                                        <br>
                                        <small>Stok : <span class="currency">{{ $i->stock->stock_total }}</span> {{ $i->item_unit }}</small>
                                    </td>
                                    <td>
                                        <ul>
                                            <li><small>2 Minggu : Rp <span class="currency">{{ $i->item_price_2_weeks }}</span></small></li>
                                            <li><small>Per Bulan : Rp <span class="currency">{{ $i->item_price_per_month }}</span></small></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li><small>Kerusakan : Rp <span class="currency">{{ $i->item_fine_damaged }}</span></small></li>
                                            <li><small> Kehilangan : Rp <span class="currency">{{ $i->item_fine_lost }}</span></small></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah" href="/scaffolding/item/edit/{{ $i->item_id}}"><span class="icon-pencil"></span></a>
                                        <a class="btn btn-danger btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" onclick="confirmAlert('/scaffolding/item/hapus/{{ $i->item_id }}', 'Anda yakin akan menghapus data ini?')"><span class="icon-trash"></span></a>
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
