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
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('street_1');
            $table->string('street_2')->nullable(); // Puede ser nulo
            $table->string('locality');      // Ciudad o localidad
            $table->string('province');      // Provincia o estado
            $table->string('postal_code');   // Código postal
            $table->decimal('latitude', 10, 7)->nullable();      // Latitud
            $table->decimal('longitude', 10, 7)->nullable();     // Longitud
            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'is_default']);
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
