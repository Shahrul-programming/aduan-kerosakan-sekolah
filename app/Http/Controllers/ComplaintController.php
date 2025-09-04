<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Complaint::with(['school', 'user']);
        $schools = \App\Models\School::all();

        // Technician: only see complaints assigned to them
        if (auth()->user()->role === 'technician') {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        $complaints = $query->latest()->paginate(10)->appends($request->all());
        return view('complaints.index', compact('complaints', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = \App\Models\School::all();
        return view('complaints.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'complaint_number' => 'required|unique:complaints',
            'school_id' => 'required|exists:schools,id',
            'category' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:10240',
        ]);
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'baru';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('complaint_images', 'public');
        }
        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('complaint_videos', 'public');
        }

        $complaint = \App\Models\Complaint::create($validated);

        // Send notification email
        NotificationService::sendNewComplaintNotification($complaint);

        return redirect()->route('complaints.index')->with('success', 'Aduan berjaya dihantar.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $complaint = \App\Models\Complaint::with(['school', 'user'])->findOrFail($id);
        return view('complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        $schools = \App\Models\School::all();
        $contractors = Contractor::all();
        return view('complaints.edit', compact('complaint', 'schools', 'contractors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        $validated = $request->validate([
            'complaint_number' => 'required|unique:complaints,complaint_number,' . $complaint->id,
            'school_id' => 'required|exists:schools,id',
            'category' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'assigned_to' => 'nullable|exists:contractors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('complaint_images', 'public');
        }
        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('complaint_videos', 'public');
        }

        $complaint->update($validated);
        
        // Log aktiviti dan hantar notifikasi
        if ($request->filled('assigned_to')) {
            \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'assign kontraktor', $complaint->id);
            NotificationService::sendAssignmentNotification($complaint->fresh());
        }
        if ($request->has('status')) {
            \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'ubah status: ' . $request->status, $complaint->id);
        }
        
        return redirect()->route('complaints.index')->with('success', 'Aduan berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        $complaint->delete();
        return redirect()->route('complaints.index')->with('success', 'Aduan berjaya dipadam.');
    }

    /**
     * Kontraktor acknowledge tugasan (terima/tolak)
     */
    public function acknowledge(Request $request, $id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        if (auth()->user()->role !== 'kontraktor' || $complaint->assigned_to != auth()->id()) {
            abort(403);
        }
        $status = $request->input('acknowledge');
        if (!in_array($status, ['accepted', 'rejected'])) {
            return back()->with('error', 'Status tidak sah.');
        }
        $complaint->acknowledged_status = $status;
        $complaint->acknowledged_at = now();
        $complaint->save();
        // Log aktiviti dan hantar notifikasi
        $action = $status === 'accepted' ? 'Terima tugasan' : 'Tolak tugasan';
        \App\Http\Controllers\ActivityLogController::log(auth()->id(), $action, $complaint->id);
        NotificationService::sendAcknowledgeNotification($complaint);
        return back()->with('success', 'Status acknowledge dikemaskini.');
    }
}
