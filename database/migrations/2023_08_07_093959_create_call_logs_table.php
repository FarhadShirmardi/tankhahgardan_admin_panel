<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql')->create('panel_call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('panel_user_id');
            $table->unsignedInteger('user_id');
            $table->text('text');
            $table->dateTime('date');
            $table->timestamps();

            $table->foreign('panel_user_id')
                ->on('panel_users')
                ->references('id');

            $table->foreign('user_id')
                ->on('users')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
