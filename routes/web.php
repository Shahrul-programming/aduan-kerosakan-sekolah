
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

// Route untuk Pengurusan
Route::middleware(['auth', 'role:pengurusan'])->group(function () {
    Route::get('/pengurusan', function () {
        return 'Dashboard Pengurusan';
    });
    Route::resource('complaints', App\Http\Controllers\ComplaintController::class);
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:pengurusan'])->group(function () {
    // Route untuk pengurusan
});

// Route untuk Kontraktor: progress update
Route::middleware(['auth', 'role:kontraktor'])->group(function () {
    Route::post('complaints/{complaint}/progress', [\App\Http\Controllers\ProgressUpdateController::class, 'store'])->name('complaints.progress.store');
    Route::post('complaints/{complaint}/acknowledge', [\App\Http\Controllers\ComplaintController::class, 'acknowledge'])->name('complaints.acknowledge');
});