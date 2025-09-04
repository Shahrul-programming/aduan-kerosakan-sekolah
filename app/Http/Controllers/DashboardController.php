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
            case 'contractor':
                return $this->contractorDashboard();
            case 'technician':
                return $this->technicianDashboard();
            default:
                return redirect()->route('login');
        }
    }

    private function superAdminDashboard()
    {
        $totalComplaints = Complaint::count();
        $completedComplaints = Complaint::where('status', 'completed')->count();
        $completionRate = $totalComplaints > 0 ? round(($completedComplaints / $totalComplaints) * 100, 2) : 0;
        $stats = [
            'totalComplaints' => $totalComplaints,
            'pendingComplaints' => Complaint::where('status', 'pending')->count(),
            'inProgressComplaints' => Complaint::where('status', 'in_progress')->count(),
            'completedComplaints' => $completedComplaints,
            'completionRate' => $completionRate,
            'totalSchools' => School::count(),
            'totalUsers' => User::count(),
            'recentComplaints' => Complaint::with(['school', 'user'])->latest()->take(5)->get()
        ];

        return view('dashboard.super_admin', $stats);
    }

    private function schoolAdminDashboard()
    {
        $user = Auth::user();
        $school = $user->school;
        $schoolId = $school ? $school->id : 0;

        // Calculate additional statistics
        $monthlyComplaints = Complaint::where('school_id', $schoolId)
            ->whereMonth('created_at', now()->month)
            ->count();
        $urgentComplaints = Complaint::where('school_id', $schoolId)
            ->where('priority', 'urgent')
            ->count();

        $stats = [
            'totalComplaints' => Complaint::where('school_id', $schoolId)->count(),
            'pendingComplaints' => Complaint::where('school_id', $schoolId)->where('status', 'pending')->count(),
            'reviewComplaints' => Complaint::where('school_id', $schoolId)->where('status', 'semakan')->count(),
            'assignedComplaints' => Complaint::where('school_id', $schoolId)->where('status', 'assigned')->count(),
            'inProgressComplaints' => Complaint::where('school_id', $schoolId)->where('status', 'in_progress')->count(),
            'completedComplaints' => Complaint::where('school_id', $schoolId)->where('status', 'completed')->count(),
            'monthlyComplaints' => $monthlyComplaints,
            'urgentComplaints' => $urgentComplaints,
            'recentComplaints' => Complaint::where('school_id', $schoolId)->with(['user'])->latest()->take(5)->get(),
            'school' => $school
        ];

        return view('dashboard.school_admin', $stats);
    }

    private function contractorDashboard()
    {
        // Calculate additional statistics
        $monthlyTasks = Complaint::where('contractor_id', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->count();
        $completionRate = 85; // Sample rate
        $urgentTasks = Complaint::where('contractor_id', Auth::id())
            ->where('priority', 'urgent')
            ->count();
            
        $stats = [
            'assignedComplaints' => Complaint::where('contractor_id', Auth::id())->count(),
            'pendingComplaints' => Complaint::where('contractor_id', Auth::id())->where('status', 'pending')->count(),
            'inProgressComplaints' => Complaint::where('contractor_id', Auth::id())->where('status', 'in_progress')->count(),
            'completedComplaints' => Complaint::where('contractor_id', Auth::id())->where('status', 'completed')->count(),
            'monthlyTasks' => $monthlyTasks,
            'completionRate' => $completionRate,
            'urgentTasks' => $urgentTasks,
            'recentComplaints' => Complaint::where('contractor_id', Auth::id())->with(['school', 'user'])->latest()->take(5)->get()
        ];

        return view('dashboard.contractor', $stats);
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

        return view('dashboard.technician', $stats);
    }
}
