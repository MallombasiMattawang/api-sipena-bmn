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
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('active')->default('YES');
            $table->integer('tarif_pnbp_hr_kerja');
            $table->integer('tarif_pemda_hr_kerja');
            $table->integer('tarif_pnbp_hr_libur');
            $table->integer('tarif_pemda_hr_libur');
            $table->integer('tarif_asuransi');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
