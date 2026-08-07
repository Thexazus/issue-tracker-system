<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Eager load relationships to prevent N+1 queries
        $query = Ticket::with(['reporter', 'assignee']);

        // Enforce role-based access to list
        if ($user->isDeveloper()) {
            $query->where('assigned_to_id', $user->id);
        } elseif ($user->isQA()) {
            $query->where('reporter_id', $user->id);
        }

        // Apply filters (if present)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        // Only QA and Admin can create tickets
        if (! Auth::user()->isQA() && ! Auth::user()->isAdmin()) {
            abort(403, 'Only QA and Admin roles can create tickets.');
        }

        return view('tickets.create');
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $validated = $request->validated();

        // Handle screenshot upload
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot'] = $path;
        }

        $validated['reporter_id'] = Auth::id();
        $validated['status'] = 'open';

        $ticket = Ticket::create($validated);

        // Record creation in activity log
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => Auth::user()->name." created ticket: {$ticket->ticket_number}",
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->ticket_number} successfully reported!");
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        // Enforce access control for viewing details
        if ($user->isDeveloper() && $ticket->assigned_to_id !== $user->id) {
            abort(403, 'You are not assigned to this ticket.');
        }
        if ($user->isQA() && $ticket->reporter_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'You do not have access to view this ticket.');
        }

        $ticket->load(['reporter', 'assignee', 'comments.user', 'activityLogs.user']);

        // Fetch developers for the dropdown (only Admin can assign)
        $developers = $user->isAdmin() ? User::where('role', 'developer')->get() : collect();

        return view('tickets.show', compact('ticket', 'developers'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        $user = Auth::user();

        // Check if the user is authorized to edit
        if ($user->isDeveloper() && $ticket->assigned_to_id !== $user->id) {
            abort(403, 'You are not assigned to this ticket.');
        }
        if ($user->isQA() && $ticket->reporter_id !== $user->id) {
            abort(403, 'You do not have access to edit this ticket.');
        }

        $developers = $user->isAdmin() ? User::where('role', 'developer')->get() : collect();

        return view('tickets.edit', compact('ticket', 'developers'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;
        $oldAssigneeId = $ticket->assigned_to_id;

        if ($user->isDeveloper()) {
            // Developers can only update the status
            $ticket->status = $validated['status'];
            $ticket->save();

            if ($oldStatus !== $validated['status']) {
                ActivityLog::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'action' => 'status_changed',
                    'description' => "{$user->name} updated status to ".strtoupper($validated['status']),
                    'changes' => ['old' => $oldStatus, 'new' => $validated['status']],
                ]);
            }
        } else {
            // Admins & QAs can update other fields
            if ($request->hasFile('screenshot')) {
                // Delete old screenshot if it exists to save space
                if ($ticket->screenshot) {
                    Storage::disk('public')->delete($ticket->screenshot);
                }
                $path = $request->file('screenshot')->store('screenshots', 'public');
                $validated['screenshot'] = $path;
            }

            $ticket->fill($validated);
            $ticket->save();

            // Track and log changes
            if ($oldStatus !== $ticket->status) {
                ActivityLog::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'action' => 'status_changed',
                    'description' => "{$user->name} changed status to ".strtoupper($ticket->status),
                    'changes' => ['old' => $oldStatus, 'new' => $ticket->status],
                ]);
            }

            if ($oldPriority !== $ticket->priority) {
                ActivityLog::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'action' => 'priority_changed',
                    'description' => "{$user->name} changed priority to ".strtoupper($ticket->priority),
                    'changes' => ['old' => $oldPriority, 'new' => $ticket->priority],
                ]);
            }

            if ($oldAssigneeId != $ticket->assigned_to_id) {
                $newAssigneeName = $ticket->assignee ? $ticket->assignee->name : 'Unassigned';
                ActivityLog::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'action' => 'assigned',
                    'description' => "{$user->name} assigned ticket to: {$newAssigneeName}",
                    'changes' => ['old' => $oldAssigneeId, 'new' => $ticket->assigned_to_id],
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $user = Auth::user();

        // Only Admin, or the QA who reported it (while status is open) can delete
        if ($user->isAdmin() || ($user->isQA() && $ticket->reporter_id === $user->id && $ticket->status === 'open')) {

            // Delete files associated with the ticket
            if ($ticket->screenshot) {
                Storage::disk('public')->delete($ticket->screenshot);
            }

            // Create activity log before deletion
            ActivityLog::create([
                'ticket_id' => null, // Disassociate because of deletion
                'user_id' => $user->id,
                'action' => 'deleted',
                'description' => "{$user->name} deleted ticket: {$ticket->ticket_number} - {$ticket->title}",
            ]);

            $ticket->delete();

            return redirect()->route('tickets.index')
                ->with('success', 'Ticket deleted successfully!');
        }

        abort(403, 'You do not have permission to delete this ticket.');
    }
}
