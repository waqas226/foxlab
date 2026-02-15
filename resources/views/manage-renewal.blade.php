@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Manage Todos')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />

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

<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>


<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/manage-renewal.js')}}"></script>
@endsection

@section('content')

<!-- Users List Table -->
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-3">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
      <!-- <div class="col-md-4 user_role"></div> -->
      <div class="col-md-10 todo_company">
      <select id="UserPlan" class="form-select text-capitalize update-company">
        <option value="" >Select Company</option>
        @foreach($companies as $company)
          <option value="{{$company->id}}">{{$company->name}}</option>
        @endforeach
      </select>
      </div>
      <div class="col-md-2 todo_filter"></div>
      <div class="col-md-6 month_filter ms-auto">
      <select id="month_filter" class="form-select select2 text-capitalize mt-2">
          <option value=""  selected>Select Month</option>
                            <option value="1">January {{date('Y')}}</option> 
                            <option value="2">February {{date('Y')}}</option>
                            <option value="3">March {{date('Y')}}</option>
                            <option value="4">April {{date('Y')}}</option>
                            <option value="5">May {{date('Y')}}</option>
                            <option value="6">June {{date('Y')}}</option>
                            <option value="7">July {{date('Y')}}</option>
                            <option value="8">August {{date('Y')}}</option>
                            <option value="9">September {{date('Y')}}</option>
                            <option value="10">October {{date('Y')}}</option>
                            <option value="11">November {{date('Y')}}</option>
                            <option value="12">December {{date('Y')}}</option>
        </select>
    </div>
  </div>
  <div class="card-datatable ">
    <table class="datatables-todo table">
      <thead class="border-top">
        <tr>
          <th></th>
        
          <th style="width:30%">Title</th>
          <th>Company</th>
          <th>Due Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCompany" aria-labelledby="offcanvasAddCompanyLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddCompanyLabel" class="offcanvas-title">Add New Renewal</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
      <form class="add-new-user pt-0" id="addNewTodoForm" method="POST" onsubmit="return false;">
     
        @csrf
        <div class="mb-3">
          <label class="form-label" for="company_id">Company</label>
          <select id="company_id" class="form-select text-capitalize" name="company_id">
            <option value="" >Select Company</option>
            @foreach($companies as $company)
              <option value="{{$company->id}}">{{$company->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label" for="title">Renewal</label>
          <input type="text" class="form-control" id="title" placeholder="Renewal" name="title" required />
        </div>
       
        <div class="mb-3">
          <label class="form-label" for="renewal_date">Due Date</label>
          <input type="text" class="form-control flatpickr-validation" id="renewal_date" placeholder="YYYY-MM-DD" name="renewal_date" required />
        </div>
           
       
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>


@endsection
