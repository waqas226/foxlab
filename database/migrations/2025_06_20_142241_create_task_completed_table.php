<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
  {
    Schema::create('task_completed', function (Blueprint $table) {
      $table->id();
      $table
        ->foreignId('work_order_id')
        ->constrained()
        ->onDelete('cascade');
      $table
        ->foreignId('task_id')
        ->constrained()
        ->onDelete('cascade');
      $table->text('notes')->nullable();
      $table->boolean('completed')->default(false);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('task_completed');
  }
};
