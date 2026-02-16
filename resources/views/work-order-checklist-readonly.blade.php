@php
$customizerHidden = 'customizer-hide';
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Work Order Checklist View')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <p class="title-text card-header text-uppercase text-body-secondary">Work Order Details</p>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <i class="icon-base ti ti-user icon-lg"></i><span class="fw-medium mx-2">Company Name:</span> <span>{{ $device->customer->company }}</span>
          </div>
          <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">QB-WO#:</span> <span>{{ $work->qb }}</span>
          </div>
          <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">SN#:</span> <span>{{ $device->sn }}</span>
          </div>
          <div class="col-md-6">
            <i class="icon-base ti ti-crown icon-lg"></i><span class="fw-medium mx-2">Type:</span> <span>{{ $work->type }}</span>
          </div>
          <div class="col-md-6">
            <i class="icon-base ti ti-map icon-lg"></i><span class="fw-medium mx-2">Make:</span> <span>{{ $device->make }}</span>
          </div>
          <div class="col-md-6">
            <i class="icon-base ti ti-crown icon-lg"></i><span class="fw-medium mx-2">Model:</span> <span>{{ $device->model }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card mb-6">
      <p class="title-text card-header text-uppercase text-body-secondary">Checklist for Work Order</p>
      <div class="table-responsive">
        @if($work->type == 'Repair')
          <p class="title-text card-header text-body-secondary py-1 mb-2"><span class="form-label">Notes : </span> {{ $work->notes }}</p>
          <table class="card-action table table-striped table-hover">
            <thead class="border-top">
              <tr>
                <th></th>
                <th>Description / Part Number</th>
                <th>Quantity</th>
                <th>Notes</th>
                <th>Completed</th>
              </tr>
            </thead>
            <tbody>
              @forelse($checklistTasks as $checklist)
              <tr>
                <td>{{ $checklist['title'] }}</td>
                <td>{{ $checklist['description'] ?: '-' }}</td>
                <td>{{ $checklist['quantity'] ?: '-' }}</td>
                <td>{{ $checklist['notes'] ?: '-' }}</td>
                <td>
                  <input type="checkbox" class="form-check-input" @if($checklist['completed']) checked @endif disabled>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center">No checklist items found.</td>
              </tr>
              @endforelse
              <tr>
                <td colspan="5">
                  <a href="{{ $backUrl ?? route('manage-work-orders') }}" class="btn btn-secondary">Back</a>
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
                <th>Completed</th>
              </tr>
            </thead>
            <tbody>
              @php
              $step = 1;
              @endphp
              @forelse($checklistTasks as $checklist)
              <tr>
                <td>{{ $step++ }}</td>
                <td>{{ $checklist['title'] }}</td>
                <td>{{ $checklist['notes'] ?: '-' }}</td>
                <td>
                  <input type="checkbox" class="form-check-input" @if($checklist['completed']) checked @endif disabled>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">No checklist items found.</td>
              </tr>
              @endforelse
              <tr>
                <td colspan="4">
                  <a href="{{ $backUrl ?? route('manage-work-orders') }}" class="btn btn-secondary">Back</a>
                </td>
              </tr>
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
