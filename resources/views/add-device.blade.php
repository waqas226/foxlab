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
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/typeahead-js/typeahead.css')}}" />




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
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

<script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
<script>
// Fallback for heic2any if CDN fails
if (typeof heic2any === 'undefined') {
  console.warn('heic2any library failed to load from CDN');
}
</script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/add-device.js')}}"></script>
<script src="{{asset('assets/js/forms-typeahead.js')}}"></script>
<script>
// Test delete handlers
$(document).ready(function() {
  console.log('Page loaded, testing delete handlers');
  
  // Test SN Pic delete
  $(document).on('click', '.delete-sn-pic-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    console.log('DELETE SN PIC CLICKED - Handler is working!');
    var deviceId = $('input[name="id"]').val() || $('#device_id').val();
    console.log('Device ID found:', deviceId);
    
    if (!deviceId) {
      alert('No device ID found - removing from view only');
      $('#sn_pic_container').fadeOut(300, function() { $(this).remove(); });
      $('#sn_pic').val('');
      return;
    }
    
    if (typeof Swal === 'undefined') {
      alert('SweetAlert not loaded!');
      return;
    }

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will permanently delete the SN picture from the server.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-danger waves-effect waves-light',
        cancelButton: 'btn btn-secondary waves-effect waves-light'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('Confirmed - deleting SN pic');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
          url: '/manage-devices/delete-picture',
          type: 'POST',
          data: {
            device_id: deviceId,
            picture_type: 'sn_pic',
            _token: csrfToken
          },
          success: function (response) {
            console.log('Delete response:', response);
            if (response.status) {
              $('#sn_pic_container').fadeOut(300, function() {
                $(this).remove();
              });
              $('#sn_pic').val('');
              Swal.fire({
                title: 'Deleted!',
                text: 'SN picture has been permanently deleted.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
            }
          },
          error: function (xhr, status, error) {
            console.error('Delete error:', xhr, status, error);
            Swal.fire({
              title: 'Error!',
              text: xhr.responseJSON?.message || 'Failed to delete picture. Status: ' + status,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  // Test Asset Pic delete
  $(document).on('click', '.delete-asset-pic-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    console.log('DELETE ASSET PIC CLICKED - Handler is working!');
    var deviceId = $('input[name="id"]').val() || $('#device_id').val();
    console.log('Device ID found:', deviceId);
    
    if (!deviceId) {
      alert('No device ID found - removing from view only');
      $('#asset_pic_container').fadeOut(300, function() { $(this).remove(); });
      $('#asset_pic').val('');
      return;
    }
    
    if (typeof Swal === 'undefined') {
      alert('SweetAlert not loaded!');
      return;
    }

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will permanently delete the Asset picture from the server.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-danger waves-effect waves-light',
        cancelButton: 'btn btn-secondary waves-effect waves-light'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('Confirmed - deleting Asset pic');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
          url: '/manage-devices/delete-picture',
          type: 'POST',
          data: {
            device_id: deviceId,
            picture_type: 'asset_pic',
            _token: csrfToken
          },
          success: function (response) {
            console.log('Delete response:', response);
            if (response.status) {
              $('#asset_pic_container').fadeOut(300, function() {
                $(this).remove();
              });
              $('#asset_pic').val('');
              Swal.fire({
                title: 'Deleted!',
                text: 'Asset picture has been permanently deleted.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
            }
          },
          error: function (xhr, status, error) {
            console.error('Delete error:', xhr, status, error);
            Swal.fire({
              title: 'Error!',
              text: xhr.responseJSON?.message || 'Failed to delete picture. Status: ' + status,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });
});
</script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Manage Device /</span> {{(isset($id))?'Update':'Add'}} Device
</h4>
<div class="row mb-4">
  
  <!-- Bootstrap Validation -->
  <div class="col-md">
<!-- Users List Table -->
<div class="card">
 
    <h5 class="card-header">{{(isset($id))?'Update':'Add'}} Device</h5>

    <div class="card-body px-5">
 
    <form class="add-new-device pt-0" id="addNewDeviceForm" method="POST" action="{{ route('device.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($device))
        <input type="hidden" name="id" id="device_id" value="{{ $device->id }}">
    @endif

    <div class="mb-3">
        <label class="form-label" for="device_type">Device Type</label>
        <input type="text" class="form-control" id="device_type" name="device_type" placeholder="Device Type" required
            value="{{ old('device_type', $device->device_type ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="make">Make</label>
        <input type="text" class="form-control" id="make" name="make" placeholder="Make"
            value="{{ old('make', $device->make ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="model">Model</label>
        <input type="text" class="form-control" id="model" name="model" placeholder="Model"
            value="{{ old('model', $device->model ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="sn">Serial Number (SN)</label>
        <input type="text" class="form-control" id="sn" name="sn" placeholder="Serial Number"
            value="{{ old('sn', $device->sn ?? '') }}" />
    </div>
    
    <div class="mb-3">
        <label class="form-label" for="sn_pic">SN Pic</label>
        <input type="file" class="form-control" id="sn_pic" name="sn_pic" accept="image/*,.heic,.HEIC" />
        <input type="hidden" id="sn_pic_path" name="sn_pic" value="" />
        <input type="hidden" id="delete_sn_pic" name="delete_sn_pic" value="0" />
        <div id="sn_pic_upload_status" class="mt-2" style="display: none;">
          <div class="progress" style="height: 20px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
          </div>
          <small class="text-muted" id="sn_pic_status_text"></small>
        </div>
        <div id="sn_pic_preview" class="mt-2" style="display: none;">
          <img id="sn_pic_preview_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail" />
          <button type="button" class="btn btn-sm btn-danger mt-2" id="sn_pic_remove_btn">
            <i class="ti ti-trash"></i> Remove
          </button>
        </div>
        @if(isset($device) && $device->sn_pic)
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('sn_pic_path').value = '{{ $device->sn_pic }}';
                document.getElementById('sn_pic_preview_img').src = '{{ asset("uploads/" . $device->sn_pic) }}';
                document.getElementById('sn_pic_preview').style.display = 'block';
            });
            </script>
        @endif
    </div>
   
    <div class="mb-3">
        <label class="form-label" for="asset">Asset</label>
        <input type="text" class="form-control" id="asset" name="asset" placeholder="Asset"
            value="{{ old('asset', $device->asset ?? '') }}" />
    </div>
    
    <div class="mb-3">
        <label class="form-label" for="asset_pic">Asset Pic</label>
        <input type="file" class="form-control" id="asset_pic" name="asset_pic" accept="image/*,.heic,.HEIC" />
        <input type="hidden" id="asset_pic_path" name="asset_pic" value="" />
        <input type="hidden" id="delete_asset_pic" name="delete_asset_pic" value="0" />
        <div id="asset_pic_upload_status" class="mt-2" style="display: none;">
          <div class="progress" style="height: 20px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
          </div>
          <small class="text-muted" id="asset_pic_status_text"></small>
        </div>
        <div id="asset_pic_preview" class="mt-2" style="display: none;">
          <img id="asset_pic_preview_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail" />
          <button type="button" class="btn btn-sm btn-danger mt-2" id="asset_pic_remove_btn">
            <i class="ti ti-trash"></i> Remove
          </button>
        </div>
        @if(isset($device) && $device->asset_pic)
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('asset_pic_path').value = '{{ $device->asset_pic }}';
                document.getElementById('asset_pic_preview_img').src = '{{ asset("uploads/" . $device->asset_pic) }}';
                document.getElementById('asset_pic_preview').style.display = 'block';
            });
            </script>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label" for="last_pm">Last PM Date</label>
        <input type="text" class="form-control flatpickr-validation" id="last_pm" name="last_pm"
            value="{{ old('last_pm', $device->last_pm ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="next_pm">Next PM Date</label>
        <input type="text" class="form-control flatpickr-validation" id="next_pm" name="next_pm"
            value="{{ old('next_pm', $device->next_pm ?? '') }}" />
    </div>

    <div class="mb-3">
        <label class="form-label" for="company">Company</label>
        <select class="form-select" id="company" name="company_id" >
            <option value="">Please Select</option>
            @foreach($customers as $company)
                <option value="{{ $company->id }}" {{ (isset($device) && $device->company_id == $company->id) ? 'selected' : '' }}>
                    {{ $company->company }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- //checklist -->
    <div class="mb-3">
        <label class="form-label" for="checklist">Checklist</label>
        <select class="form-select" id="checklist" name="checklist_id">
            <option value="">Please Select</option>
            @foreach($checklists as $checklist)
                <option value="{{ $checklist->id }}" {{ (isset($device) && $device->checklist_id == $checklist->id) ? 'selected' : '' }}>
                    {{ $checklist->title }}
                </option>
            @endforeach
        </select>
    </div>

   

    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
    <a href="/manage-devices" class="btn btn-label-secondary" >Cancel</a>
</form>


 </div>
</div>
</div>
</div>
@endsection
