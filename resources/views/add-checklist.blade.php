@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Add Checklist')

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
<script src="{{asset('assets/js/add-checklist.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Manage Checklist /</span> {{(isset($checklist))?'Update':'Add'}} Checklist
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
<!-- Users List Table -->
<div class="card">
 
    <h5 class="card-header">{{(isset($checklist))?'Update':'Add'}} Checklist</h5>

    <div class="card-body px-5">
 
      <form class="add-new-user pt-0" id="addNewUserForm" onsubmit="return false">
        @csrf
        @if(isset($checklist))
        <input type="hidden" id="id" name="id" value="{{$checklist->id}}">
        @endif
        <div class="mb-3">
          <label class="form-label" for="title">Title</label>
          <input type="text" class="form-control" id="title" placeholder="Title" name="title" required
          @if(isset($checklist)) value="{{$checklist->title}}" @endif
          />
        </div>

        
   
      <h5 class="card-header ms-0">Tasks List</h5>

      <table class="form-table newcustomEmails table mb-2" id="customEmails" width="100%">
       
        <thead>
          <tr>
            <th>Task Id</th>
            <th style="">Task</th>
            
          </tr>
        </thead>
          @if(isset($checklist) && $checklist->tasks && count($checklist->tasks)>0 )
          @foreach( $checklist->tasks as $key => $task)
          <tr   @if($key == 0) valign="top" @endif  class="add_task_left">
            <td class="taskid">{{$key+1}}</td>
          <td>
          <div class="mb-3">
      
        <input type="text" class="form-control"  placeholder="Task Title" name="task_title[]" required 
        value="{{ $task->title }}"
        />
      </div>

            @if($key == 0)
           
            @else
            <a href="javascript:void(0);" class="remCF btn btn-xs btn-danger">Remove</a>
            @endif

          </td>
          </tr>
          @endforeach
          @else
          <tr valign="top" class="add_task_left">
            <td class="taskid">1</td>
          <td>
          <div class="mb-3">
      
        <input type="text" class="form-control"  placeholder="Task Title" name="task_title[]" required  />
      </div>

              
          </td>
          </tr>
          @endif
          
         
      <tfoot>
          <td></td>
           <td>
              <a href="javascript:void(0);" class="addCF btn btn-xs btn-primary">Add More</a>
          </td>
      </tfoot>
          </table>
      
     
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <a href="/manage-checklists" class="btn btn-label-secondary" >Cancel</a>
      </form>
 </div>
</div>
</div>
</div>
@endsection
