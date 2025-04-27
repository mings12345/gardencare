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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();  // New column for phone number
            $table->string('address')->nullable(); // New column for address
            $table->enum('user_type', ['homeowner', 'gardener', 'service_provider', 'admin']);
            $table->text('years_experience')->nullable(); // e.g., "5 years of gardening"
            $table->json('highlighted_works')->nullable(); // Store image paths/URLs as JSON
            $table->json('reviews')->nullable(); // Store reviews as JSON
            $table->integer('completed_jobs')->nullable()->default(0); // removed ->after()
            $table->text('bio')->nullable(); // removed ->after()
            $table->string('fcm_token')->nullable();
            $table->string('gcash_no', 11)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
