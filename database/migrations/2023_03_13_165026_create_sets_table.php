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
            $table->char('sectionLeader', 24)->nullable();
            $table->char('worshipLeader', 24)->nullable();
            $table->char('prayerLeader', 24)->nullable();
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
