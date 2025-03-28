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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homeowner_id');
            $table->unsignedBigInteger('gardener_id')->nullable();
            $table->unsignedBigInteger('service_provider_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->foreign('homeowner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gardener_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_provider_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
