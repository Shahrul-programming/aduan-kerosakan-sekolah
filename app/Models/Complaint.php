<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    /** @use HasFactory<\Database\Factories\ComplaintFactory> */
    use HasFactory;

    protected $fillable = [
        'complaint_number', 'school_id', 'user_id', 'category', 'description', 'image', 'video', 'priority', 'status', 'assigned_to',
        'acknowledged_status', 'acknowledged_at'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Relationship: assigned_to can be contractor OR technician (User)
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'assigned_to');
    }

    public function progressUpdates()
    {
        return $this->hasMany(ProgressUpdate::class);
    }
}
