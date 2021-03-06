<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViaHistoryLog extends Model
{
    use HasFactory;

    protected $table = 'via_history_log';

    protected $fillable = [
        'user_id',
        'via_id',
    ];
}
