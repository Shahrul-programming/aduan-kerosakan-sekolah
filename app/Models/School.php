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
}
