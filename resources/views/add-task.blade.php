@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Add Tasks')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/typeahead-js/typeahead.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">

<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
<style>
  .todo-table td{
    padding: 0.1rem !important;
  }
  .todo-table th{
    padding: 0.3rem !important;
  }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>

<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>

<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/add-task.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Tasks /</span> {{(isset($id))?'Update':'Add'}} Task
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
    <div class="card">
      <h5 class="card-header">{{(isset($id))?'Update':'Add New'}} Task</h5>
      <div class="card-body px-5">
        <form class="needs-validation" novalidate id="addNewTaskForm" enctype="multipart/form-data" method="POST" >
          @csrf
          @if(isset($id))
          <input type="hidden" name="id" id="task_id" value="{{ isset($id) ? $task->id : '' }}" />
          @endif
        <!-- * Priority: -->
        <div class="mb-3 col-md-8">
          <label class="form-label" for="bs-validation-priority">Priority</label>
          <select class="form-select" id="bs-validation-priority" name="priority" required>
            <option value="">Select Priority</option>
            <option value="Normal" {{ isset($id) && $task->enumToDo == 'Normal' ? 'selected' : '' }}>Normal</option>
            <option value="Urgent" {{ isset($id) && $task->enumToDo == 'Urgent' ? 'selected' : '' }}>Urgent</option>
          </select>
          <div class="valid-feedback"> Looks good! </div>
          <div class="invalid-feedback"> Please select your priority </div>
        </div>

        <div class="mb-3 col-md-8">
          <label class="form-label" for="company_id">Company</label>
          <select class="form-select" id="company_id" name="company_id" required>
            <option value="">Please Select</option>
            @foreach($companies as $company)
              <option value="{{$company->id}}" {{ isset($id) && $task->company_id == $company->id ? 'selected' : '' }}>{{$company->name}}</option>
            @endforeach
          </select>
          <div class="valid-feedback"> Looks good! </div>
          <div class="invalid-feedback"> Please select your company </div>
        </div>

        <div class="mb-3 col-md-8">
          <label class="form-label" for="bs-validation-device">Device Affected</label>
          <input type="text" class="form-control" id="bs-validation-device" name="device_affected" placeholder="Device Affected" value="{{ isset($id) ? $task->device_affected : '' }}" required />
          <div class="valid-feedback"> Looks good! </div>
          <div class="invalid-feedback"> Please enter device affected. </div>
        </div>

        <div class="mb-3 col-md-8">
          <label class="form-label" for="bs-validation-short">Short Description</label>
          <input type="text" id="bs-validation-short" class="form-control" placeholder="Short Description" name="short_desc" value="{{ isset($id) ? $task->short_desc : '' }}" aria-label="Short.Description" required />
          <div class="valid-feedback"> Looks good! </div>
          <div class="invalid-feedback"> Please enter short description </div>
        </div>

        <div class="mb-3 col-md-8">
          <label class="form-label" for="bs-validation-upload-file">Error Image (One Image only)</label>
          <input type="file" class="form-control" name="attachment" id="bs-validation-upload-file" />
          @if(isset($id) && $task->error_image)
            <div class="mt-2">
              <img src="{{ asset('uploads/' . $task->error_image) }}" alt="Error Image" class="img-thumbnail" style="max-width: 200px;">
            </div>
          @endif
        </div>
         
         
         
          <div class="mb-3">
            <div class="row">
                <div class="col-sm-8">
            <label class="form-label" for="todo">Next visit todo</label>
            <input type="text" class="form-control "  name="todo" id="todo" placeholder="Next visit todo"  />
           
            </div>
            <div class="col-sm-3 mt-4">
            <button type="button" class="btn btn-primary submit-todo">Submit</button>
            </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row">
                <div class="col-sm-8">
            <label class="form-label" for="renewal">Next visit renewal</label>
            <input type="text" class="form-control " name="renewal" id="renewal" placeholder="Next visit renewal"  />
           
           
            </div>
            <div class="col-sm-3 mt-4">
            <button type="button" class="btn btn-primary submit-renewal">Submit</button>
            </div>
            </div>
          </div>

          <div class="mb-3 col-md-8">
           
            <label class="form-label" for="bs-validation-task-date">Task Date</label>
            <input type="text" class="form-control flatpickr-validation" id="task-date" name="task_date" placeholder="Task Date"  value="{{ isset($id) ?$task->task_date : date('Y-m-d')}}" />
            
           
           
          </div>
         

        <div class="row {{(isset($id))?'':'d-none'}}">
            <div class="col-md-1">
            <label class="form-label" for="bs-validation-dob">Notes</label>
            <div class="form-control text-center">
            <a id="printid11" href="javascript:;" title="Print" >
              <i class="menu-icon icon-base ti ti-printer mb-2" style="font-size: 20px;"></i>
            </a>
            <a id="printhardware" href="javascript:;" title="Print Hardware" >
              <i class="menu-icon icon-base ti ti-device-desktop-cog mb-2" style="font-size: 20px;"></i>
            </a>
           
            <a href="javascript:;" id="todolist_toggle" class="todolist_toggle" title="Todo list">
              <i class="menu-icon icon-base ti ti-list mb-2" style="font-size: 20px;"></i>
            </a>
            </div>
            </div>
          <div class="col-md-7 mt-4">
  
            <div id="full-editor">
              <?= isset($id) ? $task->long_desc : '' ?>
            </div>
          </div>
          <div class="col-md-4 d-none" id="todolist_div">
            <lable class="form-label">Todos:</lable>
            <!-- # , date , description -->
            <table class="table table-bordered border todo-table">
              <thead>
                <tr>
                  
                  <th>Date</th>
                  <th>Description</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody id="todolist">
               
              </tbody>
            </table>


            <lable class="form-label mt-5">Renewals:</lable>
            <!-- # , date , description -->
            <table class="table table-bordered border renewal-table ">
              <thead>
                <tr>
                  
                  <th>Date</th>
                  <th>Description</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody id="renewals">
               
              </tbody>
            </table>

          </div>
        </div>

        <div class="row">
            <div class="col-12 text-center mt-3">
              <button type="submit" class="btn btn-primary">{{(isset($id))?'Update':'Submit'}}</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
  <!-- /Bootstrap Validation -->
</div>

@endsection
