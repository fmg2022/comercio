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
            $table->string('tracking_number')->unique(); // EMP-1242-AR (EMP)prefijo + (1242)numero de seguimiento + (AR)codigo de pais
            $table->date('shipped_at')->nullable(); // Fecha de envio
            $table->date('delivered_at')->nullable(); // Fecha de entrega
            $table->foreignId('shipping_provider_id')->constrained('shipping_providers')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
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
