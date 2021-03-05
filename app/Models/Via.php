<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Via extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'country',
        'password',
        'cost',
        'status',
        'created_date',
    ];

}
