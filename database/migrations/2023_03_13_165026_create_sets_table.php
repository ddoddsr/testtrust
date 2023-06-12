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
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->char('dayOfWeek', 10)->nullable();
            $table->char('setOfDay', 10)->nullable();
            $table->char('location', 24)->nullable();
            $table->foreignId('location_id');
            $table->unsignedBigInteger('section_leader_id')->nullable();
            $table->unsignedBigInteger('worship_leader_id')->nullable();
            $table->unsignedBigInteger('associate_worship_leader_id')->nullable();
            $table->unsignedBigInteger('prayer_leader_id')->nullable();
            $table->char('title', 24)->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
