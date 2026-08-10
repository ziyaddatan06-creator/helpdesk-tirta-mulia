<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('login'); });

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. AREA PEGAWAI INTERNAL
    Route::middleware(['role:Pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $activeTickets = \App\Models\Ticket::where('customer_id', $user->id)->whereHas('status', function($query) {
                $query->whereNotIn('name', ['Selesai', 'Ditutup']);
            })->count();
            $completedTickets = \App\Models\Ticket::where('customer_id', $user->id)->whereHas('status', function($query) {
                $query->whereIn('name', ['Selesai', 'Ditutup']);
            })->count();
            $tickets = \App\Models\Ticket::with(['category', 'status'])->where('customer_id', $user->id)->latest()->take(5)->get();

            return view('pelanggan.dashboard', compact('activeTickets', 'completedTickets', 'tickets'));
        })->name('dashboard');

        Route::get('/tiket/buat', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tiket', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tiket/{id}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tiket/{id}/komentar', [TicketController::class, 'comment'])->name('tickets.comment');
    });

    // 2. AREA TEKNISI LAPANGAN
    Route::middleware(['role:Teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    });

    // 3. AREA ADMIN IT 
    Route::middleware(['role:Admin|Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/tiket/{id}', [AdminController::class, 'show'])->name('tickets.show');
        Route::post('/tiket/{id}/update', [AdminController::class, 'update'])->name('tickets.update');
        Route::post('/tiket/{id}/komentar', [AdminController::class, 'comment'])->name('tickets.comment');

        Route::get('/laporan', [AdminController::class, 'reports'])->name('reports.index');
        Route::get('/laporan/cetak', [AdminController::class, 'printReport'])->name('reports.print');

        // ROUTE MANAJEMEN AKUN (Ditambah Delete)
        Route::get('/pegawai', [AdminController::class, 'users'])->name('users.index');
        Route::post('/pegawai', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/pegawai/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy'); // <-- ROUTE BARU
    });

    // 4. AREA SUPER ADMIN 
    Route::middleware(['role:Super Admin'])->prefix('superadmin')->name('superadmin.')->group(function () {});

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) { return redirect()->route('admin.dashboard'); } 
        elseif ($user->hasRole('Teknisi')) { return redirect()->route('teknisi.dashboard'); } 
        else { return redirect()->route('pelanggan.dashboard'); }
    })->name('dashboard');
});

require __DIR__.'/auth.php';