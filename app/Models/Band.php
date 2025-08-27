<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    protected $fillable = [
        'name',
        'band_list_left_to_right',
        'bio',
        'image'
    ];
}
