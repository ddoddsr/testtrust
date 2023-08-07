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
        Schema::create('service_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('direct_report_id'); 
            $table->foreignId('supervisor_id');  
            $table->foreignId('department_id');  
            $table->decimal('hours',  4, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_hours');
    }
};
