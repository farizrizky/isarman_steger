@extends('template.auth')
@section('content')
<div class="card card-round">
    <div class="card-body">
      <form method="POST" action="/auth">
        @csrf
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control mb-3" id="password" name="password" placeholder="Password">
          <a href="#" class="">Lupa Password?</a>
        </div>
    </div>
    <div class="card-action d-grid">
      <button class="btn btn-primary" name="submit">Login</button>
    </div>
    </form>
</div>
@endsection