@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Buat Data Penyewa</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="/sewa/penyewa/buat" class="needs-validation" id="form" novalidate enctype="multipart/form-data">
                @csrf
                <div class="card">
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
                                        <input type="text" class="form-control input-full" value="{{ old('renter_phone') }}" name="renter_phone" id="renter_phone" required>
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
                    <div class="card-footer">
                        <a class="btn btn-dark float-start" href="/sewa/penyewa">Kembali</a>
                        <button class="btn btn-success float-end" id="submit">Simpan</button>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
@endsection

