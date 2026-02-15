@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Add Techlink')

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
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />

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
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/add-techlink.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Manage Tech Links /</span> {{(isset($id))?'Update':'Add'}} Tech Link
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
    <div class="card">
      <h5 class="card-header">{{(isset($id))?'Update':'Add '}} Tech Link</h5>
      <div class="card-body px-5">
        <!-- show errors  -->
        @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          </div>
        @endif
        <form action="/manage-techlinks" id="addNewTaskForm" enctype="multipart/form-data" method="POST"  >
          @csrf
          @if(isset($id))
          <input type="hidden" name="id" value="{{$id}}">
          @endif
        <!-- * Priority: -->
      
       

        <!-- description textarea -->
        <div class="mb-3 col-md-8">
          <label class="form-label" for="description">Description</label>
          <textarea name="description" id="description" class="form-control" placeholder="description..." required>{{ old('description', isset($id) ? $techlink->description : '') }}</textarea>
        </div>
        <!-- link  -->
        <div class="mb-3 col-md-8">
          <label class="form-label" for="link">Link</label>
          <input type="text" class="form-control" id="link" name="link" placeholder="Link" value="{{ old('link', isset($id) ? $techlink->link : '') }}" required />
        </div>

        <!-- Notes textarea -->
        <div class="mb-3 col-md-8">
          <label class="form-label" for="notes">Notes</label>
          <textarea name="notes" id="notes" placeholder="notes..." class="form-control">{{ old('notes', isset($id) ? $techlink->notes : '') }}</textarea>
        </div>

        <!-- file upload -->
        <div class="mb-3 col-md-8">
          <label class="form-label" for="file">{{(isset($id) && isset($techlink->image))?'Update':''}} File</label>
          <input type="file" class="form-control" id="file" name="file" placeholder="Tech Link File" />
          @if(isset($id) && isset($techlink->image))
          <div class="file-dev">
          <a href="{{ asset('uploads/' . $techlink->image) }}" target="_blank" class="btn btn-info mt-2">View File</a>
          <!-- delete file  -->
          <a href="javascript::" class="btn btn-danger mt-2 delete-file"  title="Delete File" data-id="{{ $id }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete File">
            <i class="ti ti-trash"></i>
          </a>
          </div>
          @endif

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
