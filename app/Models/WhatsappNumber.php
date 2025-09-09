<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for managing WhatsApp numbers used for notifications.
 */
class WhatsappNumber extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'status',
        'qr_code',
        'session_data',
        'last_connected_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'session_data' => 'array',
        'last_connected_at' => 'datetime',
    ];
}
