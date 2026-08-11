<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketCategory;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // 1. Dashboard Admin
    public function dashboard()
    {
        $activeTickets = Ticket::whereHas('status', function($q) { $q->whereNotIn('name', ['Selesai', 'Ditutup']); })->count();
        $completedTickets = Ticket::whereHas('status', function($q) { $q->whereIn('name', ['Selesai', 'Ditutup']); })->count();
        $tickets = Ticket::with(['customer', 'category', 'status'])->latest()->take(5)->get();
        
        // --- DATA UNTUK GRAFIK CHART.JS ---
        $categories = TicketCategory::withCount('tickets')->get();
        $chartLabels = $categories->pluck('name');
        $chartData = $categories->pluck('tickets_count');
        
        return view('admin.dashboard', compact('activeTickets', 'completedTickets', 'tickets', 'chartLabels', 'chartData'));
    }

    // 2. Detail Tiket & Live Chat
    public function show($id)
    {
        $ticket = Ticket::with(['customer', 'category', 'status', 'attachments', 'comments.user', 'technician'])->findOrFail($id);
        
        TicketStatus::firstOrCreate(['name' => 'Open'], ['color_code' => '#3b82f6', 'order' => 1]);
        TicketStatus::firstOrCreate(['name' => 'Sedang Diproses'], ['color_code' => '#f59e0b', 'order' => 2]);
        TicketStatus::firstOrCreate(['name' => 'Selesai'], ['color_code' => '#10b981', 'order' => 3]);
        
        $statuses = TicketStatus::orderBy('order')->get();
        $technicians = User::role('Teknisi')->get(); 

        return view('admin.tickets.show', compact('ticket', 'statuses', 'technicians'));
    }

    // 3. Update Status Laporan
    public function update(Request $request, $id)
    {
        $request->validate(['status_id' => 'required|exists:ticket_statuses,id']);
        
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status_id' => $request->status_id]);
        
        return back()->with('success', 'Status berhasil diperbarui!');
    }

    // 4. Komentar Live Chat
    public function comment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);
        
        TicketComment::create([
            'ticket_id' => $id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }

    // 5. Delegasi Teknisi
    public function assign(Request $request, $id)
    {
        $request->validate(['technician_id' => 'required|exists:users,id']);
        
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['technician_id' => $request->technician_id]);
        
        $techName = User::find($request->technician_id)->name;

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'body' => "Sistem: Laporan ini telah didelegasikan kepada Teknisi Lapangan: " . $techName,
        ]);

        return back()->with('success', 'Teknisi berhasil ditugaskan!');
    }

    // ==========================================
    // MENU BARU YANG TADI BIKIN ERROR
    // ==========================================
    
    // 6. Halaman Semua Laporan
    public function index() 
    {
        $tickets = Ticket::with(['customer', 'category', 'status', 'technician'])->latest()->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    // 7. Halaman Kategori Keluhan
    public function categories() 
    {
        $categories = TicketCategory::all();
        return view('admin.categories.index', compact('categories'));
    }

    // 8. Halaman Pengaturan Sistem
    public function settings() 
    {
        return view('admin.settings');
    }

    // ==========================================
    // MANAJEMEN AKUN & LAPORAN PDF
    // ==========================================

    public function users() 
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $user->assignRole($request->role);
        return back()->with('success', 'Akun berhasil dibuat!');
    }

    public function destroyUser($id) 
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }

    public function reports() 
    {
        $tickets = Ticket::with(['customer', 'category', 'status', 'technician'])->latest()->get();
        return view('admin.reports.index', compact('tickets'));
    }

    public function printReport() 
    {
        $tickets = Ticket::with(['customer', 'category', 'status', 'technician'])->latest()->get();
        return view('admin.reports.print', compact('tickets'));
    }
}