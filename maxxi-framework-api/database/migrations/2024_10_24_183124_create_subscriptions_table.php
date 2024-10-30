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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscription_id');
            $table->unsignedBigInteger('contract_id'); 
            $table->unsignedBigInteger('product_id');
            $table->enum('status', ['active', 'inactive', 'cancelled', 'expired']); 
            $table->decimal('price', 10, 2);
            $table->date('start_date'); 
            $table->date('end_date')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
