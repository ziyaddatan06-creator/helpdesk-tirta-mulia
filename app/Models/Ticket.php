<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    
    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'sla_due_date' => 'datetime',
        'is_sla_breached' => 'boolean',
    ];

    // Relasi ke User
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
    
    // Relasi ke Master Data (Kategori, Status, Prioritas)
    public function category() { return $this->belongsTo(TicketCategory::class); }
    public function subcategory() { return $this->belongsTo(TicketSubcategory::class); }
    public function priority() { return $this->belongsTo(TicketPriority::class); }
    public function status() { return $this->belongsTo(TicketStatus::class); }
    
    // Relasi ke detail tiket lainnya
    public function comments() { return $this->hasMany(TicketComment::class); }
    public function attachments() { return $this->hasMany(TicketAttachment::class); }
    public function histories() { return $this->hasMany(TicketHistory::class); }
}