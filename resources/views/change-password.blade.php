@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Change Password')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
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
<script src="{{asset('assets/js/change-password.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Account Managment /</span> Change Password
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
    <div class="card">
      <h5 class="card-header">Update Password</h5>
      <div class="card-body px-5">
      <form class="add-new-user pt-0" id="addNewUserForm"  method="post" onsubmit="return false">
          @csrf
          <!-- show error  -->
          @if(isset($errors))
            @foreach($errors->all() as $error)
              <div class="alert alert-danger alert-dismissible" role="alert">
                <strong>Error!</strong> {{$error}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endforeach
          @endif
       <div class="mb-3 col-md-8">
            <label class="form-label" for="current_password">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter Current Password" required />
        </div>
        <div class="mb-3 col-md-8">
            <label class="form-label" for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" required />
          
        </div>
        <div class="mb-3 col-md-8">
            <label class="form-label" for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
        </div>

        <div class="row">
            <div class="col-12 text-center mt-3">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
  <!-- /Bootstrap Validation -->
</div>

@endsection
