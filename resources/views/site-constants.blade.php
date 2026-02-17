@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Site Constants')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />


<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />

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

<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/site-constants.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Home /</span>Update Site Constant
  </h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
<!-- Users List Table -->
<div class="card">
 
    <h5 class="card-header">Update Site Constant</h5>

    <div class="card-body px-5">
        <form class="form-horizontal" name="siteConstantForm" id="siteConstantForm" 
           onsubmit="return false" enctype="multipart/form-data" method="POST" >
          @csrf
            
            <div class="row">
                <div class="col-sm-10">
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input class="form-control" name="site_name" id="site_name" value="{{$constant->site_name}}" required
                            placeholder="Site Name" type="text">
                    </div>

                     <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input class="form-control" name="company_name" id="company_name" value="{{$constant->company_name}}" required
                            placeholder="Company Name" type="text">
                    </div>
                   
                    
                   
                    <div class="mb-3">
                        <label for="contact_address" class="form-label">Contact Address</label>
                        <input class="form-control" name="contact_address" id="contact_address"
                            value="{{$constant->contact_address}}" required placeholder="Contact Address" type="text">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact_mobile" class="form-label">Contact Mobile</label>
                        <input class="form-control" name="contact_mobile" id="contact_mobile" value="{{$constant->contact_mobile}}" required
                            placeholder="Contact Mobile" type="text">
                    </div>
                    <div class="mb-3">
                        <label for="contact_office" class="form-label">Contact Office</label>
                        <input class="form-control" name="contact_office" id="contact_office" value="{{$constant->contact_office}}" required
                            placeholder="Contact Office" type="text">
                    </div>
                    <div class="mb-3">
                        <label for="idle_timeout" class="form-label">Idle Timeout (minutes)</label>
                        <input class="form-control" name="idle_timeout" id="idle_timeout" value="{{$constant->idle_timeout}}" required
                            placeholder="Idle Timeout" type="number" step="1" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Site Logo</label>
                        <input class="form-control" name="logo" id="logo" 
                            placeholder="Select Logo" type="file">
                    </div>
                    <div class="mb-3">
                      <img style="max-width:100px" src="{{asset('uploads/'.$constant->logo)}}" />
                    </div>
                    <div class="mb-3">
                        <label for="favicon" class="form-label">Site Favicon</label>
                        <input class="form-control" name="favicon" id="favicon" 
                            placeholder="Select favicon" type="file">
                    </div>
                    <div class="mb-3">
                      <img style="max-width:100px" src="{{asset('uploads/'.$constant->favicon)}}" />
                    </div>
                    <!--
                    <div class="mb-3">
                        <label for="smtp_password" class="form-label">SMTP Password</label>
                        <input class="form-control" name="smtp_password" id="smtp_password" value="{{$constant->smtp_password}}" required
                            placeholder="SMTP Password" type="password">
                    </div>
                    <div class="mb-3">
                        <label for="smtp_server" class="form-label">SMTP Server</label>
                        <input class="form-control" name="smtp_server" id="smtp_server" value="{{$constant->smtp_server}}" required
                            placeholder="SMTP Server" type="text">
                    </div> -->
                   <div class="mb-3">
                    <label for="smtp_port" class="form-label">Sample Checklist Import</label>
                   <a href="{{asset('assets/sample-checklist.xlsx')}}" target="_blank" class="text-primary"><i class="ti ti-download me-2"></i> Download</a>
                   </div>
                    <div class="mb-3">
                        <label for="email_template" class="form-label">Email Template </label>
                        <div class="form-control" name="email_template" id="email_template" rows="4"><?= $constant->email_template ?></div>
                    </div>
                   <!-- outlook connect button -->
                   <div class="mb-3">
                  
                     @if($constant->isOutlookConnected())
                    <label for="outlook_connect" class="form-label">Outlook Connect</label>
                    <a href="#" class="btn btn-success ms-5">Connected</a>
                    @else
                    <label for="outlook_connect" class="form-label">Outlook Connect</label>
                    <a href="{{route('outlook.connect')}}" class="btn btn-info ms-5">Connect</a>
                    @endif
                   </div>
                    <div class="mb-3 d-flex">
                        <button type="submit" class="btn btn-primary">Update</button>
                       
                          
                       
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
