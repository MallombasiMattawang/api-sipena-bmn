<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama_aset',
        'kode_aset',
        'merk_type',
        'nup',
        'tahun_perolehan',
        'kategori_aset_id',
        'status_aset_id',
        'kondisi_aset_id',
        'lokasi_aset_id',
        'deskripsi',
        'harga',
        'tanggal_perolehan',
        'pemegang_aset',
        'image',
        'masa_pakai'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriAset::class, 'kategori_aset_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusAset::class, 'status_aset_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(KondisiAset::class, 'kondisi_aset_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiAset::class, 'lokasi_aset_id');
    }

    // public function inspeksi()
    // {
    //     return $this->hasMany(InspeksiAset::class);
    // }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/bmn/' . $image),
        );
    }
}
