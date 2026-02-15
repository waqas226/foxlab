@php
$customizerHidden = 'customizer-hide';
$deviceDescription = trim(($device->make ?? '') . ' ' . ($device->model ?? ''));
if ($deviceDescription === '') {
  $deviceDescription = $device->device_type ?? 'Device';
}
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Device History')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header d-flex align-items-center">
        <p class="title-text text-uppercase text-body-secondary mb-0">Device History</p>
        <a class="btn btn-secondary btn-sm ms-auto" href="{{ $backUrl ?? route('manage-work-orders') }}" type="button">Back</a>
      </div>
      <div class="card-body">
        <h5 class="mb-0">{{ $device->sn }} - {{ $deviceDescription }}</h5>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card mb-6">
     
      <div class="table-responsive">
        <table class="card-action table table-striped table-hover">
          <thead class="border-top">
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>WO#</th>
              <th>PO Number</th>
            </tr>
          </thead>
          <tbody>
            @forelse($history as $workOrder)
            <tr>
              <td>{{ optional($workOrder->created_at)->format('Y-m-d') }}</td>
              <td>{{ $workOrder->type }}</td>
              <td>
              <a href="/manage-work-orders/{{$workOrder->id}}"  >
              {{ $workOrder->qb }}</a></td>
              <td>{{ $workOrder->client_po ?: '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center">No history found for this device.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
