@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Create Work Order')

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
<script src="{{asset('assets/js/work-order-edit.js')}}"></script>
@endsection

@section('content')


<!-- Users List Table -->

    <div class="row">
      <div class=" col-md-12">
        <div class="card mb-3">
        <p class="title-text card-header  text-uppercase text-body-secondary ">Customer Details</p>
        <div class="card-body">
        
        <div class="row">
            <div class="col-md-6">
            <i class="icon-base ti ti-user icon-lg"></i><span class="fw-medium mx-2">Company Name:</span> <span>{{$customer->company}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">Address:</span> <span>   <a href="https://maps.apple.com/?q={{$customer->address}}" target="_blank">
            {{$customer->address}}
            </a></span>
            </div>
           
            <div class="col-md-6">
            <i class="icon-base ti ti-crown icon-lg"></i><span class="fw-medium mx-2">Primary Contact:</span> <span>{{$customer->primary_contact}}</span>
            </div>
           
            <div class="col-md-6">
            <i class="icon-base ti ti-phone icon-lg"></i><span class="fw-medium mx-2">Primary Phone:</span> <span><a href="tel:{{$customer->primary_phone}}">{{$customer->primary_phone}}</a></span>
            </div>

            <div class="col-md-6">
            <i class="icon-base ti ti-crown icon-lg"></i><span class="fw-medium mx-2">Secondary Contact:</span> <span>{{$customer->secondary_contact}}</span>
            </div>

            <div class="col-md-6">
            <i class="icon-base ti ti-phone icon-lg"></i><span class="fw-medium mx-2">Secondary Phone:</span> <span><a href="tel:{{$customer->secondary_phone}}">{{$customer->secondary_phone}}</a></span>
            </div>
        </div>

        
         </div>
         </div>
      </div>
      <div class=" col-md-12">
        <div class="card  mb-6">
        <p class="title-text card-header  text-uppercase text-body-secondary ">Select Device for Work Order</p>
        <div class="table-responsive">
        <table class="card-action table table-striped table-hover" id="devices-table">
        <thead class="border-top">
        <tr>
          <th></th>
          <th>WO Line #</th>
          <th>Move</th>
          <th>SN</th>
        <th>Make</th>
        <th>Model</th>
      
      
      
        </tr>
      </thead>
          <tbody>
            @php
              $selectedDevicesArray = json_decode($selectedDevices, true) ?? [];
              $deviceSortOrders = [];
              if(isset($workOrder) && $workOrder->devices) {
                foreach($workOrder->devices as $selectedDevice) {
                  $deviceSortOrders[$selectedDevice->id] = $selectedDevice->pivot->sort_order ?? 0;
                }
              }
              // Sort devices: selected devices first (by sort_order), then unselected
              $selectedDevicesCollection = collect($devices)->filter(function($device) use ($selectedDevicesArray) {
                return in_array($device->id, $selectedDevicesArray);
              })->sortBy(function($device) use ($deviceSortOrders) {
                return $deviceSortOrders[$device->id] ?? 999;
              });
              $unselectedDevicesCollection = collect($devices)->filter(function($device) use ($selectedDevicesArray) {
                return !in_array($device->id, $selectedDevicesArray);
              });
              $sortedDevices = $selectedDevicesCollection->merge($unselectedDevicesCollection);
            @endphp
            @foreach($sortedDevices as $index => $device)
            @php
              $isSelected = in_array($device->id, $selectedDevicesArray);
              $sortOrder = $isSelected ? ($deviceSortOrders[$device->id] ?? ($index + 1)) : ($index + 1);
            @endphp
            <tr data-device-id="{{$device->id}}" data-sort-order="{{$sortOrder}}">
              <td><input type="checkbox" class="form-check-input" name="checkDevice[]" value="{{$device->id}}"
              <?= $isSelected ? 'checked' : '' ?>
              >
          
            </td>
              <td>
                <input type="number" class="form-control sort-order-input" min="1" value="{{$sortOrder}}" style="width: 80px;" data-device-id="{{$device->id}}">
              </td>
              <td>
                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary move-up" title="Move Up">
                  <i class="ti ti-arrow-up"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary move-down" title="Move Down">
                  <i class="ti ti-arrow-down"></i>
                </button>
              </td>
              <td>{{ $device->sn }}</td>
                <td>{{ $device->make }}</td>
                <td>{{ $device->model }}</td>
               
              
             
            </tr>
            @endforeach
            <!-- More rows as needed -->
          </tbody>
        </table>
        <div class="m-3">
          <div class="row">
            <div class="col-md-6">
              <label for="qb" class="form-label">QB Work Order #</label>
              <input type="text" class="form-control " id="qb" name="qb" placeholder="Enter QB Work Order #" required value="{{$workOrder->qb}}">
            </div>
            <div class="col-md-6">
              <label for="client_po" class="form-label">Client PO #</label>
              <input type="text" class="form-control " id="client_po" name="client_po" placeholder="Enter Client PO #" value="{{$workOrder->client_po ?? ''}}">
            </div>
          </div>
        </div>
        </div>
       <div class="m-3">
        <label for="type" class="form-label">Work Order Type</label>
        <select class="form-select" id="type" name="type" required>
          <option value="" disabled selected>Select Type</option>
          <option value="PM" {{ $workOrder->type == 'PM' ? 'selected' : '' }}>PM</option>
          <option value="Repair" {{ $workOrder->type == 'Repair' ? 'selected' : '' }}>Repair</option>
          <option value="Callback" {{ $workOrder->type == 'Callback' ? 'selected' : '' }}>Callback</option>
        </select>
       </div>
       <div class="m-3 @if($workOrder->type != 'Repair') d-none @endif" id="notes-div" >
          <label for="notes" class="form-label">Notes</label>
         
          <textarea class="form-control" id="notes" name="notes" placeholder="Enter Notes" required style="height: 100px;">{{$workOrder->notes}}</textarea>
        
        </div>
        <!-- QB-WO#. -->
        <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
        <input type="hidden" name="work_order_id" id="work_order_id" value="{{$workOrder->id}}">
           
          
          
        <div class="card-footer d-flex justify-content-end">
          <button class="btn btn-primary" id="create-work-order">Update Work Order</button>

        </div>
      </div>
    </div>
   
 


@endsection
