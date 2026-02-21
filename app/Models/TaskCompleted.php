<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCompleted extends Model
{
  protected $table = 'task_completed';

  protected $fillable = ['work_order_id', 'task_id', 'notes', 'completed', 'warning', 'device_id'];

  public function workOrder()
  {
    return $this->belongsTo(WorkOrder::class);
  }

  public function task()
  {
    return $this->belongsTo(Task::class);
  }
}
