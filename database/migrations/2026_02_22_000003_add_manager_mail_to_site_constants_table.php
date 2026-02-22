<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    if (Schema::hasTable('site_constants') && !Schema::hasColumn('site_constants', 'manager_mail')) {
      Schema::table('site_constants', function (Blueprint $table) {
        $table->string('manager_mail')->nullable()->after('email_template');
      });
    }
  }

  public function down(): void
  {
    if (Schema::hasTable('site_constants') && Schema::hasColumn('site_constants', 'manager_mail')) {
      Schema::table('site_constants', function (Blueprint $table) {
        $table->dropColumn('manager_mail');
      });
    }
  }
};
