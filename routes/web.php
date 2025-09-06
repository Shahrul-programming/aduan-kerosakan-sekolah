    // Paparan QR code pendaftaran guru/user sekolah
    Route::get('/schools/{school}/qr', [App\Http\Controllers\SchoolController::class, 'qrCode'])->name('schools.qr');
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Quick external check: write a short log entry when this route is hit.
// Use this to determine whether requests for complaints debug routes reach the Laravel app
Route::get('/ping-complaints-debug', function () {
    \Log::info('ping-complaints-debug hit', ['ip' => request()->ip(), 'uri' => request()->getRequestUri()]);
    return response()->json(['ok' => true, 'time' => now()->toDateTimeString()]);
});

// Test complaints routes without middleware to isolate the issue
Route::get('/complaints-test-no-middleware', function () {
    \Log::info('complaints-test-no-middleware hit');
    return response()->json(['message' => 'No middleware complaints route works!', 'time' => now()->toDateTimeString()]);
});

Route::get('/complaints/test-no-middleware', function () {
    \Log::info('complaints/test-no-middleware hit');
    return response()->json(['message' => 'complaints/test-no-middleware works!', 'time' => now()->toDateTimeString()]);
});

// Temporary health and debug routes (remove after debugging)
use Illuminate\Support\Facades\Route as RouteFacade;
Route::get('/_health', function () {
    return response('OK', 200);
});
Route::get('/debug-complaints-route', function () {
    return response()->json([
        'has_named_route' => RouteFacade::has('complaints.review'),
        'all_complaint_routes' => collect(
            RouteFacade::getRoutes()->getRoutes()
        )->filter(function ($r) {
            return str_contains($r->uri(), 'complaints');
        })->map(function ($r) {
            return [$r->methods(), $r->uri(), $r->getName(), $r->gatherMiddleware()];
        })->values(),
    ]);
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Dashboard Super Admin';
    });
    Route::resource('schools', App\Http\Controllers\SchoolController::class);
    Route::resource('complaints', App\Http\Controllers\ComplaintController::class);
    
    // User management (super admin)
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');

    // WhatsApp Management routes
    Route::get('/whatsapp', [\App\Http\Controllers\WhatsappController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp', [\App\Http\Controllers\WhatsappController::class, 'store'])->name('whatsapp.store');
    Route::patch('/whatsapp/{whatsappNumber}/status', [\App\Http\Controllers\WhatsappController::class, 'updateStatus'])->name('whatsapp.update-status');
    Route::delete('/whatsapp/{whatsappNumber}', [\App\Http\Controllers\WhatsappController::class, 'destroy'])->name('whatsapp.destroy');
    Route::post('/whatsapp/{whatsappNumber}/generate-qr', [\App\Http\Controllers\WhatsappController::class, 'generateQR'])->name('whatsapp.generate-qr');
    Route::post('/whatsapp/{whatsappNumber}/test', [\App\Http\Controllers\WhatsappController::class, 'testConnection'])->name('whatsapp.test');
    
    // Laporan & Dashboard Analitik
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    // Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/dashboard-analitik', [ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/reports/contractor-performance', [ReportController::class, 'contractorPerformance'])->name('reports.contractor-performance');
    
    // Report routes for Phase 5
    Route::get('/reports/trend', [ReportController::class, 'trend'])->name('reports.trend');
    // Route::get('/reports/trend/export/excel', [ReportController::class, 'trendExportExcel'])->name('reports.trend.export.excel');
    Route::get('/reports/trend/export/pdf', [ReportController::class, 'trendExportPdf'])->name('reports.trend.export.pdf');
    Route::get('/reports/by-category', [ReportController::class, 'byCategory'])->name('reports.by-category');
    // Route::get('/reports/by-category/export/excel', [ReportController::class, 'byCategoryExportExcel'])->name('reports.by-category.export.excel');
    Route::get('/reports/by-category/export/pdf', [ReportController::class, 'byCategoryExportPdf'])->name('reports.by-category.export.pdf');
    Route::get('/reports/by-school', [ReportController::class, 'bySchool'])->name('reports.by-school');
    // Route::get('/reports/by-school/export/excel', [ReportController::class, 'bySchoolExportExcel'])->name('reports.by-school.export.excel');
    Route::get('/reports/by-school/export/pdf', [ReportController::class, 'bySchoolExportPdf'])->name('reports.by-school.export.pdf');
    Route::get('/reports/pending', [ReportController::class, 'pending'])->name('reports.pending');
    // Route::get('/reports/pending/export/excel', [ReportController::class, 'pendingExportExcel'])->name('reports.pending.export.excel');
    Route::get('/reports/pending/export/pdf', [ReportController::class, 'pendingExportPdf'])->name('reports.pending.export.pdf');
    Route::get('/dashboard-analitik-graf', [ReportController::class, 'dashboardChart'])->name('reports.dashboard.chart');
});

// Route untuk Pengurusan
Route::middleware(['auth', 'role:pengurusan'])->group(function () {
    Route::get('/pengurusan', function () {
        return 'Dashboard Pengurusan';
    });
    Route::resource('complaints', App\Http\Controllers\ComplaintController::class);
    // Laporan & Dashboard Analitik (akses pengurusan)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/dashboard-analitik', [ReportController::class, 'dashboard'])->name('reports.dashboard');
});

// Route untuk Admin Sekolah
Route::middleware(['auth', 'role:school_admin'])->group(function () {
    Route::get('/school-admin', function () {
        return 'Dashboard Admin Sekolah';
    });
    
    // Simple test route without complaints prefix to test middleware
    Route::get('/test-school-admin', function () {
        $user = auth()->user();
        return response()->json([
            'message' => 'School admin middleware works!',
                'user_email' => $user ? $user->email : null,
                'user_role' => $user ? $user->role : null,
                'timestamp' => now()->toDateTimeString()
            ]);
        })->name('test.school.admin');

    // ...existing code...
    
    // Static complaint admin pages (define before resource so they are not
    // matched by the resource's {complaint} parameter)
    Route::get('complaints/review', function() {
        return view('complaints.review');
    })->name('complaints.review');
    // Debug route to show current auth user info for troubleshooting session/middleware
    Route::get('complaints/review-debug', function () {
        \Log::info('complaints/review-debug route hit!', [
            'request_uri' => request()->getRequestUri(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        $user = auth()->user();
        return response()->json([
            'authenticated' => auth()->check(),
            'user_email' => $user ? $user->email : null,
            'user_role' => $user ? $user->role : null,
            'route_hit' => true,
            'timestamp' => now()->toDateTimeString()
        ]);
    })->name('complaints.review.debug');
    Route::get('complaints/prioritize', function() {
        return view('complaints.prioritize');
    })->name('complaints.prioritize');
    Route::get('complaints/assign', function() {
        return view('complaints.assign');
    })->name('complaints.assign');

    // Resource route AFTER literal routes to prevent conflicts
    Route::resource('complaints', App\Http\Controllers\ComplaintController::class);
    
    // API routes for dashboard actions
    Route::patch('complaints/{complaint}/status', [App\Http\Controllers\ComplaintController::class, 'updateStatus'])->name('complaints.update.status');
    Route::patch('complaints/{complaint}/priority', [App\Http\Controllers\ComplaintController::class, 'updatePriority'])->name('complaints.update.priority');
    Route::get('complaints/{complaint}/assign', [App\Http\Controllers\ComplaintController::class, 'assignForm'])->name('complaints.assign.form');
});

// Route untuk Kontraktor: progress update
Route::middleware(['auth', 'role:kontraktor'])->group(function () {
    Route::post('complaints/{complaint}/progress', [\App\Http\Controllers\ProgressUpdateController::class, 'store'])->name('complaints.progress.store');
    Route::post('complaints/{complaint}/acknowledge', [\App\Http\Controllers\ComplaintController::class, 'acknowledge'])->name('complaints.acknowledge');
});

require __DIR__.'/auth.php';