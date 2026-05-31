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
        if (!Schema::hasTable('order_checkout_meta')) {
            Schema::create('order_checkout_meta', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('order_id')->unique();
                $table->string('delivery_method', 20)->default('instant');
                $table->string('pickup_time', 5)->nullable();
                $table->text('order_notes')->nullable();
                $table->text('shipping_address')->nullable();
                $table->string('payment_method', 20)->default('qris');
                $table->timestamp('payment_expires_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('expired_at')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_checkout_meta');
    }
};
