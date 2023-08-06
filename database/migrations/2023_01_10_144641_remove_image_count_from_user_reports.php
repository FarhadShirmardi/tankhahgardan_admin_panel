<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropColumn('image_size');
            $table->integer('payment_image_count');
            $table->integer('receive_image_count');
        });
    }

    public function down(): void
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->float('image_size');
            $table->dropColumn('payment_image_count');
            $table->dropColumn('receive_image_count');
        });
    }
};
