<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  use HasFactory;

  protected $fillable = ['checklist_id', 'title', 'description'];

  public function checklist()
  {
    return $this->belongsTo(Checklist::class);
  }
  public function workOrdersCompleted()
  {
    return $this->hasMany(TaskCompleted::class);
  }
}
