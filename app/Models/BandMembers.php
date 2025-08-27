<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandMembers extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'instrument',
        'bio',
        'portrait'
    ];
    public function getPortraitUrlAttribute()
    {
        return $this->portrait
            ? asset('storage/' . $this->portrait)
            : 'https://placehold.co/600x800';
    }

}
