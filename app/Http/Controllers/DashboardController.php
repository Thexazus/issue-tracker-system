<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard home screen.
     */
    public function index()
    {
        $user = Auth::user();

        // Initialize queries
        $ticketQuery = Ticket::query();
        $logQuery = ActivityLog::with(['user', 'ticket'])->latest();

        // Enforce role-based boundaries on dashboard stats & listings
        if ($user->isDeveloper()) {
            $ticketQuery->where('assigned_to_id', $user->id);
            $logQuery->where(function ($q) use ($user) {
                $q->whereHas('ticket', function ($subQ) use ($user) {
                    $subQ->where('assigned_to_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        } elseif ($user->isQA()) {
            $ticketQuery->where('reporter_id', $user->id);
            $logQuery->where(function ($q) use ($user) {
                $q->whereHas('ticket', function ($subQ) use ($user) {
                    $subQ->where('reporter_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        }

        // Calculate statistics based on filtered query
        $stats = [
            'total' => (clone $ticketQuery)->count(),
            'open' => (clone $ticketQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $ticketQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $ticketQuery)->where('status', 'resolved')->count(),
            'closed' => (clone $ticketQuery)->where('status', 'closed')->count(),
        ];

        // Fetch recent tickets (limit to 5)
        $recentTickets = $ticketQuery->with(['reporter', 'assignee'])
            ->latest()
            ->limit(5)
            ->get();

        // Fetch recent activity logs (limit to 5)
        $recentActivities = $logQuery->limit(5)->get();

        return view('dashboard', compact('stats', 'recentTickets', 'recentActivities'));
    }
}
