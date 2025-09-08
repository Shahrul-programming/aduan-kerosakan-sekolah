<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'phone',
        'email',
        'address',
    'school_id',
    'user_id',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    /**
     * Many-to-many: contractor may be associated with multiple schools
     */
    public function schools()
    {
        return $this->belongsToMany(\App\Models\School::class, 'contractor_school');
    }

    /**
     * Optional link to a User account for the contractor.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
