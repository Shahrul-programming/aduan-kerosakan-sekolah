<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ProgressUpdate;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class ProgressUpdateController extends Controller
{
    public function store(Request $request, $complaintId)
    {
        $complaint = Complaint::findOrFail($complaintId);
        
        // Check if task has been acknowledged
        if ($complaint->acknowledged_status !== 'accepted') {
            return back()->with('error', 'Anda perlu menerima tugasan terlebih dahulu sebelum boleh mengemas kini progress.');
        }
        
        $validated = $request->validate([
            'description' => 'required',
            'image_before' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_after' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $validated['contractor_id'] = Auth::id();
        if ($request->hasFile('image_before')) {
            $validated['image_before'] = $request->file('image_before')->store('progress_images', 'public');
        }
        if ($request->hasFile('image_after')) {
            $validated['image_after'] = $request->file('image_after')->store('progress_images', 'public');
        }
        $progress = $complaint->progressUpdates()->create($validated);
        \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'kemaskini progress', $complaint->id);
        
        // Send notification for progress update
        NotificationService::sendProgressUpdateNotification($complaint, $validated['description']);
        
        // Optionally update complaint status
        if ($request->input('mark_complete')) {
            $complaint->status = 'selesai';
            $complaint->save();
            \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'tandakan selesai', $complaint->id);
            // Send completion notification
            NotificationService::sendCompletionNotification($complaint);
        }
        return redirect()->route('complaints.show', $complaint)->with('success', 'Progress berjaya dikemaskini.');
    }
}
