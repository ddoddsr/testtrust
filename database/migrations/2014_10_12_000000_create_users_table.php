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
            $table->softDeletes();
            $table->timestamps();

            $table->boolean('active')->default(0);
            $table->boolean('is_supervisor')->default(0);
            $table->boolean('is_worship_leader')->default(0);
            $table->boolean('is_associate_worship_leader')->default(0);
            $table->boolean('is_prayer_leader')->default(0);
            $table->boolean('is_section_leader')->default(0);
            $table->char('section', 12 )->nullable();
            $table->bigInteger('result_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->char('result_status', 100)->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->char('designation', 100)->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->char('supervisor', 100)->nullable();
            $table->char('super_email1', 100)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('exit_date')->nullable();
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
