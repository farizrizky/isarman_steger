@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Kas Awal</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if(is_null($cash_initial_balance))
                <div class="card-body">
                    <form method="POST" action="/keuangan/kas-awal/simpan" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="item_price_2_weeks" class="col-md-3 col-form-label text-wrap"><b>Saldo Kas Awal</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" name="cash_initial_balance" required>
                                    <div class="invalid-feedback">Nominal kas awal harus diisi</div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-action">
                    <button class="btn btn-success float-end" name="submit"><span class="icon-check"></span> Simpan</button>
                    </form>
                </div>
                @else
                 <div class="card-body">
                    <form method="POST" action="/keuangan/kas-awal/simpan" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="item_price_2_weeks" class="col-md-3 col-form-label text-wrap"><b>Saldo Kas Awal</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" value="{{ $cash_initial_balance }}" name="cash_initial_balance" required>
                                    <div class="invalid-feedback">Nominal kas awal harus diisi</div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-action">
                    <button class="btn btn-success float-end" name="submit"><span class="icon-pencil"></span> Ubah</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection

