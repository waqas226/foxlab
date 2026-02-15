@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Todos List')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
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
<script src="{{asset('assets/js/manage-task.js')}}"></script>
@endsection

@section('content')

<!-- <div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Session</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">21,459</h3>
              <p class="text-success mb-0">(+29%)</p>
            </div>
            <p class="mb-0">Total Users</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="ti ti-user ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Paid Users</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">4,567</h3>
              <p class="text-success mb-0">(+18%)</p>
            </div>
            <p class="mb-0">Last week analytics </p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="ti ti-user-plus ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Active Users</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">19,860</h3>
              <p class="text-danger mb-0">(-14%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="ti ti-user-check ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Pending Users</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2">237</h3>
              <p class="text-success mb-0">(+42%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="ti ti-user-exclamation ti-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
<!-- Users List Table -->
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Home /</span> Manage Open Tasks
</h4>
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-3">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
      <div class="col-md-5 task_company"></div>
      <div class="col-md-5 task_status">
      <!-- <div class="date-filter">
  <label for="dateFrom">From:</label>
  <input type="text" id="dateFrom" class="form-control datepicker" placeholder="YYYY-MM-DD">
  <label for="dateTo">To:</label>
  <input type="text" id="dateTo" class="form-control datepicker" placeholder="YYYY-MM-DD">
</div> -->
      </div>
      <div class="col-md-2 task_filter">
        </div>
    </div>
  </div>
  <div class="card-datatable ">
    <table class="datatables-tasks table">
      <thead class="border-top">
        <tr>
          <th></th>
          <!-- <th>Task ID</th>
          <th>User</th> -->
          <th>Company</th>
          <th style="width:20%" >Description</th>
          <th>Priority</th>
          <th>Date Entered</th>
          <th>Date Last Activity</th>
          <th style="min-width: 100px;">Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add record -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCompany" aria-labelledby="offcanvasAddCompanyLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddCompanyLabel" class="offcanvas-title">Add Company</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
      <form class="add-new-user pt-0" id="addNewCompanyForm" method="POST" onsubmit="return false;">
     
        @csrf
        <div class="mb-3">
          <label class="form-label" for="name">Company Name</label>
          <input type="text" class="form-control" id="name" placeholder="Company Name" name="name" required />
        </div>
       
           
       
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>

   <!-- Offcanvas to update record-->
   <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateCompany" aria-labelledby="offcanvasUpdateCompanyLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasUpdateCompanyLabel" class="offcanvas-title">Update Company</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
      <form class="add-new-user pt-0" id="UpdateCompanyForm" method="POST" onsubmit="return false;">
        <input type="hidden" name="id" id="recordid" value="">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="add-user-company">Company</label>
          <select id="add-user-company" class="form-select" name="company">
        <option value="">Please select</option>
        @foreach($companies as $company)
        <option value="{{$company->id}}">{{$company->name}}</option>
        @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label" for="name">Company Name</label>
          <input type="text" class="form-control" id="recordname" placeholder="Company Name" name="name" required />
        </div>
        <div class="mb-3">
          <label class="form-label" for="name">Due Date</label>
          <input type="date" class="form-control" id="recordname" placeholder="Company Name" name="name" required />
        </div>
       
           
       
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>

</div>


@endsection
