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
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/manage-user.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
 Manage Users
</h4>
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
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-3">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
      <!-- <div class="col-md-4 user_role"></div> -->
      <div class="col-md-5 user_plan">
      <!-- <select id="UserPlan" class="form-select text-capitalize update-company"><option value="Select Company"> Select Company </option>
   <option value="">All</option>
      @foreach($companies as $company)
      <option value="{{$company->name}}">{{$company->name}}</option>
    @endforeach

    </select> -->
      </div>
      <div class="col-md-10 user_status"></div>
      <div class="col-md-2 todo_filter"></div>
    </div>
  </div>
  <div class="card-datatable ">
    <table class="datatables-users table">
      <thead class="border-top">
        <tr>
          <th></th>
          
          <th>User</th>
         
          <th>Email</th>
        <th>Type</th>
          <th>Date Added</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new user -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
      <form class="add-new-user pt-0" id="addNewUserForm" onsubmit="return false">
        <div class="mb-3">
          <label class="form-label" for="add-user-firstname">First Name</label>
          <input type="text" class="form-control" id="add-user-firstname" placeholder="First Name" name="firstName" required />
        </div>
        <div class="mb-3">
          <label class="form-label" for="add-user-lastname">Last Name</label>
          <input type="text" class="form-control" id="add-user-lastname" placeholder="Last Name" name="lastName" required />
        </div>
        <div class="mb-3">
          <label class="form-label" for="add-user-email">Email</label>
          <input type="email" id="add-user-email" class="form-control" placeholder="Email" name="email" required />
        </div>
        <div class="mb-3">
          <label class="form-label" for="add-user-additional-emails">Additional Emails</label>
          <table class="form-table newcustomEmails" id="customEmails" width="100%">
        <tr valign="top">
          <td>
            <input type="email" class="form-control add_emails_left" name="additional_emails[]" placeholder="Additional Email" />
            <a href="javascript:void(0);" class="addCF btn btn-xs btn-primary">Add More</a>
          </td>
        </tr>
          </table>
        </div>
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
          <label class="form-label" for="add-user-username">Username</label>
          <input type="text" id="add-user-username" class="form-control" placeholder="Username" name="username" required />
        </div>
        <div class="mb-3">
          <label class="form-label" for="add-user-password">Password</label>
          <input type="password" id="add-user-password" class="form-control" placeholder="Password" name="password" required />
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>


@endsection
