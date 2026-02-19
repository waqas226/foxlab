<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
  protected $fillable = ['customer_id', 'qb', 'wo_date', 'client_po', 'type', 'notes'];

  protected $casts = [
    'wo_date' => 'date',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function devices()
  {
    return $this->belongsToMany(Device::class, 'work_order_devices')
      ->withPivot('sort_order')
      ->orderBy('pivot_sort_order', 'asc');
  }
  public function completedTasks()
  {
    return $this->hasMany(TaskCompleted::class);
  }
}
