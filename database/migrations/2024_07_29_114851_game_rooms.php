<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_rooms', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('room_name');
            $table->timestamps(); // Bu satır created_at ve updated_at sütunlarını ekler
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_rooms');
    }
};
