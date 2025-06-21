@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Ubah Data User</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/user/update/{{ $user->id }}" id="form" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group form-inline row">
                            <label for="name" class="col-md-3 col-form-label text-wrap"><b>Username</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $user->name }}" name="name" id="name" required>
                                <div class="invalid-feedback">Username harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="fullname" class="col-md-3 col-form-label text-wrap"><b>Nama Lengkap</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $user->fullname }}" name="fullname" id="fullname" required>
                                <div class="invalid-feedback">Nama lengkap harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="phone" class="col-md-3 col-form-label text-wrap"><b>Telepon</b></label>
                            <div class="col-md-9 p-0">
                                <input type="text" class="form-control input-full" value="{{ $user->phone }}" name="phone" id="phone" required>
                                <div class="invalid-feedback">Nomor telepon harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="email" class="col-md-3 col-form-label text-wrap"><b>Email</b></label>
                            <div class="col-md-9 p-0">
                                <input type="email" class="form-control input-full" value="{{ $user->email }}" name="email" id="email" required>
                                <div class="invalid-feedback">Email harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="password" class="col-md-3 col-form-label text-wrap"><b>Password</b></label>
                            <div class="col-md-9 p-0">
                                <input type="password" class="form-control input-full" name="password" id="password">
                                <div class="invalid-feedback">Password harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="password_confirmation" class="col-md-3 col-form-label text-wrap"><b>Konfirmasi Password</b></label>
                            <div class="col-md-9 p-0">
                                <input type="password" class="form-control input-full" name="password_confirmation" id="password_confirmation">
                                <div class="invalid-feedback">Konfirmasi password harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group form-inline row">
                            <label for="role" class="col-md-3 col-form-label text-wrap"><b>Level</b></label>
                            <div class="col-md-9 p-0">
                                <select class="form-select input-full" name="role" id="role" required>
                                    @foreach ($role as $r)
                                        <option value="{{ $r->name }}" {{ $user->roles->first()->id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Level harus dipilih</div>
                            </div>
                        </div>
                        <!-- User active-->
                        <div class="form-group form-inline row">
                            <label for="active" class="col-md-3 col-form-label text-wrap"><b>Aktif</b></label>
                            <div class="col-md-9 p-0">
                                <select class="form-select input-full" name="is_active" id="active" required>
                                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>Tidak</option>
                                </select>
                                <div class="invalid-feedback">Status aktif harus dipilih</div>
                            </div>
                        </div>
                </div>
                <div class="card-action">
                    <a href="/user" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    <button class="btn btn-success float-end" name="submit" id="submit"><span class="icon-check"></span> Simpan</button>
                    </form>
                  </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('script')
<script>

   $('#form').submit(function(e){
        var password = $('#password').val();
        var passwordConfirmation = $('#password_confirmation').val();
        console.log(password, passwordConfirmation);
        if (password !== passwordConfirmation) {
            e.preventDefault();
            showAlert('Pastikan password dan konfirmasi password sama.', '', 'error');
        } 
    });
</script>
@endsection

