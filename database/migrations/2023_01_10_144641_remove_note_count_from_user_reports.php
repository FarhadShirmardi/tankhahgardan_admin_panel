<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropColumn('note_count');
            $table->dropColumn('not_own_project_count');
            $table->renameColumn('feedback_count', 'ticket_count');
        });
    }

    public function down()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->integer('note_count');
            $table->integer('not_own_project_count');
            $table->renameColumn('ticket_count', 'feedback_count');
        });
    }
};
