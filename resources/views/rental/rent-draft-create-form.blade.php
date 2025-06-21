@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Buat Draft Sewa</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <h3>Tanggal : <span class="text-primary">{{ date('d-m-Y')}}</span></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/sewa/draft/buat" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header row">
                        <div class="col-md-8">
                            <h4 class="float-start">Identitas Penyewa</h4>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-inline row">
                                <label for="exists_renter"><b>Gunakan Data Penyewa Tersedia</b></label>
                                <select class="form-select float-end select2" id="exists_renter">
                                    <option value="">-- Pilih Penyewa --</option>
                                    <option value="0">Buat Penyewa Baru</option>
                                    @foreach($renter_list as $r)
                                    <option value="{{ $r->renter_id }}">{{ $r->renter_name }}</option>
                                    @endforeach
                                </select>
                            </div>   
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-inline row">
                                    <label for="renter_name" class="col-md-3 col-form-label text-wrap"><b>Nama</b></label>
                                    <div class="col-md-9 p-0">
                                        <input type="text" class="form-control input-full" value="{{ old('renter_name') }}" name="renter_name" id="renter_name" required>
                                        <div class="invalid-feedback">Nama harus diisi</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="renter_identity" class="col-md-3 col-form-label text-wrap"><b>NIK</b></label>
                                    <div class="col-md-9 p-0">
                                        <input type="text" class="form-control input-full" value="{{ old('renter_identity') }}" name="renter_identity" id="renter_identity" required>
                                        <div class="invalid-feedback">NIK harus diisi</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="renter_job" class="col-md-3 col-form-label text-wrap"><b>Pekerjaan</b></label>
                                    <div class="col-md-9 p-0">
                                        <input type="text" class="form-control input-full" value="{{ old('renter_job') }}" name="renter_job" id="renter_job" required>
                                        <div class="invalid-feedback">Pekerjaan harus diisi</div>
                                    </div>
                                </div>                            
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-inline row">
                                    <label for="renter_phone" class="col-md-3 col-form-label text-wrap"><b>No. Telp / WA</b></label>
                                    <div class="col-md-9 p-0">
                                        <input type="number" class="form-control input-full" value="{{ old('renter_phone') }}" name="renter_phone" id="renter_phone" required>
                                        <div class="invalid-feedback">No. Telp / WA harus diisi</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="renter_address" class="col-md-3 col-form-label text-wrap"><b>Alamat</b></label>
                                    <div class="col-md-9 p-0">
                                        <textarea class="form-control"  name="renter_address" id="renter_address" required>{{ old('renter_address') }}</textarea>
                                        <div class="invalid-feedback">Alamat harus diisi</div>    
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="renter_identity_file" class="col-md-3 col-form-label text-wrap"><b>Foto KTP</b></label>
                                    <div class="col-md-9 p-0">
                                        <input type="file" class="form-control input-full"  name="renter_identity_file" id="renter_identity_file" required>
                                        <div class="invalid-feedback">Foto KTP harus diisi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h4>Detail Penyewaan</h4>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <h4>Total Biaya : Rp <span class="total_price" id="total_price_top"></span></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-inline row">
                                    <label class="form-label col-md-4 col-form-label"><b>Durasi Sewa</b></label>
                                    <div class="selectgroup col-md-8 p-0">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="rent_duration" value="2 Minggu" class="selectgroup-input" required>
                                            <span class="selectgroup-button">2 Minggu</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="rent_duration" value="Per Bulan" class="selectgroup-input" checked required>
                                            <span class="selectgroup-button">Per Bulan</span>
                                        </label>
                                        <div class="invalid-feedback">Durasi harus dipilih</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row" id="total_duration">
                                    <label for="rent_total_duration" class="col-md-4 col-form-label text-wrap"><b>Jumlah Bulan</b></label>
                                    <div class="col-md-8 p-0">
                                        <div class="input-group">
                                            <input type="number" min="1" value="1" class="form-control" value="{{ old('rent_total_duration') }}" name="rent_total_duration" id="rent_total_duration" required>
                                            <span class="input-group-text">Bulan</span>
                                            <div class="invalid-feedback">Jumlah Bulan harus diisi</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="rent_start_date" class="col-md-4 col-form-label text-wrap"><b>Tanggal Mulai Sewa</b></label>
                                    <div class="col-md-8 p-0">
                                        <input type="date" class="form-control" value="{{ old('rent_start_date') }}" name="rent_start_date" id="rent_start_date" required> 
                                        <div class="invalid-feedback">Tanggal mulai sewa harus diisi</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="rent_end_date" class="col-md-4 col-form-label text-wrap"><b>Tanggal Selesai Sewa</b></label>
                                    <div class="col-md-8 p-0">
                                        <input type="date" class="form-control" value="{{ old('rent_end_date') }}" name="rent_end_date" id="rent_end_date" required>
                                        <div class="invalid-feedback">Tanggal selesai sewa harus diisi</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-inline row">
                                    <label for="rent_project_name" class="col-md-4 col-form-label text-wrap"><b>Nama Proyek</b></label>
                                    <div class="col-md-8 p-0">
                                        <input type="text" class="form-control input-full" value="{{ old('rent_project_name') }}" name="rent_project_name" id="rent_project_name" required>
                                        <div class="invalid-feedback">Nama Proyek</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="rent_project_address" class="col-md-4 col-form-label text-wrap"><b>Alamat Proyek</b></label>
                                    <div class="col-md-8 p-0">
                                        <textarea class="form-control"  name="rent_project_address" id="rent_project_address" required>{{ old('rent_project_address') }}</textarea>
                                        <div class="invalid-feedback">Alamat proyek harus diisi</div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="rent_project_phone" class="col-md-4 col-form-label text-wrap"><b>No. Telp Petugas Proyek</b></label>
                                    <div class="col-md-8 p-0">
                                        <input type="number" class="form-control input-full" value="{{ old('rent_project_phone') }}" name="rent_project_phone" id="rent_project_phone" required>
                                        <div class="invalid-feedback">No. Telp petugas proyek harus diisi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-3 col-form-label text-wrap"><b>Pilih Set / Item</b></label>
                                <div class="form-group form-inline row">
                                    <div class="col-md-7 p-0 me-2">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Set / Item</span>
                                            <select class="select2 form-control" id="item_set"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 p-0 me-2">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Jumlah</span>
                                            <input type="text"  class="form-control form-control-sm currency" id="quantity">
                                            <small class="text-danger" id="stock_excess" style="display: none;">Melebihi Stok</small>
                                        </div>
                                    </div>
                                    <div class="col-md-1 p-0">
                                        <div class="input-group mb-3 d-grid gap-2">
                                            <div class="btn btn-primary btn-sm" id="add_item_set">Tambah</div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive p-0">
                                        <table class="table table-stripped table-bordered" id="item_set_selected">
                                            <thead class="table-primary">
                                                <th>Item / Set</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan / <span id="durationText"></span></th>
                                                <th>Subtotal</th>
                                                <th>Hapus</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                                <div class="form-group form-inline row">
                                    <label for="rent_transport_price" class="col-md-4 col-form-label text-wrap"><b>Biaya Transport</b></label>
                                    <div class="col-md-7 p-0 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control currency" value="0" name="rent_transport_price" id="rent_transport_price">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="rent_deposit" class="col-md-4 col-form-label text-wrap"><b>Deposit</b></label>
                                    <div class="col-md-7 p-0 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control currency" value="0" name="rent_deposit" id="rent_deposit">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-inline row">
                                    <label for="rent_discount" class="col-md-4 col-form-label text-wrap"><b>Discount</b></label>
                                    <div class="col-md-7 p-0 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control currency" value="0" name="rent_discount" id="rent_discount">
                                        </div>
                                    </div>
                                </div>          
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h4 class="float-end">Total Biaya : Rp <span class="total_price" id="total_price_bottom"></span></h4>
                    </div>
                </div>
                <a class="btn btn-dark float-start" href="/sewa/draft">Kembali</a>
                <button class="btn btn-success float-end" id="submit">Buat Draft Sewa</button>
            </form> 
           
        </div>
        
    </div>
</div>
@endsection
@section('script')
<script>
    
    const set = jQuery.parseJSON('@php echo $set @endphp');
    const item = jQuery.parseJSON('@php echo $item @endphp');
    const itemSet = jQuery.parseJSON('@php echo $item_set @endphp');
    
    var setSelected = {};
    var itemSelected = {};
    var itemOptionalSelected = {};
    var totalPrice = 0;

    $(document).ready(function(){
        setDurationText();
        countSetStock();
        renderItemSet();
        countTotalPrice();
    });

    $('#exists_renter').change(function(){
        var renterId = this.value;
        if(renterId == 0){
            location.href = '/sewa/draft/input';
        }else{
            location.href = '/sewa/draft/penyewa/'+renterId;
        }
    });

    $('#add_item_set').click(function(){
        var itemSetId = $('#item_set').val();
        var itemSetIdSplit = itemSetId.split('_');
        var type = itemSetIdSplit[0];
        var id = itemSetIdSplit[1];
        var quantity = $('#quantity').val().replace(/[&\/\\#, +()$~%.'":*?<>{}]/g, '');

        if(type == "set"){
            var stock = set[id]['stock'];
        }else{
            var stock = item[id]['stock'];
        }

        if(id!="0" && quantity!="" && quantity!=0){
            if(quantity > stock){
                $('#stock_excess').css('display', '');
            }else{
                $('#stock_excess').css('display', 'none');
                addSelectedItemSet(type, id, quantity);
                renderSelectedItemSet();
                renderItemSet();
            }
        }
    });

    $('input[name="rent_duration"]').change(function(){
        var duration = this.value;
        if(duration == '2 Minggu'){
            $('#total_duration').css('display', 'none')
            $('#rent_total_duration').prop('readOnly', true)
            $('#rent_total_duration').prop('required', false)
        }else{
            $('#total_duration').css('display', '')
            $('#rent_total_duration').prop('readOnly', false)
            $('#rent_total_duration').prop('required', true)
        }
        setDurationText();
        resetPriceItemSet();
        renderSelectedItemSet();
        setRentEndDate();
    });

    $('#rent_total_duration').keyup(function(){
        setDurationText();
        resetPriceItemSet();
        renderSelectedItemSet();
        setRentEndDate();
    });

    $('#rent_start_date').change(function(){
        setRentEndDate();
    });

    $('#rent_transport_price').keyup(function(){
        if(this.value == ""){
            this.value = 0;
        }
        countTotalPrice();
    });

    $('#rent_deposit').keyup(function(){
        if(this.value == ""){
            this.value = 0;
        }
        countTotalPrice();
    });

    $('#rent_discount').keyup(function(){
        if(this.value == ""){
            this.value = 0;
        }
        countTotalPrice();
    });

    $("#form").submit(function(e){
        $(this).prop('disabled', true);
        var itemSetCount = $('input[name="item_set[]"]').length;
        var startDate = new Date($('#rent_start_date').val());
        var endDate = new Date($('#rent_end_date').val());
        var dateDiff = endDate - startDate;

        if(itemSetCount == 0){
            e.preventDefault();
            showAlert("Draft Sewa Tidak Valid", "Item atau Set belum ditambahkan", "error");
        }

        if(dateDiff <= 0){
            e.preventDefault();
            showAlert("Draft Sewa Tidak Valid", "Tanggal mulai dan selesai sewa tidak valid", "error");
        }
        
        if(totalPrice < 0){
            e.preventDefault();
            showAlert("Draft Sewa Tidak Valid", "Total biaya sewa tidak valid", "error");
            $('#rent_discount').focus;
        }
        $(this).prop('disabled', false);
    });

    function renderItemSet(){
        $('#item_set').empty();
        $('#item_set').append('<option value="">-- Pilih Set atau Item --</option>');
        $('#item_set').append('<optgroup label="Set Scaffolding">')
        for(var s in set){
            var option = "";
            if(s in setSelected){
                option = '<option data-color="#8abdff" value="set_'+s+'" disabled>(SET) '+set[s]['name']+' ('+set[s]['stock']+')</option>'
            }else{
                option = '<option data-color="#007bff" value="set_'+s+'" >(SET) '+set[s]['name']+' ('+set[s]['stock']+')</option>'
            }
            $('#item_set').append(option);
        }
        $('#item_set').append('</optgroup>');
        $('#item_set').append('<optgroup label="Item Scaffolding">')
        for(var i in item){
            var option = "";
            if(i in itemSelected){
                option = '<option data-color="#fc9aa3" value="item_'+i+'" disabled>(ITEM) '+item[i]['name']+' ('+item[i]['stock']+')</option>'
            }else{
                option = '<option data-color="#dc3545" value="item_'+i+'" >(ITEM) '+item[i]['name']+' ('+item[i]['stock']+')</option>'
            }
            $('#item_set').append(option);
        }
        $('#item_set').append('</optgroup>');
        $('.select2').select2({
            theme: 'bootstrap-5',
            templateResult: function (state) {
                if (!state.id) return state.text;

                const color = $(state.element).data('color');
                const $state = $(`
                    <span style="color: ${color}; font-weight: bold;">
                    ${state.text}
                    </span>
                `);

                return $state;
            },
            templateSelection: function (state) {
                if (!state.id) return state.text;

                const color = $(state.element).data('color');
                return $(`<span style="color: ${color}; font-weight: bold;">${state.text}</span>`);
            }
        });
    }

    function addSelectedItemSet(type, id, quantity){
        var duration = $('input[name="rent_duration"]:checked').val();
        var totalDuration = $('#rent_total_duration').val();
        if(type == 'set'){
            setSelected[id] = [];
            setSelected[id]['quantity'] = quantity;

            if(duration == "2 Minggu"){
               setSelected[id]['price'] = set[id]['price_2_weeks'];
            }else{
                setSelected[id]['price'] = set[id]['price_per_month'] * totalDuration;
            }

            for(is in itemSet[id]){
                if(item[is]['stock'] == 0){
                    if(itemSet[id][is]['optional'] == 1){
                        quantity = 0;
                        if (!itemOptionalSelected.hasOwnProperty(id)) {
                            itemOptionalSelected[id] = [];
                        }
                        itemOptionalSelected[id][is] = 0;
                    }
                }else{
                    if(itemSet[id][is]['optional'] == 1){
                        var itemOptionalQuantity = quantity*itemSet[id][is]['quantity']
                        if(item[is]['stock'] < itemOptionalQuantity){
                            itemOptionalQuantity = item[is]['stock'];
                        }
                        if (!itemOptionalSelected.hasOwnProperty(id)) {
                            itemOptionalSelected[id] = [];
                        }
                        itemOptionalSelected[id][is] = itemOptionalQuantity;
                        item[is]['stock'] = item[is]['stock'] - itemOptionalQuantity;
                    }else{
                        var itemSetQuantity = quantity*itemSet[id][is]['quantity']
                        item[is]['stock'] = item[is]['stock'] - itemSetQuantity;
                    }
                }
            }
        }else if(type == 'item'){
            itemSelected[id] = [];
            itemSelected[id]['quantity'] = quantity;
            item[id]['stock'] = item[id]['stock'] - quantity; 
            if(duration == "2 Minggu"){
                itemSelected[id]['price'] = item[id]['price_2_weeks'];
            }else{
                itemSelected[id]['price'] = item[id]['price_per_month'] * totalDuration;
            }

        }
        countTotalPrice();
        countSetStock();
    }

    function countSetStock(){
        for(s in set){
            var stockItem = [];
            for(is in itemSet[s]){
                if(itemSet[s][is]['optional'] == 0){
                    var available = item[is]['stock'] / itemSet[s][is]['quantity'];
                    stockItem.push(Math.floor(available));
                }
            }

            if(stockItem.length == 0){
                for(is in itemSet[s]){
                    var available = item[is]['stock'] / itemSet[s][is]['quantity'];
                    stockItem.push(Math.floor(available));
                }
            }
            
            stockItem.sort(function(a, b){return a - b});
            set[s]['stock'] = stockItem[0];
        }
    }

    function removeSelectedItemSet(type, id){
        if(type == 'set'){
            for(is in itemSet[id]){
                if(itemSet[id][is]['optional'] == 0){
                    item[is]['stock'] = item[is]['stock'] + (parseInt(setSelected[id]['quantity']) * itemSet[id][is]['quantity']);  
                }else{
                    item[is]['stock'] = item[is]['stock'] + (parseInt(itemOptionalSelected[id][is]));
                }
            }
            for(is in itemOptionalSelected[id]){
                delete(itemOptionalSelected[id]);
            }
            delete setSelected[id]
        }else{
            item[id]['stock'] = item[id]['stock'] + parseInt(itemSelected[id]['quantity']);
            delete itemSelected[id]
        }
        countSetStock();
        renderSelectedItemSet();
        renderItemSet();
        countTotalPrice();
    }

    function renderSelectedItemSet(){
        $('#item_set_selected tbody').empty();
        
        var price = 0;
        var totalPrice = 0;
        for(var s in setSelected){
            price = setSelected[s]['price'];
            totalPrice = setSelected[s]['quantity'] * setSelected[s]['price'];
            var max = set[s]['stock']+parseInt(setSelected[s]['quantity']);
            var haveOptional = "";
            for(is in itemSet[s]){
                if(itemSet[s][is]['optional'] == 1){
                    haveOptional = "rowspan=2";
                }
            }
            $('#item_set_selected tbody').append(
                '<tr id="set_'+s+'">'+
                    '<td width="30%">'+
                        '<span class="badge bg-primary">SET</span><br>'+
                        '<input type="hidden" name="item_set[]" value="set_'+s+'">'+set[s]['name']+
                    '</td>'+
                    '<td>'+
                        '<input type="hidden" name="quantity[]" value="'+setSelected[s]['quantity']+'">'+
                        '<span class="price">'+setSelected[s]['quantity']+'</span> Set'+
                    '</td>'+
                    '<td '+haveOptional+'>'+
                        '<div class="input-group mb-3">'+
                            '<a class="input-group-text">Rp</a>'+
                            '<input type="text" class="form-control price" name="price[]" value="'+price+'" onkeyup="changePriceItemSet(`set`, '+s+', this.value)" required>'+
                        '</div>'+
                    '</td>'+
                    '<td '+haveOptional+'>'+
                        'Rp <span class="set_price_'+s+'" id="set_price_'+s+'">'+totalPrice+'</span>'+
                    '</td>'+
                    '<td '+haveOptional+'>'+
                        '<button class="btn btn-sm btn-danger" onclick="removeSelectedItemSet(`set`,'+s+')">Hapus</button>'+
                    '</td>'+
                '</tr>'
            );

            formatingNumber('set_price_'+s);

            for(is in itemSet[s]){
                var quantityInput = "";
                if(itemSet[s][is]['optional'] == 1){
                    var quantity = setSelected[s]['quantity'] * itemSet[s][is]['quantity'];
                    if(is in itemOptionalSelected[s]){
                        quantity = itemOptionalSelected[s][is];
                    }
        
                    $('#item_set_selected tbody').append(
                        '<tr id="item_'+is+'">'+
                            '<td>'+
                                '<span class="badge bg-success">ITEM SET OPTIONAL</span><br>'+
                                item[is]['name']+
                            '</td>'+
                            '<td>'+
                                '<div class="input-group mb-3">'+
                                    '<input type="hidden" name="item_set_optional[]" value="'+s+"_"+is+"_"+quantity+'">'+
                                    '<input type="text" min="1" max="'+parseInt([is]['stock']+itemOptionalSelected[s][is])+'" class="form-control currency" onkeyup="changeQuantityItemOptional(`'+s+"_"+is+'`, this.value)" id="'+s+"_"+is+'" value="'+quantity+'" required>'+
                                    '<a class="input-group-text">'+item[is]['unit']+'</a>'+
                                '</div>'+
                                '<class="invalid-feedback">Maksimal '+parseInt(item[is]['stock']+itemOptionalSelected[s][is])+'</div>'+
                            '</td>'+
                        '</tr>'
                    );
                }
            }
        }

        for(var i in itemSelected){
            price = itemSelected[i]['price'];
            totalPrice = itemSelected[i]['quantity'] * itemSelected[i]['price'];
            var max = item[i]['stock']+parseInt(itemSelected[i]['quantity']);
            $('#item_set_selected tbody').append(
                '<tr id="item_'+i+'">'+
                    '<td width="30%">'+
                        '<input type="hidden" name="item_set[]" value="item_'+i+'">'+item[i]['name']+
                        '<br><span class="badge bg-danger">ITEM</span>'+
                    '</td>'+
                    '<td>'+
                    '<input type="hidden" name="quantity[]" value="'+itemSelected[i]['quantity']+'">'+
                        '<br><span class="price">'+itemSelected[i]['quantity']+'</span> '+item[i]['unit']+
                    '</td>'+
                    '<td>'+
                       '<div class="input-group mb-3">'+
                            '<a class="input-group-text">Rp</a>'+
                            '<input type="text" class="form-control price" name="price[]" value="'+price+'" onkeyup="changePriceItemSet(`item`, '+i+', this.value)" required>'+
                        '</div>'+
                    '</td>'+
                    '<td>'+
                        'Rp <span class="item_price_'+i+'" id="item_price_'+i+'">'+totalPrice+'</span>'+
                    '</td>'+
                    '<td>'+
                        '<button class="btn btn-sm btn-danger" onclick="removeSelectedItemSet(`item`,'+i+')">Hapus</button>'+
                    '</td>'+
                '</tr>'
            );
            formatingNumber('item_price_'+i);
        }
        formatingNumber('price');
        countTotalPrice();
    }

    function changePriceItemSet(type, id, value){
        var duration = $('input[name="rent_duration"]:checked').val();
        var totalDuration = $('#rent_total_duration').val();
        var price = parseInt(value.replace(/[&\/\\#, +()$~%.'":*?<>{}]/g, ''));
        if(type == "set"){
            subtotal = price * setSelected[id]['quantity'];
            console.log(subtotal);
            setSelected[id]['price'] = price;
            $('#set_price_'+id).text(subtotal);
            formatingNumber('set_price_'+id);
        }else{
            subtotal = price * itemSelected[id]['quantity'];
            itemSelected[id]['price'] = price;
            $('#item_price_'+id).text(subtotal);
            formatingNumber('item_price_'+id);
        }
        
        countTotalPrice();
    }

    function resetPriceItemSet(){
        var duration = $('input[name="rent_duration"]:checked').val();
        var totalDuration = $('#rent_total_duration').val();
        for(var s in setSelected){
            if(duration == "2 Minggu"){
                setSelected[s]['price'] = set[s]['price_2_weeks'];
            }else{
                setSelected[s]['price'] = set[s]['price_per_month'] * totalDuration;
            }
        }
        for(var i in itemSelected){
            if(duration == "2 Minggu"){
                itemSelected[i]['price'] = item[i]['price_2_weeks'];
            }else{
                itemSelected[i]['price'] = item[i]['price_per_month'] * totalDuration;
            }
        }
    }

    function changeQuantityItemOptional(id, value){
        var idSplit = id.split('_');
        var setId = idSplit[0];
        var itemId = idSplit[1];
        var quantity = parseInt(value.replace(/[&\/\\#, +()$~%.'":*?<>{}]/g, ''));
        var quantityPrevious =  parseInt(itemOptionalSelected[setId][itemId]);

        if(value == 0 || value == ""){
            quantity = 0;
        }

        if(quantity > item[itemId]['stock']+quantityPrevious){
            $('#'+id).val(item[itemId]['stock']+quantityPrevious);
            quantity = item[itemId]['stock']+quantityPrevious;
        }else if(quantity <= item[itemId]['stock']+quantityPrevious){
            quantity = quantity;
        }

        var stock = item[itemId]['stock'] + quantityPrevious - quantity;
        item[itemId]['stock'] = stock;
        itemOptionalSelected[setId][itemId] = quantity;
        renderItemSet();
        renderSelectedItemSet();
        $('#'+id).focus();
        $('#'+id)[0].setSelectionRange($('#'+id)[0].value.length, $('#'+id)[0].value.length);

    }


    function countTotalPrice(){
        $('#total_price_row').remove();
        var transport = parseInt(unformatingNumber($('#rent_transport_price').val())); 
        var deposit = parseInt(unformatingNumber($('#rent_deposit').val()));
        var discount = parseInt(unformatingNumber($('#rent_discount').val()));
        var subtotal = 0;
        $('table tbody tr td:nth-child(4)').each(function() {
            var stringPrice = $(this).text();
            stringPrice = stringPrice.split(" ");
            var price = unformatingNumber(stringPrice[1]);
            subtotal += parseInt(price);
        });
        totalPrice = transport+deposit+subtotal-discount;
        $('#total_price_top').text(totalPrice);
        $('#total_price_bottom').text(totalPrice);
        $('#item_set_selected').append(
            '<tr class="table-danger" id="total_price_row">'+
                '<td colspan=3><b><h5>Total Biaya Sewa</h5></b></td>'+
                '<td colspan=2><b><h5>Rp <span class="subtotal_price">'+subtotal+'</span></h5></b></td>'+
            '</tr>'
        );
        formatingNumber('total_price');
        formatingNumber('subtotal_price');
        
    }

    function setRentEndDate(){
        var duration = $('input[name="rent_duration"]:checked').val();
        var startDate = $('#rent_start_date').val();
        const date = new Date(startDate);
        if(duration == "2 Minggu"){
            date.setDate(date.getDate() + 14);
            var month = "0" + (date.getMonth()+1);
            var day = "0" + (date.getDate());
            $('#rent_end_date').val(date.getFullYear()+'-'+month.slice(-2)+'-'+day.slice(-2));
        }else{
            var totalMonth = parseInt($('#rent_total_duration').val());
            var day = "0" + (date.getDate());
            date.setMonth(date.getMonth()+totalMonth);
            var month = "0" + (date.getMonth()+1)
            $('#rent_end_date').val(date.getFullYear()+'-'+month.slice(-2)+'-'+day.slice(-2));
        }
    }
    
    function setDurationText(){
        var duration = $('input[name="rent_duration"]:checked').val();
        var totalDuration = $('#rent_total_duration').val();
        if(duration == "2 Minggu"){
            $('#durationText').text('2 Minggu');
        }else{
            $('#durationText').text(totalDuration + ' Bulan');
        }
    }

</script>
@endsection
