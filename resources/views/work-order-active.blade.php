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
<script src="{{asset('assets/js/work-order-active.js')}}"></script>
@endsection

@section('content')


<!-- Users List Table -->
 @if(session('error'))
 <div class="alert alert-danger">
  {{session('error')}}
 </div>
 @endif

    <div class="row">
      <div class=" col-md-12">
        <div class="card mb-3">
      
        <div class="card-header d-flex">
        <p class="title-text   text-uppercase text-body-secondary ">Customer Details</p>
     
            <a class="btn btn-secondary btn-sm ms-auto" href="{{ route('manage-work-orders') }}" type="button">Back</a>
          
        </div>
        <div class="card-body">
        
        <div class="row">
            <div class="col-md-6">
            <i class="icon-base ti ti-user icon-lg"></i><span class="fw-medium mx-2">Company Name:</span> <span>{{$customer->company}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">QB-WO#:</span> <span>{{$work->qb}}</span> <span class="badge bg-info ms-3">{{$work->status}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">Type:</span> <span>{{$work->type}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">Address:</span> <span>
                
                  <a href="https://maps.apple.com/?q={{$customer->address}}" target="_blank">
            {{$customer->address}}
            </a>
            </span>
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
        <p class="title-text card-header  text-uppercase text-body-secondary ">Device for Work Order</p>
        <div class="table-responsive">
        <table class="card-action table table-striped table-hover">
        <thead class="border-top">
        <tr>
          <th>WO Line #</th>
          <th>SN</th>
        <th>Make</th>
        <th>Model</th>
          @if($work->status != 'Closed' || $work->type != 'Repair')
        <th>Action</th>
      
      @endif
      
        </tr>
      </thead>
          <tbody>
            @php
            $completed = 0;
            @endphp
            @foreach($devices as $device)
            @if($device->completed)
            @php
            $completed++;
            @endphp
            @endif
            <tr>
              <td style="{{ $device->completed ? 'color:blue' : '' }}">{{ $device->pivot->sort_order ?? '' }}</td>
              <td style="{{ $device->completed ? 'color:blue' : '' }}">{{ $device->sn }}</td>
                <td  style="{{ $device->completed ? 'color:blue' : '' }}">{{ $device->make }}</td>
                <td  style="{{ $device->completed ? 'color:blue' : '' }}">{{ $device->model }}</td>
                  @if($work->status != 'Closed' || $work->type != 'Repair')
                <td>
                    <a href="{{ route('work-order-device', ['id' => $work->id, 'device_id' => $device->id]) }}" class="text-primary">
                        <i class="ti ti-list-check"></i> View
                    </a>
                </td>
                @endif
               
              
             
            </tr>
            @endforeach
            <!-- More rows as needed -->
          </tbody>
        </table>
        
        @if($work->status == 'Closed')

        @if($work->type == 'Repair')

        <p class="title-text card-header text-body-secondary "><span class="form-label">Complaint Notes : </span> {{$work->notes}}</p>

         
        <table class="card-action table table-striped table-hover">
        <thead class="border-top">
        <tr>
          <th></th>
          <th>Description / Part Number</th>
          <th>Quantity</th>
          <th>Notes</th>
        </tr>
        </thead>
        <tbody>
          @foreach($devices as $device)
        @foreach($device->tasks as $task)
        
        <tr>
          <td >{{$task->title}}</td>
          <td >{{$task->description}}</td>
          <td >{{$task->quantity}}</td>
          <td >{{$task->notes}}</td>
        </tr>
        @endforeach
        @endforeach
        </tbody>
        </table>


        @endif

        <div class="row">
          <div class="col-md-12">
            <div class="m-3">
              <label for="signature" class="form-label">Signature</label>
              <img src="{{$work->signature}}" alt="Signature" class="img-fluid">
            </div>
          </div>
        </div>

        @elseif($completed == $devices->count())

       <div class="row">
        <div class="">
        <div class="m-3">
        <label for="signature" class="form-label">Signature</label>

        <canvas class="border border-dark rounded p-0" id="signature-pad" style="max-width:600 max-height:300px" style="border:1px solid #000;"></canvas>
<button class="btn btn-secondary mt-1" type="button" onclick="clearPad()">Clear</button>
<input type="hidden" name="signature" id="signature">
<input type="hidden" name="id" id="work-order-id" value="{{$work->id}}">

        </div>

        </div>

        <div class="col-md-12">
          <div class="m-3 d-flex">
         
            <button class="btn btn-primary" id="submit-work-order" type="submit">Submit</button>
          </div>
        </div>
       </div>
    
      @endif
          
    
      </div>
    </div>
   
 

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  const canvas = document.getElementById('signature-pad');
  const signaturePad = new SignaturePad(canvas);

  function clearPad() {
    signaturePad.clear();
    document.getElementById('signature').value = '';
  }
 canvas.addEventListener('touchend', function () {
    if (!signaturePad.isEmpty()) {
      const data = signaturePad.toDataURL('image/png');
      document.getElementById('signature').value = data;
    }
  });
  canvas.addEventListener('click', function () {
    if (!signaturePad.isEmpty()) {
      const data = signaturePad.toDataURL('image/png');
      document.getElementById('signature').value = data;
    }
  });
</script>

@endsection
