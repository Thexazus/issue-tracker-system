<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of system activity logs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Eager load relations to avoid N+1 queries
        $query = ActivityLog::with(['user', 'ticket'])->latest();

        // Enforce role-based access limits on log visibility
        if ($user->isDeveloper()) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('ticket', function ($subQ) use ($user) {
                    $subQ->where('assigned_to_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        } elseif ($user->isQA()) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('ticket', function ($subQ) use ($user) {
                    $subQ->where('reporter_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        }

        // Filter by action type (if specified)
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('activity-logs.index', compact('logs'));
    }
}
