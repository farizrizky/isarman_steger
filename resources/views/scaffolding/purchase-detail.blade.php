@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Data Pembelian Scaffolding <br>ID : {{ HID::genId($purchase->purchase_id) }}</h3>
            
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <small>Total Pembelian  :</small>
            <h1>Rp <span class="currency">{{ $purchase->purchase_total }}</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5>Detail</h5></div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Tanggal Pembelian</td>
                            <td>:</td>
                            <td>{{ $purchase->purchase_date }}</td>
                        </tr>
                        <tr>
                            <td>Suplier</td>
                            <td>:</td>
                            <td>{{ $purchase->purchase_supplier }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>{{ $purchase->purchase_status }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Diterima</td>
                            <td>:</td>
                            <td>{{ $purchase->purchase_accepted_date }}</td>
                        </tr>
                        @if($purchase->purchase_status == "Diterima")
                        <tr>
                            <td>Nama Kurir</td>
                            <td>:</td>
                            <td>{{ $purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_name }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Kendaraan</td>
                            <td>:</td>
                            <td>{{ $purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_number }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Kwitansi Pembelian</td>
                            <td>:</td>
                            <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchase_receipt_photo) }}">Lihat Berkas</button></td>
                        </tr>
                        @if($purchase->purchase_status == "Diterima")
                        <tr>
                            <td>Foto Kurir</td>
                            <td>:</td>
                            <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_photo) }}">Lihat Berkas</button></td>
                        </tr>
                        <tr>
                            <td>Identitas Kurir</td>
                            <td>:</td>
                            <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_identity_photo) }}">Lihat Berkas</button></td>
                        </tr>
                        <tr>
                            <td>Foto Kendaraan</td>
                            <td>:</td>
                            <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_photo) }}">Lihat Berkas</button></td>
                        </tr>
                        <tr>
                            <td>Identitas Kendaraan</td>
                            <td>:</td>
                            <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_identity_photo) }}">Lihat Berkas</button></td>
                        </tr>
                        @if($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_file)
                        <tr>
                            <td>Berkas Penerimaan</td>
                            <td>:</td>
                            <td><a class="btn btn-success btn-sm" target="_blank" href="{{ asset('/storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_file) }}">Lihat Berkas</a></td>
                        </tr>
                        @endif
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5>Item Pembelian</h5></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Jumlah</th>
                        </thead>
                        <tbody>
                            @foreach($purchase->purchaseItem as $pi)
                            <tr>
                                <td>{{ HID::genId($pi->item_id) }}</td>
                                <td>{{ $pi->item->item_name }}</td>
                                <td>{{ $pi->purchase_item_quantity }} {{ $pi->item_unit }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="image_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img class="img-fluid" id="image_show">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
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

    $('#image_modal').on('show.bs.modal', function (event) {
        var data = $(event.relatedTarget);
        var image = data.data('image');

        $('#image_show').attr("src", image);
    });

  
</script>
@endsection
