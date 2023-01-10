<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->unsignedInteger('user_state')->default(1);
        });
    }

    public function down()
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropColumn('user_state');
        });
    }
};
