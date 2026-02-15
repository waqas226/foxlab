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
    Schema::create('statistics', function (Blueprint $table) {
      $table->id();
      $table
        ->foreignId('user_id')
        ->constrained()
        ->onDelete('cascade');
      $table->ipAddress('ip_address')->nullable();
      $table->string('browser_name')->nullable();
      $table->string('browser_version')->nullable();
      $table->string('os_platform')->nullable();
      $table->string('carrier_name')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('statistics');
  }
};
