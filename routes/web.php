<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\Ticket;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Umum dengan Data Statistik Personal
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('Teknisi')) {
        return redirect()->route('teknisi.dashboard');
    }

    // Statistik untuk Pegawai / Pelanggan
    $userId = $user->id;
    $totalTickets = Ticket::where('customer_id', $userId)->count();
    $activeTickets = Ticket::where('customer_id', $userId)
        ->whereHas('status', function($q) { $q->whereNotIn('name', ['Selesai', 'Ditutup']); })->count();
    $completedTickets = Ticket::where('customer_id', $userId)
        ->whereHas('status', function($q) { $q->whereIn('name', ['Selesai', 'Ditutup']); })->count();
    
    $tickets = Ticket::with(['category', 'status'])
        ->where('customer_id', $userId)
        ->latest()->take(5)->get();

    return view('dashboard', compact('totalTickets', 'activeTickets', 'completedTickets', 'tickets'));
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// ROUTE PROFIL PENGGUNA
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ROUTE KHUSUS PELANGGAN / PEGAWAI
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/pelanggan/tickets', [TicketController::class, 'index'])->name('pelanggan.tickets.index');
    Route::get('/pelanggan/tickets/create', [TicketController::class, 'create'])->name('pelanggan.tickets.create');
    Route::post('/pelanggan/tickets', [TicketController::class, 'store'])->name('pelanggan.tickets.store');
    Route::get('/pelanggan/tickets/{id}', [TicketController::class, 'show'])->name('pelanggan.tickets.show');
    Route::get('/pelanggan/tiket/{id}', [TicketController::class, 'show']); // Alias
    Route::post('/pelanggan/tickets/{id}/comment', [TicketController::class, 'comment'])->name('pelanggan.tickets.comment');
    Route::post('/pelanggan/tickets/{id}/rating', [TicketController::class, 'giveRating'])->name('pelanggan.tickets.rating');
    Route::get('/pelanggan/faq', [TicketController::class, 'faq'])->name('pelanggan.faq');
});

// ==========================================
// ROUTE KHUSUS TEKNISI LAPANGAN
// ==========================================
Route::middleware(['auth'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets/history', [TechnicianController::class, 'history'])->name('tickets.history');
    Route::get('/tickets/{id}', [TechnicianController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/update', [TechnicianController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{id}/comment', [TechnicianController::class, 'comment'])->name('tickets.comment');
});

// ==========================================
// ROUTE KHUSUS ADMIN / SUPER ADMIN
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [AdminController::class, 'index'])->name('tickets.index');
    Route::get('/semua-laporan', [AdminController::class, 'index']);
    Route::get('/tickets/{id}', [AdminController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/update', [AdminController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{id}/comment', [AdminController::class, 'comment'])->name('tickets.comment');
    Route::post('/tickets/{id}/assign', [AdminController::class, 'assign'])->name('tickets.assign');
    
    // Master Data & Pengaturan
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    
    // Laporan PDF
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/reports/print', [AdminController::class, 'printReport'])->name('reports.print');
    
    // Manajemen Akun
    Route::get('/pegawai', [AdminController::class, 'users'])->name('users.index');
    Route::post('/pegawai', [AdminController::class, 'usersStore'])->name('users.store');
    Route::delete('/pegawai/{id}', [AdminController::class, 'usersDestroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';