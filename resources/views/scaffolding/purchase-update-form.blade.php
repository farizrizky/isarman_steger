@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-sets-left align-sets-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Update Data Pembelian & Penerimaan</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/scaffolding/pembelian/update/{{ $purchase->purchase_id }}" enctype="multipart/form-data" id="form" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="purchase_date" class="col-md-3 col-form-label"><b>Tanggal Pembelian</b></label>
                            <div class="col-md-9 p-0">
                                <input type="date" class="form-control input-full create-input" value="{{ $purchase->purchase_date }}" name="purchase_date" id="purchase_date" required>
                                <div class="invalid-feedback">Tanggal pembelian harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_total" class="col-md-3 col-form-label"><b>Total Pembelian</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency create-input" value="{{ $purchase->purchase_total }}" name="purchase_total" id="purchase_total" required>
                                    <div class="invalid-feedback">Total pembelian harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_supplier" class="col-md-3 col-form-label"><b>Supplier</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full create-input" value="{{ $purchase->purchase_supplier }}" name="purchase_supplier" id="purchase_supplier" >
                                <div class="invalid-feedback">Supplier harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_receipt_file" class="col-md-3 col-form-label"><b>Kwitansi Pembelian</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control create-input" name="purchase_receipt_file" id="purchase_receipt_file">
                                    <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchase_receipt_photo)}}">Lihat Kwitansi</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="set_price_per_month" class="col-md-3 col-form-label"><b>Item Pembelian</b></label>
                            <div class="col-md-4 p-0 me-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Item</span>
                                    <select class="select2 form-control create-input" id="item">
                                        <option value="0">--Pilih Item--</option>
                                        @foreach ($item as $i)
                                        <option value="{{ $i->item_id }}">{{ $i->item_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 p-0 me-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Jumlah</span>
                                    <input type="text"  class="form-control form-control-sm currency create-input" id="quantity">
                                </div>
                            </div>
                            <div class="col-md-1 p-0">
                                <div class="input-group mb-3 d-grid gap-2">
                                    <a class="btn btn-primary btn-sm create-input" id="add-item">Tambah</a>
                                </div>
                            </div>
                        </div>
                        <div id = "selectedItem">
                            
                        </div>
                        @if($purchase->purchase_status == "Belum Diterima")
                        <div class="form-group form-inline row">
                          <label class="form-label col-md-3 col-form-label">Status</label>
                            <div class="selectgroup col-md-6 p-0">
                                <label class="selectgroup-item">
                                    <input type="radio" name="purchase_status" value="Belum Diterima" class="selectgroup-input"checked="">
                                    <span class="selectgroup-button">Belum Diterima</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="purchase_status" value="Diterima" class="selectgroup-input">
                                    <span class="selectgroup-button">Sudah Diterima</span>
                                </label>
                            </div>
                        </div>
                        @else
                        <div class="form-group form-inline row">
                            <label class="form-label col-md-3 col-form-label">Status</label>
                              <div class="selectgroup col-md-9 p-0">
                                  <label class="selectgroup-item">
                                      <input type="radio" class="selectgroup-input" name="purchase_status" value="Diterima" checked="">
                                      <span class="selectgroup-button">Sudah Diterima</span>
                                  </label>
                              </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_date" class="col-md-3 col-form-label"><b>Tanggal Diterima</b></label>
                            <div class="col-md-9 p-0">
                                <input type="date" class="form-control input-full" value="{{ $purchase->purchase_accepted_date }}" name="purchase_accepted_date" id="purchase_accepted_date" required>
                                <div class="invalid-feedback">Tanggal diterima harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_courier_name" class="col-md-3 col-form-label"><b>Nama Kurir</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_name }}"  name="purchase_accepted_evidence_courier_name" id="purchase_accepted_evidence_courier_name" required>
                                <div class="invalid-feedback">Nama kurir harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_vehicle_number" class="col-md-3 col-form-label"><b>Nomor Kendaraan</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_number }}"  name="purchase_accepted_evidence_vehicle_number" id="purchase_accepted_evidence_vehicle_number" required>
                                <div class="invalid-feedback">Nomor kendaraan harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_courier_photo_file" class="col-md-3 col-form-label"><b>Foto Kurir</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="purchase_accepted_evidence_courier_file" id="purchase_accepted_evidence_courier_file">
                                    <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_photo) }}">Lihat Bukti</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_courier_identity_file" class="col-md-3 col-form-label"><b>Identitas Kurir</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="purchase_accepted_evidence_courier_identity_file" id="purchase_accepted_evidence_courier_identity_file">
                                    <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_identity_photo) }}">Lihat Bukti</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_vehicle_photo_file" class="col-md-3 col-form-label"><b>Foto Kendaraan</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="purchase_accepted_evidence_vehicle_file" id="purchase_accepted_evidence_vehicle_file">
                                    <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_photo) }}">Lihat Bukti</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_vehicle_identity_file" class="col-md-3 col-form-label"><b>Identitas Kendaraan</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="purchase_accepted_evidence_vehicle_identity_file" id="purchase_accepted_evidence_vehicle_identity_file">
                                    <a class="input-group-text" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_identity_photo) }}">Lihat Bukti</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_accepted_evidence_file_file" class="col-md-3 col-form-label"><b>Berkas Penerimaan (Optional)</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="purchase_accepted_evidence_file_file" id="purchase_accepted_evidence_file_file">
                                    <a class="input-group-text" target="_blank" href="{{ '/storage/'.$purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_file }}">Lihat Bukti</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div id="purchase_accepted" style="display:none;">
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_date" class="col-md-3 col-form-label"><b>Tanggal Diterima</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="date" class="form-control input-full" name="purchase_accepted_date" id="purchase_accepted_date">
                                    <div class="invalid-feedback">Tanggal diterima harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_courier_name" class="col-md-3 col-form-label"><b>Nama Kurir</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="text" class="form-control input-full"  name="purchase_accepted_evidence_courier_name" id="purchase_accepted_evidence_courier_name">
                                    <div class="invalid-feedback">Nama kurir harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_vehicle_number" class="col-md-3 col-form-label"><b>Nomor Kendaraan</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="text" class="form-control input-full"  name="purchase_accepted_evidence_vehicle_number" id="purchase_accepted_evidence_vehicle_number">
                                    <div class="invalid-feedback">Nomor kendaraan harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_courier_photo_file" class="col-md-3 col-form-label"><b>Foto Kurir</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="file" class="form-control input-full"  name="purchase_accepted_evidence_courier_file" id="purchase_accepted_evidence_courier_file">
                                    <div class="invalid-feedback">Foto kurir harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_courier_identity_file" class="col-md-3 col-form-label"><b>Identitas Kurir</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="file" class="form-control input-full"  name="purchase_accepted_evidence_courier_identity_file" id="purchase_accepted_evidence_courier_identity_file">
                                    <div class="invalid-feedback">Identitas kurir harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_vehicle_photo_file" class="col-md-3 col-form-label"><b>Foto Kendaraan</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="file" class="form-control input-full"  name="purchase_accepted_evidence_vehicle_file" id="purchase_accepted_evidence_vehicle_file">
                                    <div class="invalid-feedback">Foto kendaraan harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_vehicle_identity_file" class="col-md-3 col-form-label"><b>Identitas Kendaraan</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="file" class="form-control input-full"  name="purchase_accepted_evidence_vehicle_identity_file" id="purchase_accepted_evidence_vehicle_identity_file">
                                    <div class="invalid-feedback">Identitas kendaraan harus diisi</div>
                                </div>
                            </div>
                            <div class="form-group form-inline row">
                                <label for="purchase_accepted_evidence_file_file" class="col-md-3 col-form-label"><b>Berkas Penerimaan (Optional)</b></label>
                                <div class="col-md-9 p-0">
                                    <input type="file" class="form-control input-full"  name="purchase_accepted_evidence_file_file" id="purchase_accepted_evidence_file_file">
                                </div>
                            </div>
                        </div>

                </div>
                <div class="card-action">
                    <a href="/scaffolding/pembelian" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    <button class="btn btn-success float-end" name="submit"><span class="icon-check"></span> Simpan</button>
                    </form>
                  </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="image_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar</h5>
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
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        @foreach ($purchase->purchaseItem as $pi)
        addItem("{{ $pi->item_id }}", "{{ $pi->item->item_name }}", "{{ $pi->purchase_item_quantity }}");
        $('#item option[value="{{ $pi->item_id }}"]').prop('disabled', true);
        @endforeach

        @if($purchase->purchase_status == "Diterima")
        $('#purchase_accepted').remove();
        $('.create-input').prop('disabled', true)
        @endif
    });

    $("#form").submit(function(e){
        var itemCount = $('#selectedItem > div').length;

        if(itemCount == 0){
            e.preventDefault();
            showAlert("Input Pembelian Tidak Valid", "Item belum ditambahkan", "error");
        }
    });

    $("#add-item").click(function(){
        var id = $('#item').val();
        var name = $('#item option:selected').text();
        var quantity = $('#quantity').val()
        if(id!="0" && quantity!="" && quantity!="0"){
            addItem(id, name, quantity);
            $('#item option:selected').prop('disabled', true);
            $('#item').val('0');
            $('#item').select2({
                theme: 'bootstrap-5'
            });
        }
    });

    $('#image_modal').on('show.bs.modal', function (event) {
        var data = $(event.relatedTarget);
        var image = data.data('image');

        $('#image_show').attr("src", image);
    })

    $('input[name="purchase_status"]').change(function(){
        var status = this.value;
        if(status == 'Diterima'){
            $('#purchase_accepted').css('display', '')
            $('#purchase_accepted_date').attr('required', true)
            $('#purchase_accepted_evidence_courier_name').attr('required', true)
            $('#purchase_accepted_evidence_vehicle_number').attr('required', true)
            $('#purchase_accepted_evidence_courier_file').attr('required', true)
            $('#purchase_accepted_evidence_courier_identity_file').attr('required', true)
            $('#purchase_accepted_evidence_vehicle_file').attr('required', true)
            $('#purchase_accepted_evidence_vehicle_identity_file').attr('required', true)
        }else{
            $('#purchase_accepted').css('display', 'none')
            $('#purchase_accepted_date').removeAttr('required')
            $('#purchase_accepted_evidence_courier_name').removeAttr('required')
            $('#purchase_accepted_evidence_vehicle_number').removeAttr('required')
            $('#purchase_accepted_evidence_courier_file').removeAttr('required')
            $('#purchase_accepted_evidence_courier_identity_file').removeAttr('required')
            $('#purchase_accepted_evidence_vehicle_file').removeAttr('required')
            $('#purchase_accepted_evidence_vehicle_identity_file').removeAttr('required')
        }
    })

    function addItem(id, name, quantity){
        
        $("#selectedItem").append(
            '<div class="form-group form-inline row" id="item_'+id+'">'+
                '<label for="set_price_per_month" class="col-md-3 col-form-label"></label>'+
                ' <div class="col-md-4 p-0 me-2">'+
                    '<div class="input-group mb-3">'+
                        '<span class="input-group-text">Item</span>'+
                        '<input type="hidden" name="item_id[]" value="'+id+'">'+
                        '<input type="text" class="form-control form-control-sm" value="'+name+'" disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-3 col-sm-12 p-0 me-2">'+
                    '<div class="input-group mb-3">'+
                        '<span class="input-group-text">Jumlah</span>'+
                        '<input type="hidden" name="purchase_item_quantity[]" value="'+quantity+'">'+
                        ' <input type="text" class="form-control currency form-control-sm" value="'+quantity+'" disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-1 p-0">'+
                    '<div class="input-group mb-3 d-grid gap-2">'+
                        '<button class="btn btn-danger btn-sm create-input" onclick="removeItem('+id+')">Hapus</button>'+
                    '</div>'+
                '</div>'+
            '</div>'
        );        
    }

    function removeItem(id){
        $('#item option[value="'+id+'"]').prop('disabled', false);
        $('#item_'+id).remove()
    }
</script>

@endsection
