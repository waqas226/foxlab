<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
  use HasFactory;
  protected $fillable = ['user_id', 'ip_address', 'browser_name', 'browser_version', 'os_platform', 'carrier_name'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
