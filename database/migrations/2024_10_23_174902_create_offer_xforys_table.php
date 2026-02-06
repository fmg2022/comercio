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
        Schema::create('offer_xforys', function (Blueprint $table) {
            $table->foreignId('offer_template_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedSmallInteger('buy_qty');
            $table->unsignedSmallInteger('pay_qty');
            $table->primary(['offer_template_id']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_xforys');
    }
};
