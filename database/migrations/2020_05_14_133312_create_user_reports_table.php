<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name');
            $table->string('phone_number');
            $table->dateTime('registered_at');
            $table->integer('payment_count');
            $table->integer('receive_count');
            $table->integer('note_count');
            $table->integer('imprest_count');
            $table->integer('file_count');
            $table->integer('image_count');
            $table->float('image_size');
            $table->integer('device_count');
            $table->integer('feedback_count');
            $table->integer('step_by_step')->nullable();
            $table->integer('project_count');
            $table->integer('own_project_count');
            $table->integer('not_own_project_count');
            $table->dateTime('max_time')->nullable();
            $table->integer('user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_reports');
    }
}
