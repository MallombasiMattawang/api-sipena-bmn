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
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->string('kode_aset')->unique();
            $table->unsignedBigInteger('kategori_aset_id');
            $table->unsignedBigInteger('kondisi_aset_id');
            $table->unsignedBigInteger('lokasi_aset_id');
            $table->unsignedBigInteger('status_aset_id');
            $table->string('merk_type');
            $table->string('nup');
            $table->year('tahun_perolehan');
            
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('pemegang_aset')->nullable();
            $table->string('image')->nullable();
            $table->integer('masa_pakai');

            // Foreign keys
            $table->foreign('kategori_aset_id')->references('id')->on('kategori_asets')->cascadeOnDelete();
            $table->foreign('status_aset_id')->references('id')->on('status_asets')->cascadeOnDelete();
            $table->foreign('lokasi_aset_id')->references('id')->on('lokasi_asets')->cascadeOnDelete();
            $table->foreign('kondisi_aset_id')->references('id')->on('kondisi_asets')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
