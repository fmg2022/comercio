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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('trade_name', 150)->nullable(); // Nombre comercial
            $table->string('email', 100)->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->boolean('active')->default(true);
            $table->string('address_full', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
