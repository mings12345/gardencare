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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 11, 2);
            $table->decimal('admin_fee', 11, 2)->default(0.00);
            $table->timestamp('payment_date');
            $table->string('sender_no');
            $table->enum('payment_status',['Pending', 'Received'])->default('Pending');
            $table->string('receiver_no')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
