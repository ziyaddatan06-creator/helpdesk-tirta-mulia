<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number', 
        'title', 
        'description', 
        'customer_id', 
        'technician_id', 
        'priority_id', 
        'category_id', 
        'status_id',
        'rating', 
        'review'
    ];

    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
    public function category() { return $this->belongsTo(TicketCategory::class); }
    public function status() { return $this->belongsTo(TicketStatus::class); }
    public function attachments() { return $this->hasMany(TicketAttachment::class); }
    public function comments() { return $this->hasMany(TicketComment::class); }

    public function getPriorityNameAttribute() {
        return $this->priority_id == 2 ? 'Darurat' : 'Biasa';
    }
    
    public function getPriorityColorAttribute() {
        return $this->priority_id == 2 
            ? 'bg-red-100 text-red-700 border-red-200 animate-pulse' 
            : 'bg-gray-100 text-gray-600 border-gray-200';
    }
}