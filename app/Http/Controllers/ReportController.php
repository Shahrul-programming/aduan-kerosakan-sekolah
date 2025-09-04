<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\School;
use App\Models\Contractor;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function trend()
    {
        $months = \App\Models\Complaint::selectRaw("strftime('%Y-%m', created_at) as month, count(*) as total")
            ->groupBy('month')->orderBy('month')->get();
        $labels = $months->pluck('month');
        $data = $months->pluck('total');
        return view('reports.trend', compact('labels','data'));
    }

    public function trendExportPdf()
    {
        $months = \App\Models\Complaint::selectRaw("strftime('%Y-%m', created_at) as month, count(*) as total")
            ->groupBy('month')->orderBy('month')->get();
        $labels = $months->pluck('month');
        $data = $months->pluck('total');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.trend', compact('labels','data'));
        return $pdf->download('trend-aduan-bulanan.pdf');
    }
    public function byCategory()
    {
        $stats = \App\Models\Complaint::selectRaw('category, count(*) as total')->groupBy('category')->get();
        return view('reports.by_category', compact('stats'));
    }

    public function byCategoryExportPdf()
    {
        $stats = \App\Models\Complaint::selectRaw('category, count(*) as total')->groupBy('category')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.by_category', compact('stats'));
        return $pdf->download('aduan-mengikut-kategori.pdf');
    }

    public function bySchool()
    {
        $stats = \App\Models\School::select('schools.name')
            ->leftJoin('complaints', 'schools.id', '=', 'complaints.school_id')
            ->selectRaw('count(complaints.id) as total')
            ->groupBy('schools.id')
            ->get();
        return view('reports.by_school', compact('stats'));
    }

    public function bySchoolExportPdf()
    {
        $stats = \App\Models\School::select('schools.name')
            ->leftJoin('complaints', 'schools.id', '=', 'complaints.school_id')
            ->selectRaw('count(complaints.id) as total')
            ->groupBy('schools.id')
            ->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.by_school', compact('stats'));
        return $pdf->download('aduan-mengikut-sekolah.pdf');
    }
    public function pending(Request $request)
    {
        $complaints = \App\Models\Complaint::with(['school', 'user', 'contractor'])
            ->where('status', '!=', 'selesai')
            ->latest()->paginate(20);
        return view('reports.pending', compact('complaints'));
    }

    public function pendingExportPdf(Request $request)
    {
        $complaints = \App\Models\Complaint::with(['school', 'user', 'contractor'])
            ->where('status', '!=', 'selesai')
            ->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pending', ['complaints' => $complaints]);
        return $pdf->download('aduan-belum-selesai.pdf');
    }
    public function dashboardChart()
    {
        $statusData = Complaint::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');
        $categoryData = Complaint::selectRaw('category, count(*) as total')->groupBy('category')->pluck('total','category');
        return view('reports.dashboard_chart', compact('statusData','categoryData'));
    }
    public function contractorPerformance()
    {
        $contractors = \App\Models\Contractor::withCount([
            'complaints',
            'complaints as complaints_selesai' => function($q) { $q->where('status', 'selesai'); },
            'complaints as complaints_belum' => function($q) { $q->where('status', '!=', 'selesai'); },
        ])->get();
        return view('reports.contractor_performance', compact('contractors'));
    }

    public function index(Request $request)
    {
        $schools = School::all();
        $contractors = Contractor::all();
        $categories = Complaint::select('category')->distinct()->pluck('category');
        $priorities = ['tinggi', 'sederhana', 'rendah'];

        $complaints = Complaint::with(['school', 'user', 'contractor'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->school_id, fn($q) => $q->where('school_id', $request->school_id))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->contractor_id, fn($q) => $q->where('assigned_to', $request->contractor_id))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()->paginate(20);
        return view('reports.index', compact('complaints','schools','contractors','categories','priorities'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ComplaintsExport($request->status), 'aduan-sekolah.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $complaints = Complaint::with(['school', 'user', 'contractor'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->get();
        $pdf = Pdf::loadView('reports.pdf', compact('complaints'));
        return $pdf->download('aduan-sekolah.pdf');
    }

    public function dashboard()
    {
        $total = Complaint::count();
        $byStatus = Complaint::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');
        $byContractor = Contractor::withCount('complaints')->get();
        return view('reports.dashboard', compact('total','byStatus','byContractor'));
    }
}
