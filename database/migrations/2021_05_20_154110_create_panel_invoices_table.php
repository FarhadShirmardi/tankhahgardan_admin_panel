<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('volume_size');
            $table->integer('user_count');
            $table->integer('wallet_amount')->default(0);
            $table->integer('total_amount');
            $table->integer('type');
            $table->integer('price_id');
            $table->integer('status')->default(2);
            $table->integer('discount_amount')->default(0);
            $table->integer('added_value_amount');
            $table->unsignedInteger('user_status_log_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
