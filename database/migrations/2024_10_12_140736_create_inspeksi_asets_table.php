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
        Schema::create('inspeksi_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kondisi_aset_id')->constrained('kondisi_asets')->cascadeOnDelete();
            $table->foreignId('lokasi_aset_id')->constrained('lokasi_asets')->cascadeOnDelete();
            $table->foreignId('status_aset_id')->constrained('status_asets')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('aset_id')->constrained('asets')->cascadeOnDelete();
            $table->date('tanggal_inspeksi');
            $table->text('hasil_inspeksi');
            $table->text('rekomendasi')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeksi_asets');
    }
};
