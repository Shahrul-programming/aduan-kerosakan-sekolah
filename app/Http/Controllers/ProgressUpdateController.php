<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\NotificationService;
use Illuminate\Http\Request;
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
            'status' => 'nullable|in:in_progress,pending,completed',
        ]);

        // Determine contractor id from authenticated user if linked
        $contractorId = null;
        if (Auth::user() && method_exists(Auth::user(), 'contractor') && Auth::user()->contractor) {
            $contractorId = Auth::user()->contractor->id;
        }
        $validated['contractor_id'] = $contractorId;
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
        // Optionally update complaint status if provided
        if ($request->filled('status')) {
            $complaint->status = $request->input('status');
            $complaint->save();
            \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'kemaskini status progress: '.$request->input('status'), $complaint->id);
            if ($request->input('status') === 'completed' || $request->input('status') === 'selesai') {
                NotificationService::sendCompletionNotification($complaint);
            }
        }

        return redirect()->route('complaints.show', $complaint)->with('success', 'Progress berjaya dikemaskini.');
    }
}
