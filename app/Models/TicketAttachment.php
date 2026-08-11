<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'file_path',
        'file_name',
        'file_type',
        'user_id',
    ];

    // --- PENGAMAN OTOMATIS (AUTO-FILL USER ID, FILE NAME, & FILE TYPE) ---
    protected static function booted()
    {
        static::creating(function ($attachment) {
            // Otomatis isi user_id jika kosong
            if (empty($attachment->user_id)) {
                $attachment->user_id = auth()->id() ?? 1;
            }
            
            // Otomatis isi file_name dari path jika kosong
            if (empty($attachment->file_name) && !empty($attachment->file_path)) {
                $attachment->file_name = basename($attachment->file_path);
            }

            // Otomatis isi file_type dari ekstensi file jika kosong
            if (empty($attachment->file_type) && !empty($attachment->file_path)) {
                $attachment->file_type = pathinfo($attachment->file_path, PATHINFO_EXTENSION) ?: 'jpg';
            }
        });
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}