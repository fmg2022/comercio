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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->unique();
            $table->foreignId('transport_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('shipping_state_id')->constrained('shipping_states')->onDelete('restrict');
            $table->foreignId('shipping_rate_id')->nullable()->constrained('shipping_rates')->onDelete('set null');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');

            // Campos operativos
            $table->string('tracking_number')->nullable();
            $table->decimal('distance_km', 8, 3)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->dateTime('estimated_delivery_date')->nullable();
            $table->dateTime('delivered_to')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
