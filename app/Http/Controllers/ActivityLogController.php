<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public static function log($userId, $action, $complaintId = null)
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'complaint_id' => $complaintId,
        ]);
    }
}
