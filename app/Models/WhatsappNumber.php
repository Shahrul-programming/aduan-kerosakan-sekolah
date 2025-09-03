<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappNumber extends Model
{
    protected $fillable = [
        'number',
        'status',
        'qr_code',
        'session_data',
        'last_connected_at'
    ];

    protected $casts = [
        'session_data' => 'array',
        'last_connected_at' => 'datetime'
    ];
}
