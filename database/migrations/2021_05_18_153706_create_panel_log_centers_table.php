<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
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

    public function down(): void
    {
        Schema::dropIfExists('log_centers');
    }
};
