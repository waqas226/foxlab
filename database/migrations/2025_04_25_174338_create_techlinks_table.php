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
    Schema::create('techlinks', function (Blueprint $table) {
      $table->id();
      $table->text('description')->nullable();
      $table->string('link');
      $table->text('notes')->nullable();
      $table->enum('status', ['A', 'D'])->default('A');
      $table->string('image')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('techlinks');
  }
};
