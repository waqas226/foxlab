@php
$customizerHidden = 'customizer-hide';
$pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('layouts/layoutMaster')

@section('title', '2FA Authentication')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
<script src="{{asset('assets/js/pages-auth-two-steps.js')}}"></script>
@endsection

@section('content')
<div class="authentication-wrapper authentication-basic px-4">
  <div class="authentication-inner py-4">
    <!--  Two Steps Verification -->
    <div class="card">
      <div class="card-body">
        <!-- Logo -->
        <div class="app-brand justify-content-center mb-4 mt-2">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros',['height'=>20,'withbg' => "fill: #fff;"])</span>
            <span class="app-brand-text demo text-body fw-bold ms-1">
            <span style="color:#6ab0e8">Talerman</span>
</br>
HelpDesk
              </span>
            </span>
          </a>
        </div>
        <!-- /Logo -->
        <h5 class="mb-1 pt-2">Set up Two-Factor Authentication</h5>
        <div>{!! $qrCodeSvg !!}</div>
        <p>Secret Key: <strong>{{ $secret }}</strong></p>

        <p class="mb-0 fw-medium">Enter code from Authenticator app</p>
        <form id="twoStepsForm" method="POST" action="{{ route('2fa.confirm') }}">
          @csrf
        @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
          <div class="mb-3">
            <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
              <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" autofocus>
              <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
              <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
              <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
              <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
              <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
            </div>

            <div class="d-flex justify-content-between">
              <div class="form-check">
                <input type="checkbox" name="remember_device" class="form-check-input" id="remember-me" />
                <label class="form-check-label" for="remember-me">Remember this device</label>
              </div>
           
            
        </div>
            <!-- Create a hidden field which is combined by 3 fields above -->
            <input type="hidden" name="code" />
          </div>
          <button class="btn btn-primary d-grid w-100 mb-3">
          Confirm 2FA
          </button>
          
        </form>
      </div>
    </div>
    <!-- / Two Steps Verification -->
  </div>
</div>
@endsection
