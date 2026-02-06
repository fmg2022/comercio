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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60); // Nombre de la dirección (CASA, TRABAJO, etc.)
            $table->string('street', 250); // Calle
            $table->string('city', 100); // Ciudad
            $table->string('province', 100); // Provincia
            $table->string('postal_code', 20); // Código postal
            $table->boolean('is_default')->default(true); // Indica si es la dirección por defecto
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
