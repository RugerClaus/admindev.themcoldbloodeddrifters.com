<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandMembers extends Model
{
    protected $fillable = [
        'name',
        'instrument',
        'bio',
        'portrait'
    ];
}
