<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelLogCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('panel_user_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('type');
            $table->dateTime('date_time');
            $table->text('description');
            $table->json('old_json')->nullable();
            $table->json('new_json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_centers');
    }
}
