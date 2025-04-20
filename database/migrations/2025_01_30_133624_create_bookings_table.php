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
        Schema::disableForeignKeyConstraints(); // Disable foreign key checks
        
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['Landscaping','Gardening']);
            $table->foreignId('serviceprovider_id')->nullable()->references('id')->on('users');
            $table->foreignId('homeowner_id')->references('id')->on('users');
            $table->foreignId('gardener_id')->nullable()->references('id')->on('users');
            $table->string('address');
            $table->enum('status', ['Pending','Rejected','Approved', 'Completed'])->default('Pending');
            $table->date('date'); // Add date field
            $table->time('time'); // Add time field
            $table->decimal('total_price', 8, 2); // Add total_price field
            $table->text('special_instructions')->nullable();
            $table->text('decline_reason')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints(); // Re-enable foreign key checks
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};