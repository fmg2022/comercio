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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->default(now());
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('iva', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->foreignId('order_state_id')->constrained()->onUpdate('cascade');
            $table->foreignId('address_id')->constrained()->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
