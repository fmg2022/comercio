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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('carrier_name', 100);
            $table->string('tracking_number')->unique(); // EMP-1242-AR (EMP)prefijo + (1242)numero de seguimiento + (AR)codigo de pais
            $table->decimal('shipping_cost', 10, 2);
            $table->dateTime('shipped_at'); // Fecha de envio
            $table->dateTime('delivered_at')->nullable(); // Fecha de entrega
            $table->foreignId('order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('shipment_state_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
