<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tableNames = config('permission.table_names');
        Schema::connection('mysql')->table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
        });
    }

    public function down()
    {
        $tableNames = config('permission.table_names');
        Schema::connection('mysql')->table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
