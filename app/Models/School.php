<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
     *
     * @return HasOne<User, $this>
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'school_admin');
    }
}
