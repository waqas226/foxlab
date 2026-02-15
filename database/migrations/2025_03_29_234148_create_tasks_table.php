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
    Schema::create('tasks', function (Blueprint $table) {
      $table->id();
      $table
        ->foreignId('user_id')
        ->constrained()
        ->onDelete('cascade'); // Links to users table
      $table
        ->foreignId('company_id')
        ->nullable()
        ->constrained('companies')
        ->onDelete('set null'); // Links to companies table
      $table->string('device_affected')->nullable();
      $table->string('short_desc');
      $table->text('long_desc')->nullable();
      $table->enum('enumToDo', ['Normal', 'Urgent'])->default('Normal');
      $table->string('error_image')->nullable();
      $table->ipAddress('ip_address')->nullable();
      $table->enum('status', ['Open', 'Closed', 'Archived', 'Pending'])->default('Pending');
      $table->date('task_date');
      $table->timestamp('dt_last_activity')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tasks');
  }
};
