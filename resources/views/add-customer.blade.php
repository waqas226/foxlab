@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Add Customer')

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
<script src="{{asset('assets/js/add-customer.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Manage Customers /</span> {{(isset($id))?'Update':'Add'}} Customer
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
<!-- Users List Table -->
<div class="card">
 
    <h5 class="card-header">{{(isset($id))?'Update':'Add'}} Customer</h5>

    <div class="card-body px-5">
 
    <form class="add-new-customer pt-0" id="addNewCustomerForm" method="POST" action="{{ route('customers.store') }}">
    @csrf
    @if(isset($customer))
        <input type="hidden" name="id" value="{{ $customer->id }}">
    @endif

   
    <div class="mb-3">
        <label class="form-label" for="company">Company</label>
        <input type="text" class="form-control" id="company" name="company" placeholder="company"
            value="{{ old('company', $customer->company ?? '') }}" />
    </div>
    <div class="mb-3">
        <label class="form-label" for="primary_contact">Primary Contact</label>
        <input type="text" class="form-control" id="primary_contact" name="primary_contact" placeholder="Primary Contact" required
            value="{{ old('primary_contact', $customer->primary_contact ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="primary_email">Primary Email</label>
        <input type="email" class="form-control" id="primary_email" name="primary_email" placeholder="Primary Email"
            value="{{ old('primary_email', $customer->primary_email ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="primary_phone">Primary Phone</label>
        <input type="tel" class="form-control" id="primary_phone" name="primary_phone" placeholder="Primary Phone"
            value="{{ old('primary_phone', $customer->primary_phone ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="secondary_contact">Secondary Contact</label>
        <input type="text" class="form-control" id="secondary_contact" name="secondary_contact" placeholder="Secondary Contact"
            value="{{ old('secondary_contact', $customer->secondary_contact ?? '') }}" />
    </div>
    <div class="mb-3">
        <label class="form-label" for="secondary_phone">Secondary Phone</label>
        <input type="tel" class="form-control" id="secondary_phone" name="secondary_phone" placeholder="Secondary Phone"
            value="{{ old('secondary_phone', $customer->secondary_phone ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="secondary_email">Secondary Email</label>
        <input type="email" class="form-control" id="secondary_email" name="secondary_email" placeholder="Secondary Email"
            value="{{ old('secondary_email', $customer->secondary_email ?? '') }}" />
    </div>
    <div class="mb-3">
        <label class="form-label" for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Address"
            value="{{ old('address', $customer->address ?? '') }}" />
    </div>
   

    <div class="mb-3">
        <label class="form-label" for="pm_type">PM Type</label>
       
            <select class="form-control" id="pm_type" name="pm_type" required>
            <option value="6" {{ (old('pm_type', $customer->pm_type ?? '') == '6') ? 'selected' : '' }}>6</option>
            <option value="12" {{ (old('pm_type', $customer->pm_type ?? '') == '12') ? 'selected' : '' }}>12</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-control" id="status" name="status" required>
            <option value="A" {{ (old('status', $customer->status ?? '') == 'A') ? 'selected' : '' }}>Active</option>
            <option value="D" {{ (old('status', $customer->status ?? '') == 'D') ? 'selected' : '' }}>Disabled</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="comment">Comment</label>
        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Enter comment">{{ old('comment', $customer->comment ?? '') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
    <a href="/manage-customers" class="btn btn-label-secondary" >Cancel</a>
</form>

 </div>
</div>
</div>
</div>
@endsection
