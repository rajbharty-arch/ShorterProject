@extends('header')
@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
      <div class="col-md-4">

          <h3 class="mb-3">Login</h3>

          <form action = "{{ route('login')}}" method = 'POST' >
              @csrf
              <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" value = '' class="form-control" name="email">
              </div>

              <div class="mb-3">
                  <label class="form-label">Password</label>
                  <input type="password" value = '' class="form-control" name="password">
              </div>

              <button type="submit" class="btn btn-primary">
                  Login
              </button>
          </form>

      </div>
  </div>
</div>

@endsection
