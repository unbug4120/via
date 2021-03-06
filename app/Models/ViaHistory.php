<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViaHistory extends Model
{
    use HasFactory;
    protected $table = 'via_history';

    protected $fillable = [
        'user_id',
        'via_id',
    ];

    public function via()
    {
        return $this->belongsTo(Via::class);
    }
}
