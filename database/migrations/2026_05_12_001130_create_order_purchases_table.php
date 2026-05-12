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
        Schema::create('order_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date')->default(now());
            $table->decimal('amount', 10, 2)->default(0);
            $table->foreignId('provider_id')->constrained()->onDelete('restrict');
            $table->foreignId('order_purchase_states_id')->constrained('order_purchase_states');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_purchases');
    }
};
