<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nama_kategori'
    ];

    public function aset()
    {
        return $this->hasMany(Aset::class);
    }
}
