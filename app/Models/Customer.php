<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  use HasFactory;
  protected $fillable = [
    'company',
    'primary_contact',
    'primary_email',
    'primary_phone',
    'secondary_contact',
    'secondary_email',
    'secondary_phone',
    'address',
    'pm_type',
    'status',
    'comment',
  ];
}
