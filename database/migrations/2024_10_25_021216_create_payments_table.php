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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('provider_transaction_id', 50); // Datos externos
            $table->string('provider_state', 50); // Datos externos
            $table->text('checkout_url')->nullable(); // Datos externos
            $table->string('method', 50); // Método de pago: tarjeta, transferencia, etc
            $table->decimal('amount', 10, 2);
            $table->unsignedSmallInteger('nro_fee')->default(1);
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('payment_state_id')->constrained('payment_states');
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('payment_provider_id')->constrained('payment_providers');
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
