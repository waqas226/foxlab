@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Manage Work Orders')

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
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/manage-work-orders.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  Manage Work Orders 
</h4>

<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-3">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
      <div class="col-md-5 ">
        <select class="form-select" id="workCompany" name="workCompany">
          <option value="">Select Company</option>
          @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->company }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-5 task_status">
    
      </div>
      <div class="col-md-2 task_filter">
        </div>
    </div>
  </div>
  <div class="card-datatable ">
    <table class="datatables-work table">
      <thead class="border-top">
        <tr>
          <th></th>
     
          <th>Company</th>
          <th >Address</th>
          <th>Devices</th>
          <th>Type</th>
          <th>Date Added</th>
          <th style="min-width: 100px;">Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>


@endsection
