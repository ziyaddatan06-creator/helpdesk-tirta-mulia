<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketComment;
use App\Models\TicketAttachment; // <-- Wajib untuk simpan foto

class TechnicianController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        $activeTickets = Ticket::with(['customer', 'category', 'status'])
            ->where('technician_id', $user->id)
            ->whereHas('status', function($q) { $q->whereNotIn('name', ['Selesai', 'Ditutup']); })
            ->latest()->get();

        $completedTickets = Ticket::with(['customer', 'category', 'status'])
            ->where('technician_id', $user->id)
            ->whereHas('status', function($q) { $q->whereIn('name', ['Selesai', 'Ditutup']); })
            ->latest()->get();

        return view('teknisi.dashboard', compact('activeTickets', 'completedTickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['customer', 'category', 'status', 'attachments', 'comments.user'])
            ->where('technician_id', auth()->id())->findOrFail($id);
        $statuses = TicketStatus::orderBy('order')->get();

        return view('teknisi.tickets.show', compact('ticket', 'statuses'));
    }

    // --- FUNGSI UPDATE YANG BARU (DENGAN WAJIB FOTO) ---
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:ticket_statuses,id',
            'bukti_perbaikan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Izin file gambar
        ]);
        
        $ticket = Ticket::where('technician_id', auth()->id())->findOrFail($id);
        $oldStatus = $ticket->status->name;
        
        $newStatusModel = TicketStatus::find($request->status_id);
        $newStatus = $newStatusModel->name;

        // LOGIKA CEGAL: Jika diubah jadi "Selesai" tapi tidak ada foto, tolak!
        if (in_array($newStatus, ['Selesai', 'Ditutup']) && !$request->hasFile('bukti_perbaikan')) {
            return back()->with('error', '⚠️ Wajib melampirkan Foto Bukti Perbaikan jika laporan diselesaikan!');
        }

        // Simpan status baru
        $ticket->update(['status_id' => $request->status_id]);
        $pesanSistem = "Sistem: Status laporan diperbarui menjadi '" . $newStatus . "' oleh Teknisi Lapangan.";

        // Jika teknisi melampirkan foto
        if ($request->hasFile('bukti_perbaikan')) {
            $path = $request->file('bukti_perbaikan')->store('attachments', 'public');
            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'file_path' => $path,
            ]);
            $pesanSistem .= " (Melampirkan Foto Bukti Perbaikan)";
        }

        // Tampilkan di Live Chat otomatis
        if ($oldStatus !== $newStatus || $request->hasFile('bukti_perbaikan')) {
            TicketComment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'body' => $pesanSistem,
            ]);
        }

        return back()->with('success', 'Progress perbaikan berhasil disimpan!');
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);
        TicketComment::create([
            'ticket_id' => $id,
            'user_id' => auth()->id(),
            'body' => $request->body,
            'user_id'
        ]);
        return back()->with('success', 'Pesan terkirim.');
    }

    public function history()
    {
        $tickets = Ticket::with(['customer', 'category', 'status'])
            ->where('technician_id', auth()->id())
            ->whereHas('status', function($q) { $q->whereIn('name', ['Selesai', 'Ditutup']); })
            ->latest()->get();
            
        return view('teknisi.history', compact('tickets'));
    }
}