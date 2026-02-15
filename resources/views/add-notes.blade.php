@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Add Notes')

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
<script src="{{asset('assets/js/add-notes.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Manage Notes /</span> {{(isset($id))?'Update':'Add'}} Notes
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
    <div class="card">
      <h5 class="card-header">{{(isset($id))?'Update':'Add New'}} Notes</h5>
      <div class="card-body px-5">
        <form class="needs-validation" id="addNotesForm" enctype="multipart/form-data" method="POST" >
          @csrf
          @if(isset($id))
          <input type="hidden" name="id" value="{{$id}}">
          @endif
        <!-- * Priority: -->
      
       

        <div class="mb-3 col-md-8">
          <label class="form-label" for="title">Title</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Notes Title" value="{{ isset($id) ? $note->title : '' }}" required />
         
        </div>

      

       
         
         
         
         

          


       
           
          <div class="mb-3 col-md-8 ">
          <label class="form-label" for="items">Items</label>
          <textarea class="d-none" name="items" id="items" >{{ isset($id) ? $note->items : '' }}</textarea>
            <div id="full-editor" name="full-editor" >
              <?= isset($id) ? $note->items : '' ?>
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
