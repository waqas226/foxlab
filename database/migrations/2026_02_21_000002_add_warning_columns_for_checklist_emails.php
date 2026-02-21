<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    if (Schema::hasTable('site_constants') && !Schema::hasColumn('site_constants', 'email_template_warning')) {
      Schema::table('site_constants', function (Blueprint $table) {
        $table->longText('email_template_warning')->nullable()->after('email_template');
      });
    }

    if (Schema::hasTable('task_completed') && !Schema::hasColumn('task_completed', 'warning')) {
      Schema::table('task_completed', function (Blueprint $table) {
        $table->boolean('warning')->default(false)->after('completed');
      });
    }

    if (Schema::hasTable('repair_tasks') && !Schema::hasColumn('repair_tasks', 'warning')) {
      Schema::table('repair_tasks', function (Blueprint $table) {
        $table->boolean('warning')->default(false)->after('notes');
      });
    }
  }

  public function down(): void
  {
    if (Schema::hasTable('site_constants') && Schema::hasColumn('site_constants', 'email_template_warning')) {
      Schema::table('site_constants', function (Blueprint $table) {
        $table->dropColumn('email_template_warning');
      });
    }

    if (Schema::hasTable('task_completed') && Schema::hasColumn('task_completed', 'warning')) {
      Schema::table('task_completed', function (Blueprint $table) {
        $table->dropColumn('warning');
      });
    }

    if (Schema::hasTable('repair_tasks') && Schema::hasColumn('repair_tasks', 'warning')) {
      Schema::table('repair_tasks', function (Blueprint $table) {
        $table->dropColumn('warning');
      });
    }
  }
};
