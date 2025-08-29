<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarouselImages extends Model
{
    protected $fillable = [
        'src',
        'caption',
        'blurb',
    ];
}
