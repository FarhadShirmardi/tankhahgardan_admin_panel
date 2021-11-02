<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissCallToCallLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automation_calls', function (Blueprint $table) {
            $table->text('text')->nullable()->change();
            $table->boolean('is_missed_call')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('automation_calls', function (Blueprint $table) {
            $table->text('text')->change();
            $table->dropColumn('is_missed_call');
        });
    }
}
