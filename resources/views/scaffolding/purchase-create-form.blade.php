@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-sets-left align-sets-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Input Pembelian Baru</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/scaffolding/pembelian/simpan" id="form" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="purchase_date" class="col-md-3 col-form-label text-wrap"><b>Tanggal Pembelian</b></label>
                            <div class="col-md-9 p-0">
                                <input type="date" class="form-control input-full" value="{{ old('purchase_date') }}" name="purchase_date" id="purchase_date" required>
                                <div class="invalid-feedback">Tanggal pembelian harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_total" class="col-md-3 col-form-label text-wrap"><b>Total Pembelian</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ old('purchase_total') }}" name="purchase_total" id="purchase_total" required>
                                    <div class="invalid-feedback">Total pembelian harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_supplier" class="col-md-3 col-form-label text-wrap"><b>Supplier</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ old('purchase_supplier') }}" name="purchase_supplier" id="purchase_supplier" required>
                                <div class="invalid-feedback">Supplier harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="purchase_receipt_file" class="col-md-3 col-form-label text-wrap"><b>Kwitansi Pembelian</b></label>
                            <div class="col-md-9 p-0">
                                <input type="file" class="form-control input-full" value="{{ old('purchase_receipt') }}" name="purchase_receipt_file" id="purchase_receipt_file" required>
                                <div class="invalid-feedback">Kwitansi pembelian harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="set_price_per_month" class="col-md-3 col-form-label text-wrap"><b>Item Pembelian</b></label>
                            <div class="col-md-4 p-0 me-2">
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
                            <div class="col-md-3 col-sm-12 p-0 me-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Jumlah</span>
                                    <input type="text" class="form-control form-control-sm currency" id="quantity">
                                </div>
                            </div>
                            <div class="col-md-1 p-0">
                                <div class="input-group mb-3 d-grid gap-2">
                                    <div class="btn btn-primary btn-sm" id="add-item">Tambah</div>
                                </div>
                            </div>
                        </div>
                        <div id="selectedItem"></div>
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
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
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

    function addItem(id, name, quantity){
        $("#selectedItem").append(
            '<div class="form-group form-inline row" id="item_'+id+'">'+
                '<label for="set_price_per_month" class="col-md-3 col-form-label text-wrap"></label>'+
                ' <div class="col-md-4 p-0 me-2">'+
                    '<div class="input-group mb-3">'+
                        '<span class="input-group-text">Item</span>'+
                        '<input type="hidden" name="item_id[]" value="'+id+'">'+
                        '<input type="text" class="form-control" value="'+name+'" disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-3 col-sm-12 p-0 me-2">'+
                    '<div class="input-group mb-3">'+
                        '<span class="input-group-text">Jumlah</span>'+
                        '<input type="hidden" name="purchase_item_quantity[]" value="'+quantity+'">'+
                        ' <input type="text" class="form-control currency" value="'+quantity+'" disabled>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-1 p-0">'+
                    '<div class="input-group mb-3 d-grid gap-2">'+
                        '<div class="btn btn-danger" onclick="removeItem('+id+')">Hapus</div>'+
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
