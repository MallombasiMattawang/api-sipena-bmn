<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

      /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
    ];

     /**
     * image
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/sliders/' . $image),
        );
    }
}
