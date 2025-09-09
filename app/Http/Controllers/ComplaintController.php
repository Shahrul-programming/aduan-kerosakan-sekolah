<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ComplaintController extends Controller
{
    public function __construct()
    {
        // Allow only teachers to access complaint creation routes.
        // Note: roles 'guru' and 'teacher' are treated as synonyms in this app.
        $this->middleware('role:guru,teacher')->only(['create', 'store']);
    }
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

        // Teacher: only see complaints reported by them
        if (in_array(auth()->user()->role, ['guru','teacher'])) {
            $query->where('reported_by', auth()->id());
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
            'title' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'category' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|in:urgent,tinggi,sederhana,rendah',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:10240',
        ]);
        
        // If the authenticated user is a teacher, enforce their school_id server-side
        $userRole = null;
        if (auth()->check()) {
            $userRole = strtolower(trim(auth()->user()->role ?? ''));
        }
        \Log::debug('Complaint store - user role check', ['role' => $userRole]);

        if (in_array($userRole, ['guru', 'teacher'])) {
            $userSchoolId = auth()->user()->school_id;
            if (empty($userSchoolId)) {
                return back()->withErrors(['school_id' => 'Akaun guru tidak dikaitkan dengan mana-mana sekolah.'])->withInput();
            }
            $validated['school_id'] = $userSchoolId;
        }
        
        // Auto-generate complaint number
        $validated['complaint_number'] = $this->generateComplaintNumber();
        $validated['user_id'] = auth()->id();
        $validated['reported_by'] = auth()->id();
        // capture reporter phone if provided in form or from user profile
        // only include the field if the column exists (migration may not have been run yet)
        try {
            if (Schema::hasColumn('complaints', 'reporter_phone')) {
                $validated['reporter_phone'] = $request->input('reporter_phone', auth()->user()->phone ?? null);
            }
        } catch (\Exception $e) {
            // If DB connection or schema check fails, skip adding the field to avoid SQL errors
            \Log::warning('Schema check failed for reporter_phone: ' . $e->getMessage());
        }
        $validated['status'] = 'baru';
        $validated['reported_at'] = now();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('complaint_images', 'public');
        }
        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('complaint_videos', 'public');
        }

        try {
            $complaint = \App\Models\Complaint::create($validated);
        } catch (\Exception $e) {
            \Log::error('Failed to create complaint', ['error' => $e->getMessage(), 'payload' => $validated]);
            // Return a friendly error to the user while preserving input
            return back()->withInput()->withErrors(['general' => 'Gagal menghantar aduan. Sila semak data dan cuba lagi atau hubungi pentadbir.']);
        }

        // Send notification email
        NotificationService::sendNewComplaintNotification($complaint);

        // Redirect based on role: teachers return to their dashboard to avoid
        // hitting role-restricted complaints.index (which may cause 403).
        if (in_array($userRole, ['guru', 'teacher'])) {
            return redirect()->route('dashboard')->with('success', 'Aduan berjaya dihantar.');
        }

        return redirect()->route('complaints.index')->with('success', 'Aduan berjaya dihantar.');
    }

    /**
     * Generate unique complaint number
     */
    private function generateComplaintNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Format: ADU-YYYY-MM-XXXX (e.g., ADU-2025-09-0001)
        $prefix = "ADU-{$year}-{$month}-";
        
        // Get the last complaint number for this month
        $lastComplaint = \App\Models\Complaint::where('complaint_number', 'like', $prefix . '%')
            ->orderBy('complaint_number', 'desc')
            ->first();
        
        if ($lastComplaint) {
            // Extract the sequence number and increment
            $lastNumber = (int) substr($lastComplaint->complaint_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        // Format with leading zeros
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
        // Only show contractors associated with this complaint's school.
        // A contractor may be assigned via direct school_id or via the contractor_school pivot table.
        $contractors = Contractor::where(function($q) use ($complaint) {
            $q->where('school_id', $complaint->school_id)
              ->orWhereHas('schools', function($q2) use ($complaint) {
                  $q2->where('schools.id', $complaint->school_id);
              });
        })->orderBy('name')->get()->unique('name')->values();

        // Localized labels for Blade (status and priority)
        // Keys must match the enum values defined in the DB migration
        $statuses = [
            'baru' => 'Baru',
            'semakan' => 'Semakan',
            'assigned' => 'Diberi Tugasan',
            'proses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'pending' => 'Pending',
            'in_progress' => 'Dalam Progress',
            'completed' => 'Selesai (completed)'
        ];

        $priorities = [
            'rendah' => 'Rendah',
            'sederhana' => 'Sederhana',
            'tinggi' => 'Tinggi',
        ];

    return view('complaints.edit', compact('complaint', 'contractors', 'statuses', 'priorities', 'schools'));
    }

    /**
     * Assign a contractor to a complaint (school admin only)
     */
    public function assign(Request $request, \App\Models\Complaint $complaint)
    {
        // Logging to help debug legacy/client issues
        \Log::info('assign() called', [
            'uri' => $request->getRequestUri(),
            'full_url' => $request->fullUrl(),
            'method' => $request->method(),
            'input' => $request->all(),
            'complaint_id' => $complaint->id,
        ]);
        // Only school_admin for the complaint's school may assign
        if (auth()->user()->role !== 'school_admin' || auth()->user()->school_id !== $complaint->school_id) {
            abort(403);
        }

        $data = $request->validate([
            'contractor_id' => 'required|exists:contractors,id',
        ]);

        // Ensure contractor belongs to the same school
        $contractor = \App\Models\Contractor::find($data['contractor_id']);
        // Accept contractor if they have direct school_id match OR are linked via pivot table
        $belongsToSchool = false;
        if ($contractor) {
            if ($contractor->school_id == $complaint->school_id) {
                $belongsToSchool = true;
            } else {
                $belongsToSchool = $contractor->schools()->where('schools.id', $complaint->school_id)->exists();
            }
        }

        if (! $contractor || ! $belongsToSchool) {
            return back()->withErrors(['contractor_id' => 'Kontraktor tidak berdaftar dengan sekolah ini.']);
        }

        // Prevent double-assignment: only allow assign when complaint is not already assigned
        if (! is_null($complaint->assigned_to) && $complaint->assigned_to != $contractor->id) {
            return back()->withErrors(['contractor_id' => 'Aduan ini sudah ditugaskan kepada kontraktor lain. Sila nyah- tugaskan terlebih dahulu jika mahu menukar.']);
        }

        $complaint->assigned_to = $contractor->id;
        $complaint->status = 'assigned';
        // persist who assigned and when if the columns exist (migration may not have been run)
        try {
            if (Schema::hasColumn('complaints', 'assigned_by')) {
                $complaint->assigned_by = auth()->id();
            }
            if (Schema::hasColumn('complaints', 'assigned_at')) {
                $complaint->assigned_at = now();
            }
        } catch (\Exception $e) {
            \Log::warning('Schema check failed while assigning complaint: ' . $e->getMessage());
        }
        $complaint->save();

        \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'assign kontraktor', $complaint->id);
        NotificationService::sendAssignmentNotification($complaint->fresh());

        return back()->with('success', 'Kontraktor berjaya ditugaskan.');
    }

    /**
     * Unassign contractor from complaint (school admin only)
     */
    public function unassign(Request $request, \App\Models\Complaint $complaint)
    {
        if (auth()->user()->role !== 'school_admin' || auth()->user()->school_id !== $complaint->school_id) {
            abort(403);
        }

        // Only unassign if currently assigned
        if (is_null($complaint->assigned_to)) {
            return back()->withErrors(['general' => 'Aduan ini tiada kontraktor ditugaskan.']);
        }

        $old = $complaint->assigned_to;
        $complaint->assigned_to = null;
        $complaint->status = 'baru';
        // only clear assigned_by/assigned_at if the columns exist
        try {
            if (Schema::hasColumn('complaints', 'assigned_by')) {
                $complaint->assigned_by = null;
            }
            if (Schema::hasColumn('complaints', 'assigned_at')) {
                $complaint->assigned_at = null;
            }
        } catch (\Exception $e) {
            \Log::warning('Schema check failed while unassigning complaint: ' . $e->getMessage());
        }
        $complaint->save();

        \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'unassign kontraktor ('.$old.')', $complaint->id);
        NotificationService::sendAssignmentNotification($complaint->fresh());

        return back()->with('success', 'Kontraktor telah dinyah-tugaskan daripada aduan ini.');
    }

    // (legacy compatibility handler removed)

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
            // enforce allowed priority values
            'priority' => 'required|in:urgent,tinggi,sederhana,rendah',
            // enforce allowed status values matching DB enum
            'status' => 'required|in:baru,semakan,assigned,proses,selesai,pending,in_progress,completed',
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
        $user = auth()->user();
        // Only contractors may acknowledge. Allow acknowledgement when:
        // - the complaint is already assigned to this contractor, OR
        // - assigned_to is NULL but the contractor is associated with the complaint's school (direct or via pivot)
        if ($user->role !== 'kontraktor') {
            abort(403);
        }

        $contractor = method_exists($user, 'contractor') ? $user->contractor : null;

        $canAcknowledge = false;
        if ($contractor) {
            if ($complaint->assigned_to == $contractor->id) {
                $canAcknowledge = true;
            } elseif (is_null($complaint->assigned_to)) {
                // check contractor-school association (direct school_id or pivot)
                if ($contractor->school_id == $complaint->school_id || $contractor->schools()->where('schools.id', $complaint->school_id)->exists()) {
                    $canAcknowledge = true;
                }
            }
        }

        if (!$canAcknowledge) {
            abort(403);
        }

        $status = $request->input('acknowledge');
        if (!in_array($status, ['accepted', 'rejected'])) {
            return back()->with('error', 'Status tidak sah.');
        }

        // Persist acknowledge info
        $complaint->acknowledged_status = $status;
        $complaint->acknowledged_at = now();

        // If accepted, ensure assigned_to points to this contractor and status is 'assigned'
        if ($status === 'accepted' && $contractor) {
            $complaint->assigned_to = $contractor->id;
            $complaint->status = 'assigned';
        }

        $complaint->save();

        // Log aktiviti dan hantar notifikasi
        $action = $status === 'accepted' ? 'Terima tugasan' : 'Tolak tugasan';
        \App\Http\Controllers\ActivityLogController::log(auth()->id(), $action, $complaint->id);
        NotificationService::sendAcknowledgeNotification($complaint);

        return back()->with('success', 'Status acknowledge dikemaskini.');
    }

    /**
     * Update complaint status via API (for dashboard actions)
     */
    public function updateStatus(Request $request, \App\Models\Complaint $complaint)
    {
        // Debug logging: capture auth and request context to help diagnose 403 from browser
        try {
            \Log::info('updateStatus called', [
                'user_id' => auth()->id(),
                'user_role' => optional(auth()->user())->role,
                'complaint_id' => $complaint->id ?? null,
                'request_method' => $request->method(),
                'route_name' => \Route::currentRouteName(),
                'ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // swallow logging errors to avoid masking the real error
        }

        $request->validate([
            'status' => 'required|in:pending,semakan,assigned,in_progress,completed'
        ]);

        // Authorization: allow school_admin for the complaint school and super_admin as before.
        // Additionally allow a contractor to update status only if they are the assigned contractor.
        $user = auth()->user();
        if ($user->role === 'super_admin') {
            // allow
        } elseif ($user->role === 'school_admin') {
            if ($user->school_id !== $complaint->school_id) {
                abort(403);
            }
        } elseif ($user->role === 'kontraktor') {
            // allow only if contractor is assigned_to this complaint
            $contractor = method_exists($user, 'contractor') ? $user->contractor : null;
            if (! $contractor || $complaint->assigned_to != $contractor->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $complaint->update(['status' => $request->status]);

        // If the caller expects JSON keep returning JSON (API/AJAX). For normal
        // browser form submissions, redirect back with a flash message so the
        // user sees friendly feedback instead of raw JSON.
        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    /**
     * Update complaint priority via API (for dashboard actions)
     */
    public function updatePriority(Request $request, \App\Models\Complaint $complaint)
    {
        $request->validate([
            'priority' => 'required|in:urgent,tinggi,sederhana,rendah'
        ]);

        $complaint->update(['priority' => $request->priority]);
        
        return response()->json(['success' => true, 'message' => 'Priority updated successfully']);
    }

    /**
     * Show assign contractor form
     */
    public function assignForm(\App\Models\Complaint $complaint)
    {
        $contractors = \App\Models\User::where('role', 'kontraktor')->get();
        return view('complaints.assign_form', compact('complaint', 'contractors'));
    }

    /**
     * Generate and download a PDF work order for the assigned contractor.
     */
    public function workOrderDownload(\App\Models\Complaint $complaint)
    {
        $user = auth()->user();
        if ($user->role !== 'kontraktor') {
            abort(403);
        }

        $contractor = method_exists($user, 'contractor') ? $user->contractor : null;
        // allow if this contractor is assigned_to the complaint
        if (! $contractor || $complaint->assigned_to != $contractor->id) {
            abort(403);
        }

        // Load full complaint with relations needed for the document
        $complaint = \App\Models\Complaint::with(['school', 'user', 'contractor'])->findOrFail($complaint->id);

        // Prepare data for view
        $data = [
            'complaint' => $complaint,
            'contractor' => $contractor,
            'assigned_at' => optional($complaint->assigned_at ?? $complaint->updated_at)->format('d/m/Y H:i'),
            'work_order_date' => now()->format('d/m/Y'),
            'generated_by' => $user->name,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('complaints.work_order_pdf', $data);
        $filename = sprintf('work-order-%s-%s.pdf', $complaint->complaint_number, $complaint->id);
        return $pdf->download($filename);
    }
}
