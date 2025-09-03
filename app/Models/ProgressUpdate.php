<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'contractor_id',
        'description',
        'image_before',
        'image_after',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
}
