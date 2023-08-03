<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_reports', function (Blueprint $table) {
            $table->dropColumn('note_count');
            $table->dropColumn('not_active_user_count');
            $table->renameColumn('state_id', 'province_id');
        });
    }

    public function down(): void
    {
        Schema::table('project_reports', function (Blueprint $table) {
            $table->integer('note_count');
            $table->integer('not_active_user_count');
            $table->renameColumn('province_id', 'state_id');
        });
    }
};
