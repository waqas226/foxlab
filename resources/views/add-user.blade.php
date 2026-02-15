@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Manage User')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />



@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>

<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>

<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/add-user.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Manage Users /</span> {{(isset($id))?'Update':'Add'}} User
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
<!-- Users List Table -->
<div class="card">
 
    <h5 class="card-header">{{(isset($id))?'Update':'Add'}} User</h5>

    <div class="card-body px-5">
 
      <form class="add-new-user pt-0" id="addNewUserForm" onsubmit="return false">
        @csrf
        @if(isset($user))
        <input type="hidden" name="user_id" value="{{$user->id}}">
        @endif
        <div class="mb-3">
          <label class="form-label" for="add-user-firstname">First Name</label>
          <input type="text" class="form-control" id="add-user-firstname" placeholder="First Name" name="firstName" required
          @if(isset($user)) value="{{$user->firstname}}" @endif
          />
        </div>
        <div class="mb-3">
          <label class="form-label" for="add-user-lastname">Last Name</label>
          <input type="text" class="form-control" id="add-user-lastname" placeholder="Last Name" name="lastName" required
          @if(isset($user)) value="{{$user->lastname}}" @endif
          />
        </div>
        <div class="mb-3">
          <label class="form-label" for="add-user-email">Email</label>
          <input type="email" id="add-user-email" class="form-control" placeholder="Email" name="email" required
          @if(isset($user)) value="{{$user->email}}" @endif
          />
        </div>

        <div class="mb-3">
          <label class="form-label" for="role_id">User Role</label>
          <select class="form-select" id="role_id" name="role_id" required>
            <option value="">Please Select</option>
            @foreach($roles as $role)
              <option value="{{$role->id}}" {{ isset($id) && $user->role_id == $role->id ? 'selected' : '' }}>{{$role->name}}</option>
            @endforeach
          </select>
        </div>
       
        
        <div class="mb-3">
          <label class="form-label" for="username">Username</label>
          <input type="text" id="username" class="form-control" placeholder="Username" name="username" required
          @if(isset($user)) value="{{$user->username}}" @endif
          />
        </div>
        @if(isset($user))
      <div class="mb-3">
        <input type="checkbox" id="change_password" class="form-check-input" name="change_password" value="0">
        <label class="form-check-label" for="change_password">Change Password</label>
      </div>
      <div class="mb-3" id="password_div" style="display:none;">
        <label class="form-label" for="add-user-password">Password</label>
        <input type="password" id="add-user-password" class="form-control" placeholder="Enter password" name="password" />
        <span class="text-muted">Leave blank if you don't want to change password</span>
      </div>
     

        @else
        <div class="mb-3">
          <label class="form-label" for="add-user-password">Password</label>
          <input type="password" id="add-user-password" class="form-control" placeholder="Enter password" name="password" required />
        </div>
        @endif
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <a href="/manage-users" class="btn btn-label-secondary" >Cancel</a>
      </form>
 </div>
</div>
</div>
</div>
@endsection
