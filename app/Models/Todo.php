<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
  use HasFactory;
  protected $fillable = ['user_id', 'company_id', 'next_visit_todo', 'todo_date'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function company()
  {
    return $this->belongsTo(Company::class);
  }
}
