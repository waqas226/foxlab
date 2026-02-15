<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Techlink extends Model
{
  use HasFactory;
  protected $fillable = ['description', 'link', 'notes', 'status', 'image'];
}
