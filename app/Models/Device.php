<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
  use HasFactory;
  // device type,make, model,sn,last pm ,next pm ,company,checklist
  protected $fillable = [
    'device_type',
    'make',
    'model',
    'sn',
    'sn_pic',
    'last_pm',
    'next_pm',
    'company',
    'asset',
    'asset_pic',
    'company_id',
    'checklist_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class, 'company_id');
  }
  public function workOrders()
  {
    return $this->belongsToMany(WorkOrder::class, 'work_order_devices');
  }
  public function checklist()
  {
    return $this->belongsTo(Checklist::class, 'checklist_id');
  }
}
