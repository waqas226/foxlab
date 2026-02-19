<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('work_orders', function (Blueprint $table) {
      $table->date('wo_date')->nullable()->after('qb');
    });

    DB::table('work_orders')
      ->whereNull('wo_date')
      ->update(['wo_date' => DB::raw('DATE(created_at)')]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('work_orders', function (Blueprint $table) {
      $table->dropColumn('wo_date');
    });
  }
};
