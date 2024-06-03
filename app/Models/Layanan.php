<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'active',
        'tarif_pnbp_hr_kerja',
        'tarif_pemda_hr_kerja',
        'tarif_pnbp_hr_libur',
        'tarif_pemda_hr_libur',
        'tarif_asuransi',
        'description',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/layanan/' . $image),
        );
    }


}
