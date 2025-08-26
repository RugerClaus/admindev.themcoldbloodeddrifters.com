<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessages extends Model
{
    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'subject',
        'email',
        'phone',
        'body',
        'read',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
