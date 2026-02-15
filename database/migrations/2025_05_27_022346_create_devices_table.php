<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('devices', function (Blueprint $table) {
      $table->id();
      $table->string('device_type');
      $table->string('make');
      $table->string('model');
      $table->string('sn')->unique();
      $table->date('last_pm')->nullable();
      $table->date('next_pm')->nullable();
      $table->string('company')->nullable();
      $table->string('checklist')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('devices');
  }
};
