<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        if (!Schema::connection('mysql')->hasColumn($tableNames['permissions'], 'name')) {
            Schema::connection('mysql')->table($tableNames['permissions'], function (Blueprint $table) {
                $table->string('title')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        if (Schema::connection('mysql')->hasColumn($tableNames['permissions'], 'name')) {
            Schema::connection('mysql')->table($tableNames['permissions'], function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }
};
