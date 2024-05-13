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
        Schema::create('readers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('data')->nullable();
            $table->integer('sensor_id')->unsigned();
            $table->integer('board_id')->unsigned();
            $table->foreign('sensor_id')->references('id')->on('sensors')->onUpdate('cascade');
            $table->foreign('board_id')->references('id')->on('boards')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readers');
    }
};
