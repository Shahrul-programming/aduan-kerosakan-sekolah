<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    Route::resource('complaints', App\Http\Controllers\ComplaintController::class);
    Route::get('complaints/review', function() {
        return view('complaints.review');
    })->name('complaints.review');
    Route::get('complaints/prioritize', function() {
        return view('complaints.prioritize');
    })->name('complaints.prioritize');
    Route::get('complaints/assign', function() {
        return view('complaints.assign');
    })->name('complaints.assign');
});

// Route untuk Kontraktor: progress update
Route::middleware(['auth', 'role:kontraktor'])->group(function () {
    Route::post('complaints/{complaint}/progress', [\App\Http\Controllers\ProgressUpdateController::class, 'store'])->name('complaints.progress.store');
    Route::post('complaints/{complaint}/acknowledge', [\App\Http\Controllers\ComplaintController::class, 'acknowledge'])->name('complaints.acknowledge');
});

require __DIR__.'/auth.php';