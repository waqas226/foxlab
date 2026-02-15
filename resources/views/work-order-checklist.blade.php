@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Work Order Checklist')

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
<script src="{{asset('assets/js/work-order-checklist.js')}}"></script>
@endsection

@section('content')


<!-- Users List Table -->

    <div class="row">
      <div class=" col-md-12">
        <div class="card mb-3">
        <p class="title-text card-header  text-uppercase text-body-secondary ">Work Order Details</p>
        <div class="card-body">
        
        <div class="row">
            <div class="col-md-6">
            <i class="icon-base ti ti-user icon-lg"></i><span class="fw-medium mx-2">Company Name:</span> <span>{{$device->customer->company}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">SN#:</span> <span>{{$device->sn}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">make:</span> <span>{{$device->make}}</span>
            </div>
            <div class="col-md-6">
            <i class="icon-base ti ti-crown icon-lg"></i><span class="fw-medium mx-2">model:</span> <span>{{$device->model}}</span>
            </div>
            
        </div>

        
         </div>
         </div>
      </div>
      <div class=" col-md-12">
        <div class="card  mb-6">
        <p class="title-text card-header  text-uppercase text-body-secondary ">Checklist for Work Order</p>
        <div class="table-responsive">
          @if($work->type=='Repair')

          
           
           <p class="title-text card-header text-body-secondary py-1 mb-2"><span class="form-label">Notes : </span> {{$work->notes}}</p>

         

         
          <table class="card-action table table-striped table-hover">
            <thead class="border-top">
            <tr>
              <th></th>
            <th>Description / Part Number</th>
            <th>Quantity</th>
            <th>Notes</th>
            <th>
              <input type="checkbox" id="selectAllCompletedRepair" class="form-check-input">
              <label for="selectAllCompletedRepair" class="ms-2">Completed</label>
            </th>
            </tr>
            </thead>
            <tbody>
              @foreach($checklistTasks as $checklist)
              <tr>
                <td>{{$checklist['title']}}</td>
                <td>
                <input type="hidden" name="task_id[]" value="{{$checklist['id']}}">
                 <textarea name="description[]" id="labor_description" class="form-control" placeholder="Enter Description">{{$checklist['description']}}</textarea>
                </td>
                <td>
                  <input type="text" name="quantity[]" id="labor_quantity" class="form-control" value="{{$checklist['quantity']}}">
                </td>
                  <td>
                    
                    <textarea name="notes[]" id="labor_notes" class="form-control" placeholder="Enter Notes">{{$checklist['notes']}}</textarea>
                    
                  </td>
                <td>
                  <input type="checkbox" name="completed[]" class="form-check-input completed-checkbox-repair" @if($checklist['completed']) checked @endif>
                </td>
              </tr>
              @endforeach
              <tr>
              <td colspan="2">
              <input type="hidden" name="device_id" id="device_id" value="{{$device_id}}">
              <a href="/manage-work-orders/{{$work_order_id}}" class="btn btn-secondary me-auto">Back</a>
              </td>
                <td colspan="3" >
                  <input type="hidden" name="work_order_id" id="work_order_id" value="{{$work_order_id}}">
               @if($work->status != 'Closed')
                <div class="d-flex">
                    <button type="submit" id="update-work-checklist" class="btn btn-primary ms-auto">Save</button>
                </div>
                @endif
                </td>
            </tr>
            </tbody>

            </table>
            

          @else
    <table class="card-action table table-striped table-hover">
        <thead class="border-top">
        <tr>
       
          <th>Step</th>
        <th>Task</th>
        <th>Comment First</th>
        <th>
          <input type="checkbox" id="selectAllCompleted" class="form-check-input" 
          @if($work->status == 'Closed') disabled @endif>
          <label for="selectAllCompleted" class="ms-2">Completed</label>
        </th>
    
        </tr>
        </thead>
        <tbody>
            @php
            $step = 1;
            @endphp
            @foreach($checklistTasks as $checklist)
            <tr>
             <td>{{$step++}}</td>
             <td>{{$checklist['title']}}</td>
             <td>
                <input type="hidden" name="task_id[]" value="{{$checklist['id']}}">
                @if($work->status == 'Closed')
                      {{$checklist['notes']}}
                      @else
                <textarea name="notes[]" id="notes" class="form-control" placeholder="Enter Comment">{{$checklist['notes'] ?? ''}}</textarea>
                @endif
              </td>
             <td>
                <input type="checkbox" name="completed[]" class="form-check-input completed-checkbox" @if($checklist['completed']) checked @endif 
                @if($work->status == 'Closed') disabled @endif
                >
             </td>
            </tr>
            @endforeach
            <tr>
              <td colspan="2">
              <input type="hidden" name="device_id" id="device_id" value="{{$device_id}}">
              <a href="/manage-work-orders/{{$work_order_id}}" class="btn btn-secondary me-auto">Back</a>
              </td>
                <td colspan="3" >
                  <input type="hidden" name="work_order_id" id="work_order_id" value="{{$work_order_id}}">
               @if($work->status != 'Closed')
                <div class="d-flex">
                    <button type="submit" id="update-work-checklist" class="btn btn-primary ms-auto">Save</button>
                </div>
                @endif
                </td>
            </tr>
            <!-- More rows as needed -->
          </tbody>
      </table>
      @endif
      
          
    
      </div>
    </div>
   
 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle select all functionality
    function setupSelectAll(selectAllId, checkboxClass) {
        const selectAllCheckbox = document.getElementById(selectAllId);
        const completedCheckboxes = document.querySelectorAll('.' + checkboxClass);
        
        if (selectAllCheckbox && completedCheckboxes.length > 0) {
            // Handle select all checkbox
            selectAllCheckbox.addEventListener('change', function() {
                completedCheckboxes.forEach(function(checkbox) {
                    if (!checkbox.disabled) {
                        checkbox.checked = selectAllCheckbox.checked;
                    }
                });
            });
            
            // Update select all checkbox when individual checkboxes change
            completedCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(completedCheckboxes).every(function(cb) {
                        return cb.disabled || cb.checked;
                    });
                    const someChecked = Array.from(completedCheckboxes).some(function(cb) {
                        return !cb.disabled && cb.checked;
                    });
                    
                    if (allChecked && someChecked) {
                        selectAllCheckbox.checked = true;
                        selectAllCheckbox.indeterminate = false;
                    } else if (someChecked) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = true;
                    } else {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = false;
                    }
                });
            });
            
            // Initialize select all checkbox state on page load
            const allChecked = Array.from(completedCheckboxes).every(function(cb) {
                return cb.disabled || cb.checked;
            });
            const someChecked = Array.from(completedCheckboxes).some(function(cb) {
                return !cb.disabled && cb.checked;
            });
            
            if (allChecked && someChecked) {
                selectAllCheckbox.checked = true;
            } else if (someChecked) {
                selectAllCheckbox.indeterminate = true;
            }
        }
    }
    
    // Setup for regular checklist table
    setupSelectAll('selectAllCompleted', 'completed-checkbox');
    
    // Setup for repair type table
    setupSelectAll('selectAllCompletedRepair', 'completed-checkbox-repair');
});
</script>

@endsection
