<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'principal_name',
        'principal_phone',
        'hem_name',
        'hem_phone',
        'qr_code',
    ];

    /**
     * Get the school admin user (one-to-one where role = school_admin).
     */
    public function admin()
    {
        return $this->hasOne(\App\Models\User::class)->where('role', 'school_admin');
    }
}
