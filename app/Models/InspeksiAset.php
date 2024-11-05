<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeksiAset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'aset_id',
        'petugas_id',
        'kondisi_aset_id',
        'status_aset_id',
        'lokasi_aset_id',
        'tanggal_inspeksi',
        'hasil_inspeksi',
        'rekomendasi',
        'image',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
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

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/inspeksi/' . $image),
        );
    }
}
