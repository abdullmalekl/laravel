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
        Schema::create('project_parts', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->integer('part');
            $table->integer('lesson_id')->unsigned();
            $table->foreign('lesson_id')->references('id')->on('platform_prjects')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_parts');
    }
};
