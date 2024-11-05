<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusAset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_status'
    ];

    public function aset()
    {
        return $this->hasMany(Aset::class);
    }
}
