<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';

    protected $fillable = [
        'name',
        'email',
        'number', // যুক্ত করা হলো
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
