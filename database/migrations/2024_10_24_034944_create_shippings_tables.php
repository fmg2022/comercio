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
            $table->foreignId('shipping_states_id')->constrained('shipping_states')->onDelete('restrict');
            $table->foreignId('shipping_rate_id')->constrained('shipping_rates')->onDelete('restrict');

            // Campos operativos
            $table->string('tracking_number')->nullable();
            $table->decimal('distance_km', 8, 3)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->date('estimated_delivery_date')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();

            // Indica si es factible enviar esta orden (ej: dentro de zona de cobertura)
            $table->boolean('is_feasible')->default(true);

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
