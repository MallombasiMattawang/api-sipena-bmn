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
        Schema::create('layanan_tikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->references('id')->on('layanans')->cascadeOnDelete();
            $table->string('barcode');
            $table->integer('tarif_pnbp');
            $table->integer('tarif_pemda');
            $table->integer('tarif_asuransi');
            $table->integer('tarif_total');
            $table->string('lunas')->nullable();
            $table->string('bukti_tf')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_tikets');
    }
};
