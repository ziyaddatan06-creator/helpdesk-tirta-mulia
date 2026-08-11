<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketComment;
use App\Models\TicketAttachment;
use App\Models\TicketStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index() 
    {
        $tickets = Ticket::with(['category', 'status'])->where('customer_id', auth()->id())->latest()->get();
        return view('pelanggan.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::all();
        return view('pelanggan.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:ticket_categories,id',
            'priority_id' => 'required|in:1,2',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $status = TicketStatus::firstOrCreate(
            ['name' => 'Open'],
            ['color_code' => '#3b82f6', 'order' => 1]
        );

        try {
            DB::table('ticket_priorities')->insertOrIgnore([
                ['id' => 1, 'name' => 'Biasa'],
                ['id' => 2, 'name' => 'Darurat']
            ]);
        } catch (\Exception $e) {
            // Abaikan
        }

        $ticket = Ticket::create([
            'ticket_number' => 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'title' => $request->title,
            'description' => $request->description,
            'customer_id' => auth()->id(),
            'category_id' => $request->category_id,
            'status_id' => $status->id,
            'priority_id' => $request->priority_id,
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'file_path' => $path,
            ]);
        }

        return redirect()->route('pelanggan.dashboard')->with('success', 'Laporan keluhan Anda berhasil dikirim ke Tim IT!');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['category', 'status', 'attachments', 'comments.user'])
            ->where('customer_id', auth()->id())->findOrFail($id);
        return view('pelanggan.tickets.show', compact('ticket'));
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);
        $ticket = Ticket::where('customer_id', auth()->id())->findOrFail($id);
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);
        return back()->with('success', 'Pesan terkirim.');
    }

    public function giveRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500'
        ]);

        $ticket = Ticket::where('customer_id', auth()->id())->findOrFail($id);
        
        $ticket->update([
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'body' => "Sistem: Pegawai memberikan rating ⭐ " . $request->rating . "/5 untuk penyelesaian kendala ini. Ulasan: \"" . ($request->review ?? '-') . "\"",
        ]);

        return back()->with('success', 'Terima kasih! Rating dan ulasan Anda telah dikirim untuk evaluasi kinerja Teknisi.');
    }

    public function faq() 
    {
        return view('pelanggan.faq');
    }
}