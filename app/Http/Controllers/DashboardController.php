<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use App\Models\School;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        switch ($role) {
            case 'super_admin':
                return $this->superAdminDashboard();
            case 'school_admin':
                return $this->schoolAdminDashboard();
            // teacher/guru have their own dashboard with complaint form
            case 'guru':
            case 'teacher':
                return $this->teacherDashboard();
            case 'contractor':
            case 'kontraktor':
                return $this->contractorDashboard();
            case 'technician':
                return $this->technicianDashboard();
            default:
                return redirect()->route('login');
        }
    }

    private function superAdminDashboard()
    {
        $total_complaints = Complaint::count();
        $completed_complaints = Complaint::where('status', 'completed')->count();
        $completion_rate = $total_complaints > 0 ? round(($completed_complaints / $total_complaints) * 100, 2) : 0;
        $stats = [
            'total_complaints' => $total_complaints,
            'pending_complaints' => Complaint::where('status', 'pending')->count(),
            'in_progress_complaints' => Complaint::where('status', 'in_progress')->count(),
            'completed_complaints' => $completed_complaints,
            'completion_rate' => $completion_rate,
            'total_schools' => School::count(),
            'total_users' => User::count(),
            'recent_complaints' => Complaint::with(['school', 'user'])->latest()->take(5)->get()
        ];

        return view('dashboards.super-admin', ['stats' => $stats]);
    }

    private function schoolAdminDashboard()
    {
        $user = Auth::user();
        // Resolve school via relationship if present, otherwise fetch by school_id
        $school = $user->school ?? null;
        if (!$school && $user->school_id) {
            $school = School::find($user->school_id);
        }
        $schoolId = $school ? $school->id : 0;

        // Calculate additional statistics
        $monthlyComplaints = Complaint::where('school_id', $schoolId)
            ->whereMonth('created_at', now()->month)
            ->count();
        $urgentComplaints = Complaint::where('school_id', $schoolId)
            ->whereIn('priority', ['urgent', 'tinggi'])
            ->count();

        // Accept both English and Malay status values to be resilient to mixed data
        $stats = [
            'total_complaints' => Complaint::where('school_id', $schoolId)->count(),
            'pending_complaints' => Complaint::where('school_id', $schoolId)->whereIn('status', ['pending', 'baru'])->count(),
            'review_complaints' => Complaint::where('school_id', $schoolId)->whereIn('status', ['semakan', 'review'])->count(),
            'assigned_complaints' => Complaint::where('school_id', $schoolId)->whereIn('status', ['assigned', 'ditugaskan'])->count(),
            'in_progress_complaints' => Complaint::where('school_id', $schoolId)->whereIn('status', ['in_progress', 'dalam_proses'])->count(),
            'completed_complaints' => Complaint::where('school_id', $schoolId)->whereIn('status', ['completed', 'selesai'])->count(),
            'monthly_complaints' => $monthlyComplaints,
            'urgent_complaints' => $urgentComplaints,
            'recent_complaints' => Complaint::where('school_id', $schoolId)->with(['user'])->latest()->take(10)->get(),
            'school' => $school
        ];

    return view('dashboards.school-admin', ['stats' => $stats]);
    }

    private function contractorDashboard()
    {
        // Resolve assigned identifier: prefer contractor.id when user has linked contractor
        $user = Auth::user();
        $assignedIdentifier = Auth::id();
        if (method_exists($user, 'contractor') && $user->contractor) {
            $assignedIdentifier = $user->contractor->id;
        }

        // Calculate additional statistics
        $monthlyTasks = Complaint::where('assigned_to', $assignedIdentifier)
            ->whereMonth('created_at', now()->month)
            ->count();
        $completionRate = 85; // Sample rate
        $urgentTasks = Complaint::where('assigned_to', Auth::id())
            ->where('priority', 'urgent')
            ->count();
            
        $stats = [
            'assigned_complaints' => Complaint::where('assigned_to', $assignedIdentifier)->count(),
            'pending_complaints' => Complaint::where('assigned_to', $assignedIdentifier)->where('status', 'pending')->count(),
            'in_progress_complaints' => Complaint::where('assigned_to', $assignedIdentifier)->where('status', 'in_progress')->count(),
            'completed_complaints' => Complaint::where('assigned_to', $assignedIdentifier)->where('status', 'completed')->count(),
            'monthly_tasks' => $monthlyTasks,
            'completion_rate' => $completionRate,
            'urgent_tasks' => $urgentTasks,
            'recent_complaints' => Complaint::where('assigned_to', $assignedIdentifier)->with(['school', 'user'])->latest()->take(5)->get()
        ];

    return view('dashboards.contractor', ['stats' => $stats]);
    }

    private function technicianDashboard()
    {
        // Calculate additional statistics
        $todayTasks = Complaint::where('technician_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();
        $pendingTasks = Complaint::where('technician_id', Auth::id())
            ->where('status', 'pending')
            ->count();
        $completionRate = 78; // Sample rate
        $urgentTasks = Complaint::where('technician_id', Auth::id())
            ->where('priority', 'urgent')
            ->count();
            
        $stats = [
            'assignedComplaints' => Complaint::where('technician_id', Auth::id())->count(),
            'pendingComplaints' => Complaint::where('technician_id', Auth::id())->where('status', 'pending')->count(),
            'inProgressComplaints' => Complaint::where('technician_id', Auth::id())->where('status', 'in_progress')->count(),
            'completedComplaints' => Complaint::where('technician_id', Auth::id())->where('status', 'completed')->count(),
            'todayTasks' => $todayTasks,
            'pendingTasks' => $pendingTasks,
            'completionRate' => $completionRate,
            'urgentTasks' => $urgentTasks,
            'recentComplaints' => Complaint::where('technician_id', Auth::id())->with(['school', 'user'])->latest()->take(5)->get()
        ];

    return view('dashboards.technician', ['stats' => $stats]);
    }

    /**
     * Dashboard for teacher role: show complaint form and their own complaints
     */
    private function teacherDashboard()
    {
        $stats = [];
    return view('dashboards.teacher', ['stats' => $stats]);
    }
}
