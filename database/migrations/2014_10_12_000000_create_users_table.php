<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wallo\FilamentCompanies\Socialite;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(
                Socialite::hasSocialiteFeatures()
            );
            $table->rememberToken();
            $table->foreignId('current_company_id')->nullable();
            $table->foreignId('current_connected_account_id')->nullable();
            $table->string('profile_photo_path')->nullable();

            $table->timestamps();

            $table->boolean('active')->default(1);
            $table->bigInteger('resultId')->nullable();
            $table->dateTime('startDate')->nullable();
            $table->dateTime('finishDate')->nullable();
            $table->dateTime('updateDate')->nullable();
            $table->char('resultStatus', 100)->nullable();
            $table->char('designation', 100)->nullable();
            $table->unsignedBigInteger('supervisorId')->nullable();
            $table->char('supervisor', 100)->nullable();
            $table->char('superEmail1', 100)->nullable();
            $table->date('effectiveDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
