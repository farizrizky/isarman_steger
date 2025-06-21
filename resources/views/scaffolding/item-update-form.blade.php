@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Ubah Data Item</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/scaffolding/item/update/{{$item->item_id}}" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="item_name" class="col-md-3 col-form-label text-wrap"><b>Nama Item</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $item->item_name }}" name="item_name" id="item_name" required>
                                <div class="invalid-feedback">Nama item harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="item_unit" class="col-md-3 col-form-label text-wrap"><b>Satuan</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $item->item_unit }}" name="item_unit" id="item_unit" required>
                                <div class="invalid-feedback">Satuan item harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="stock_total" class="col-md-3 col-form-label text-wrap"><b>Stok Pertama</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="currency form-control input-full" value="{{ $stock->stock_total }}" name="stock_total" id="stock_total" required>
                                <div class="invalid-feedback">Stok item harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="item_price_2_weeks" class="col-md-3 col-form-label text-wrap"><b>Biaya Sewa 2 Minggu</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $item->item_price_2_weeks }}" name="item_price_2_weeks" id="item_price_2_weeks" required>
                                    <div class="invalid-feedback">Biaya sewa harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="item_price_per_month" class="col-md-3 col-form-label text-wrap"><b>Biaya Sewa Per Bulan</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $item->item_price_per_month }}" name="item_price_per_month" id="item_price_per_month" required>
                                    <div class="invalid-feedback">Biaya sewa harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="item_fine_damaged" class="col-md-3 col-form-label text-wrap"><b>Denda Kerusakan</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $item->item_fine_damaged }}"  name="item_fine_damaged" id="item_fine_damaged" required>
                                    <div class="invalid-feedback">Denda harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="item_fine_lost" class="col-md-3 col-form-label text-wrap"><b>Denda Kehilangan</b></label>
                            <div class="col-md-9 p-0 ">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $item->item_fine_lost }}" name="item_fine_lost" id="item_fine_lost" required>
                                    <div class="invalid-feedback">Denda harus diisi</div>
                                </div>
                            </div>
                        </div>
                    
                </div>
                <div class="card-action">
                    <a href="/scaffolding/item" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    <button class="btn btn-success float-end" name="submit"><span class="icon-check"></span> Simpan</button>
                    </form>
                  </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
