<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_reports', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name');
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('max_time')->nullable();
            $table->integer('user_count');
            $table->integer('active_user_count');
            $table->integer('not_active_user_count');
            $table->integer('payment_count');
            $table->integer('receive_count');
            $table->integer('note_count');
            $table->integer('imprest_count');
            $table->integer('project_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_reports');
    }
}
