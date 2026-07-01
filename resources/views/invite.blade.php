@extends('header')
@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
      <div class="col-md-4">

          <h3 class="mb-3">Invite</h3>

          <form action = "{{ route('addInvite')}}" method = 'POST' >
              @csrf
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" value = '' class="form-control" name="name">
            </div>
              <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" value = '' class="form-control" name="email">
              </div>
              @if (session('type') != 'superAdmin')
                <div class="mb-3">
                 <label class="form-label">Role</label>
                  <select class="form-control" name = "role">
                     <option value = 'admin'>Admin</option>
                     <option value = 'user'>User</option>
                  </select>
            </div>
              @elseif(session('type') == 'superAdmin')
              <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" value = '' class="form-control" name="company_name">
            </div>

              @endif
            <button type="submit" class="btn btn-primary">
                  Login
              </button>
          </form>

      </div>
  </div>
</div>

@endsection
