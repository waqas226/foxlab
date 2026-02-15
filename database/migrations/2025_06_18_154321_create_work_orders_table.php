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
    Schema::create('work_orders', function (Blueprint $table) {
      $table->id();
      $table
        ->foreignId('customer_id')
        ->nullable()
        ->constrained('customers')
        ->onDelete('set null');
      $table->string('qb')->nullable(); // QB = QuickBooks ref?

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('work_orders');
  }
};
