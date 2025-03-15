<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('service_requests', function (Blueprint $table) {
        $table->id(); // Auto-incrementing primary key
        $table->string('customer_name'); // Name of the customer
        $table->string('service_type'); // Type of service requested
        $table->text('description')->nullable(); // Description of the service request
        $table->dateTime('request_date'); // Date and time of the request
        $table->string('status')->default('pending'); // Status of the request (e.g., pending, in_progress, completed)
        $table->timestamps(); // Created at and updated at timestamps
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
