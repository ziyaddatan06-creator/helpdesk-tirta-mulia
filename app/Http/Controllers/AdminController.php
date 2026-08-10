<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // <-- PENTING UNTUK HAPUS FOTO

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereHas('status', function($q) { $q->where('name', 'Open'); })->count();
        $processTickets = Ticket::whereHas('status', function($q) { $q->whereNotIn('name', ['Open', 'Selesai', 'Ditutup']); })->count();
        $completedTickets = Ticket::whereHas('status', function($q) { $q->whereIn('name', ['Selesai', 'Ditutup']); })->count();

        $tickets = Ticket::with(['customer', 'category', 'status'])->latest()->get();

        return view('admin.dashboard', compact('totalTickets', 'openTickets', 'processTickets', 'completedTickets', 'tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['customer', 'category', 'status', 'attachments', 'comments.user'])->findOrFail($id);
        
        TicketStatus::firstOrCreate(['name' => 'Open'], ['color_code' => '#3b82f6', 'order' => 1]);
        TicketStatus::firstOrCreate(['name' => 'Sedang Diproses'], ['color_code' => '#f59e0b', 'order' => 2]);
        TicketStatus::firstOrCreate(['name' => 'Selesai'], ['color_code' => '#10b981', 'order' => 3]);
        
        $statuses = TicketStatus::orderBy('order')->get();

        return view('admin.tickets.show', compact('ticket', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['status_id' => 'required|exists:ticket_statuses,id']);
        
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status->name;
        
        $ticket->update(['status_id' => $request->status_id]);
        $newStatus = TicketStatus::find($request->status_id)->name;

        if ($oldStatus !== $newStatus) {
            TicketComment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'body' => "Sistem: Status laporan diubah menjadi '" . $newStatus . "' oleh Admin.",
            ]);
        }

        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);
        
        TicketComment::create([
            'ticket_id' => $id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Pesan balasan berhasil dikirim.');
    }

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function printReport(Request $request)
    {
        $type = $request->type; 
        $query = Ticket::with(['customer', 'category', 'status'])->latest();
        $title = 'Laporan Seluruh Pengaduan IT';

        if ($type == 'harian') {
            $query->whereDate('created_at', Carbon::today());
            $title = 'Laporan Harian (Tanggal: ' . Carbon::now()->format('d M Y') . ')';
        } 
        elseif ($type == 'mingguan') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $title = 'Laporan Mingguan (' . Carbon::now()->startOfWeek()->format('d M') . ' - ' . Carbon::now()->endOfWeek()->format('d M Y') . ')';
        } 
        elseif ($type == 'bulanan') {
            $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
            $title = 'Laporan Bulanan (Bulan: ' . Carbon::now()->format('F Y') . ')';
        }

        $tickets = $query->get();

        return view('admin.reports.print', compact('tickets', 'title'));
    }

    public function users()
    {
        $users = User::with('roles')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        $username = Str::slug($request->name) . rand(10, 99);

        $user = new User();
        $user->name = $request->name;
        $user->username = $username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->assignRole($request->role);

        return back()->with('success', 'Akun ' . $request->name . ' berhasil dibuat!');
    }

    // --- FUNGSI BARU UNTUK MENGHAPUS AKUN ---
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Keamanan: Admin tidak bisa menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak diizinkan menghapus akun Anda sendiri!']);
        }

        // Hapus file foto dari server (agar tidak menuh-menuhin memori)
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus secara permanen!');
    }
}