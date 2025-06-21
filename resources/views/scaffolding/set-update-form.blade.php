@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-sets-left align-sets-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Ubah Data Set</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/scaffolding/set/update/{{ $set->set_id }}" id="form" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="set_name" class="col-md-3 col-form-label text-wrap"><b>Nama Set</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $set->set_name }}" name="set_name" id="set_name" required>
                                <div class="invalid-feedback">Nama set harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="set_price_2_weeks" class="col-md-3 col-form-label text-wrap"><b>Biaya Sewa 2 Minggu</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $set->set_price_2_weeks }}" name="set_price_2_weeks" id="set_price_2_weeks" required>
                                    <div class="invalid-feedback">Biaya sewa harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="set_price_per_month" class="col-md-3 col-form-label text-wrap"><b>Biaya Sewa Per Bulan</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $set->set_price_per_month }}" name="set_price_per_month" id="set_price_per_month" required>
                                    <div class="invalid-feedback">Biaya sewa harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="set_price_per_month" class="col-md-3 col-form-label text-wrap"><b>Item Set</b></label>
                            <div class="col-md-3 p-0 me-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Item</span>
                                    <select class="select2 form-control" id="item">
                                        <option value="0">--Pilih Item--</option>
                                        @foreach ($item as $i)
                                        <option value="{{ $i->item_id }}">{{ $i->item_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 p-0 me-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Jumlah</span>
                                    <input type="text"  class="form-control form-control-sm currency" id="quantity">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 p-0 me-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="optional">
                                    <label class="form-check-label" for="flexCheckDefault">Item Optional</label>
                                </div>
                            </div>
                            <div class="col-md-1 p-0">
                                <div class="input-group mb-3 d-grid gap-2">
                                    <div class="btn btn-primary btn-sm" id="add-item">Tambah</div>
                                </div>
                            </div>
                        </div>
                        <div id = "selectedItem"></div>
                </div>
                <div class="card-action">
                    <a href="/scaffolding/set" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    <button class="btn btn-success float-end" name="submit"><span class="icon-check"></span> Simpan</button>
                    </form>
                  </div>
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

        @foreach ($item_set as $is)
        addItem("{{ $is->item_id }}", "{{ $is->item_name }}", "{{ $is->item_set_quantity }}", "{{ $is->item_set_optional }}");
        $('#item option[value="{{ $is->item_id }}"]').prop('disabled', true);
        @endforeach
    });

    $("#form").submit(function(e){
        var itemCount = $('#selectedItem > div').length;

        if(itemCount == 0){
            e.preventDefault();
            showAlert("Input Set Tidak Valid", "Item belum ditambahkan", "error");
        }
    });

    $("#add-item").click(function(){
        var id = $('#item').val();
        var name = $('#item option:selected').text();
        var quantity = $('#quantity').val();
        var optional = 0;
        if($('#optional').is(':checked')){
            optional = 1;
        }
        if(id!="0" && quantity!="" && quantity!="0"){
            addItem(id, name, quantity, optional);
            $('#item option:selected').prop('disabled', true);
            $('#quantity').val('0');
            $('#optional').prop('checked', false);
            $('#item').val('0');
            $('#item').select2({
                theme: 'bootstrap-5'
            });
        }
    });

    function addItem(id, name, quantity, optional){
        var optionalChecked = "";
        var optionalValue = 0;
        if(optional == 1){
            optionalChecked = "checked";
            optionalValue = 1;
        }
        $("#selectedItem").append(
            '<div class="form-group form-inline row" id="item_'+id+'">'+
                '<label for="set_price_per_month" class="col-md-3 col-form-label text-wrap"></label>'+
                ' <div class="col-md-3 p-0 me-2">'+
                    '<div class="input-group mb-3">'+
                        '<span class="input-group-text">Item</span>'+
                        '<input type="hidden" name="item_id[]" value="'+id+'">'+
                        '<input type="text" class="form-control form-control-sm" value="'+name+'" disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 col-sm-12 p-0 me-2">'+
                    '<div class="input-group mb-3">'+
                        '<span class="input-group-text">Jumlah</span>'+
                        '<input type="hidden" name="item_set_quantity[]" value="'+quantity+'">'+
                        ' <input type="text" class="form-control currency form-control-sm" value="'+quantity+'" disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 col-sm-12 p-0 me-2">'+
                    '<div class="form-check">'+
                        '<input type="hidden" name="item_set_optional[]" value="'+optionalValue+'">'+
                        '<input class="form-check-input" type="checkbox" id="flexCheckDefault" '+optionalChecked+' disabled>'+
                        '<label class="form-check-label" for="flexCheckDefault">Item Optional</label>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-1 p-0">'+
                    '<div class="input-group mb-3 d-grid gap-2">'+
                        '<div class="btn btn-danger btn-sm" onclick="removeItem('+id+')">Hapus</div>'+
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
