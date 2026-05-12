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
        Schema::create('order_purchase_product', function (Blueprint $table) {
            $table->foreignId('order_purchase_id')->constrained('order_purchases')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->primary(['order_purchase_id', 'product_id']);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('suggested_sale_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_purchase_product');
    }
};
