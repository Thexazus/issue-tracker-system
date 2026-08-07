<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ticket_number',
    'title',
    'description',
    'status',
    'priority',
    'screenshot',
    'reporter_id',
    'assigned_to_id',
])]
class Ticket extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        static::creating(function ($ticket) {
            $date = now()->format('Ymd');
            // Find the latest ticket created today
            $latestTicket = static::where('ticket_number', 'like', "TKT-{$date}-%")
                ->latest('id')
                ->first();

            $sequence = 1;
            if ($latestTicket) {
                $parts = explode('-', $latestTicket->ticket_number);
                $lastSequence = (int) end($parts);
                $sequence = $lastSequence + 1;
            }

            $ticket->ticket_number = 'TKT-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
        });
    }

    // Relationships
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }
}
