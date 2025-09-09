<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    /** @use HasFactory<\Database\Factories\ComplaintFactory> */
    use HasFactory;

    protected $fillable = [
        'complaint_number', 'title', 'school_id', 'user_id', 'reported_by', 'category', 'description', 'image', 'video', 'priority', 'status', 'assigned_to',
        'acknowledged_status', 'acknowledged_at', 'reported_at', 'reporter_phone', 'assigned_by', 'assigned_at',
    ];

    /**
     * Get the school that reported this complaint.
     *
     * @return BelongsTo<School, $this>
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who created this complaint.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: assigned_to can be contractor OR technician (User).
     *
     * @return BelongsTo<User, $this>
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the contractor assigned to this complaint.
     *
     * @return BelongsTo<Contractor, $this>
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'assigned_to');
    }

    /**
     * Get the user who assigned this complaint.
     *
     * @return BelongsTo<User, $this>
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get all progress updates for this complaint.
     *
     * @return HasMany<ProgressUpdate, $this>
     */
    public function progressUpdates(): HasMany
    {
        return $this->hasMany(ProgressUpdate::class);
    }

    /**
     * Cast datetime fields to Carbon instances for easy formatting in views.
     */
    protected $casts = [
        'reported_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];
}
