@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Ubah Draft Pengeluaran</h3>
            <span class="text-muted">ID Draft Pengeluaran : {{ HID::genId($expense->expense_id) }}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/keuangan/pengeluaran/draft/update/{{ $expense->expense_id }}" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="expense_date" class="col-md-3 col-form-label text-wrap"><b>Tanggal Pengeluaran</b></label>
                            <div class="col-md-9 p-0">
                                <input type="date" class="form-control input-full" value="{{ $expense->expense_date }}" name="expense_date" id="expense_date" required>
                                <div class="invalid-feedback">Tanggal pengeluaran harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="expense_category" class="col-md-3 col-form-label text-wrap"><b>Kategori Pengeluaran</b></label>
                            <div class="col-md-9 p-0">
                                <select class="form-select input-full" name="expense_category" id="expense_category" required>
                                    <option value="">--Pilih Kategori--</option>
                                    <option value="Operasional" {{ $expense->expense_category == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                    <option value="Non Operasional" {{ $expense->expense_category == 'Non Operasional' ? 'selected' : '' }}>Non Operasional</option>
                                </select>
                                <div class="invalid-feedback">Kategori pengeluaran harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="expense_description" class="col-md-3 col-form-label text-wrap"><b>Deskripsi Pengeluaran</b></label>
                            <div class="col-md-9 p-0">
                                 <textarea class="form-control" name="expense_description" id="expense_description" required>{{ $expense->expense_description }}</textarea>
                                <div class="invalid-feedback">Deskripsi pengeluaran harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="expense_amount" class="col-md-3 col-form-label text-wrap"><b>Jumlah Pengeluaran</b></label>
                            <div class="col-md-9 p-0">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control currency" name="expense_amount" id="expense_amount" value="{{ $expense->expense_amount }}" required>
                                    <div class="invalid-feedback">Jumlah pengeluaran harus diisi</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="expense_receipt_file" class="col-md-3 col-form-label text-wrap"><b>Bukti Pengeluaran</b></label>
                            <div class="col-md-9 p-0">
                                 <div class="input-group mb-3">
                                    <input type="file" class="form-control create-input" name="expense_file_file" id="expense_file_file"">
                                    <a class="input-group-text" target="_blank" href="{{ asset('storage/'.$expense->expense_file)}}">Lihat Bukti</a>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-action">
                    <a href="/keuangan/pengeluaran" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    <button class="btn btn-success float-end" name="submit"><span class="icon-check"></span> Simpan</button>
                    </form>
                  </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

