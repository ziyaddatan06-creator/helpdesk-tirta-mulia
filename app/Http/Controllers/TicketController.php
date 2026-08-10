<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function create()
    {
        TicketCategory::firstOrCreate(['slug' => 'it-jaringan'], ['name' => 'IT & Jaringan (PC, Printer, Internet, Software)']);
        TicketCategory::firstOrCreate(['slug' => 'fasilitas-umum'], ['name' => 'Fasilitas & Umum (AC, Lampu, Meja, Gedung)']);
        
        $categories = TicketCategory::all();
        return view('pelanggan.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:ticket_categories,id',
            'description' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $status = TicketStatus::firstOrCreate(['name' => 'Open'], ['color_code' => '#3b82f6', 'order' => 1]);
        $priority = TicketPriority::firstOrCreate(['name' => 'Medium'], ['sla_hours' => 24, 'color_code' => '#f59e0b']);
        $ticketNumber = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $request->title,
            'description' => $request->description,
            'customer_id' => auth()->id(),
            'category_id' => $request->category_id,
            'priority_id' => $priority->id,
            'status_id' => $status->id,
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/tickets', $filename); 

            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'file_path' => 'tickets/' . $filename,
                'file_name' => $filename,
                'file_type' => 'proof',
            ]);
        }

        return redirect()->route('pelanggan.dashboard')->with('success', 'Laporan berhasil dikirim! Nomor Tiket: ' . $ticketNumber);
    }

    // Menampilkan detail tiket
    public function show($id)
    {
        $ticket = Ticket::with(['category', 'status', 'attachments', 'comments.user'])->findOrFail($id);

        // Pastikan pengguna hanya bisa melihat tiket miliknya sendiri (Keamanan)
        if ($ticket->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('pelanggan.tickets.show', compact('ticket'));
    }

    // Menyimpan balasan/komentar
    public function comment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);

        $ticket = Ticket::findOrFail($id);

        if ($ticket->customer_id !== auth()->id()) {
            abort(403);
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}