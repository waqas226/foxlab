@php
$customizerHidden = 'customizer-hide';
$container = 'container-fluid';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Manage Customers')

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
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></scrip>
<script src="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>

<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/manage-customers.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
 Manage Customers
</h4>

@if(session('success') )
<div class="alert alert-success alert-dismissible" role="alert">
 {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('failed'))
<div class="alert alert-danger alert-dismissible" role="alert">
{{ count(session('failed')) }} Record(s) failed to import.Click <a class="view-details" href="javascript::" data-bs-toggle="modal" data-bs-target="#errorModal" >here</a> to view the failed records.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


@if($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
  <ul>
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<!-- Users List Table -->
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-3">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
      <!-- <div class="col-md-4 user_role"></div> -->
      <div class="col-md-5 user_role">
    
      </div>
      <div class="col-md-5 user_status"></div>
      <div class="col-md-2 todo_filter"></div>
    </div>
  </div>
  <div class="card-datatable ">
    <table class="datatables-users table">
      <thead class="border-top">
        <tr>
          <th></th>
          <th>Company</th>
         <th>Primary Contact</th>
    <th>Primary Phone</th>
    <th>Primary Email</th>
    <th>Secondary Contact</th><th>Secondary Phone</th><th>Secondary Email</th>
    <th>Address</th><th>PM Type</th><th>Status</th><th>Comment</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new user -->
 
</div>


<!--/ Error List Table -->
<div class="modal-onboarding modal fade animate__animated" id="errorModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content text-center">
              <div class="modal-header border-0">
              <h5 class="modal-title">Failed Records</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body p-0 scrollbar table-responsive">
              <table class="datatables table p-3">
      <thead class="border-top">
        <tr>
          <th></th>
          <th>Company</th>
        <th>Primary Contact</th>
    <th>Primary Phone</th><th>Primary Email</th>
    <th>Secondary Contact</th><th>Secondary Phone</th><th>Secondary Email</th>
    <th>Address</th><th>PM Type</th><th>Status</th><th>Comment</th>
          <th>Error</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0 scrollbar">
        @if(session('failed'))
        @foreach (session('failed') as $failure)
        <?php $error = $failure['row']; ?>
        <tr class="text-center text-danger">
          <td></td>
          <td>{{ $error['company'] ?? '' }}</td>
            <td>{{ $error['primary_contact'] ?? '' }}</td>
            <td>{{ $error['primary'] ?? '' }}</td>
            <td>{{ $error['primary_email'] ?? '' }}</td>
            <td>{{ $error['secondary_contact'] ?? '' }}</td>
            <td>{{ $error['secondary'] ?? '' }}</td>
            <td>{{ $error['secondary_email'] ?? '' }}</td>
            <td>{{ $error['address'] ?? '' }}</td>
            <td>{{ $error['pm_type'] ?? '' }}</td>
            <td>{{ $error['status'] ?? '' }}</td>
            <td>{{ $error['comment'] ?? '' }}</td>
          
          <td class="text-danger">
         {{  implode(', ',$failure['errors']) }}
          </td>
        </tr>
        
        @endforeach
        @endif
        </tbody>
    </table>
               
              </div>
             
            </div>
          </div>
        </div>
<!-- End Error Modal -->


<div class="modal fade" id="enableOTP" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Import Customers List          </h3>
         
        </div>
        <form id="importForm" class="row g-3"  action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
        @csrf  
        <div class="col-12">
            <label class="form-label" for="file">Select Excel/CSV file</label>
            <div class="input-group">
            
              <input type="file" 
              accept=".xlsx, .xls, .csv"
              required
              id="file" name="file" class="form-control" placeholder="Select File" />
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Enable OTP Modal -->

@endsection
