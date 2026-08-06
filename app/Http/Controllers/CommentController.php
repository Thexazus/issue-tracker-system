<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Enforce comment access privileges
        if ($user->isDeveloper() && $ticket->assigned_to_id !== $user->id) {
            abort(403, 'Anda tidak ditugaskan untuk tiket ini.');
        }
        if ($user->isQA() && $ticket->reporter_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ], [
            'content.required' => 'Konten komentar wajib diisi.',
        ]);

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'content' => $validated['content'],
        ]);

        // Log comment activity
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'commented',
            'description' => "{$user->name} memberikan komentar pada tiket ini.",
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Komentar berhasil dikirim!');
    }
}
