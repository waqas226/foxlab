<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairTask extends Model
{
  protected $table = 'repair_tasks';
  protected $fillable = ['work_order_id', 'device_id', 'title', 'description', 'quantity', 'notes'];

  public function workOrder()
  {
    return $this->belongsTo(WorkOrder::class);
  }
}
