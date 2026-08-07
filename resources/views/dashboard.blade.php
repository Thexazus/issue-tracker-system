@extends('layouts.app')

@section('title', 'Dashboard - IT Ticketing System')
@section('page_title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card-custom mb-4 border-0 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);">
    <div class="d-flex align-items-center justify-content-between p-2">
        <div>
            <h4 class="fw-bold mb-1">Welcome, {{ Auth::user()->name }}!</h4>
            <p class="mb-0 opacity-75 small">You are logged in as <strong>{{ ucfirst(Auth::user()->role) }}</strong>. Here is a summary of your system activities today.</p>
        </div>
        <div class="d-none d-md-block fs-1 opacity-25">
            <i class="bi bi-shield-check"></i>
        </div>
    </div>
</div>

<!-- Stats Counter Row -->
<div class="row g-3 mb-4">
    <!-- Open Tickets Stats Card -->
    <div class="col-6 col-lg-3">
        <div class="card-custom card-custom-hoverable h-100 d-flex flex-column justify-content-between py-3 px-4 border-start border-primary border-4">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small fw-semibold">Open Tickets</span>
                <span class="text-primary fs-4"><i class="bi bi-folder2-open"></i></span>
            </div>
            <div class="mt-2">
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['open'] }}</h3>
                <span class="text-muted small">Awaiting resolution</span>
            </div>
        </div>
    </div>

    <!-- In Progress Stats Card -->
    <div class="col-6 col-lg-3">
        <div class="card-custom card-custom-hoverable h-100 d-flex flex-column justify-content-between py-3 px-4 border-start border-warning border-4">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small fw-semibold">In Progress</span>
                <span class="text-warning fs-4"><i class="bi bi-gear-wide-connected"></i></span>
            </div>
            <div class="mt-2">
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['in_progress'] }}</h3>
                <span class="text-muted small">Work in progress</span>
            </div>
        </div>
    </div>

    <!-- Resolved Stats Card -->
    <div class="col-6 col-lg-3">
        <div class="card-custom card-custom-hoverable h-100 d-flex flex-column justify-content-between py-3 px-4 border-start border-success border-4">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small fw-semibold">Resolved</span>
                <span class="text-success fs-4"><i class="bi bi-patch-check"></i></span>
            </div>
            <div class="mt-2">
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['resolved'] }}</h3>
                <span class="text-muted small">Resolved issues</span>
            </div>
        </div>
    </div>

    <!-- Closed Stats Card -->
    <div class="col-6 col-lg-3">
        <div class="card-custom card-custom-hoverable h-100 d-flex flex-column justify-content-between py-3 px-4 border-start border-secondary border-4">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small fw-semibold">Closed</span>
                <span class="text-secondary fs-4"><i class="bi bi-archive"></i></span>
            </div>
            <div class="mt-2">
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['closed'] }}</h3>
                <span class="text-muted small">Verified & closed</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Panel: Recent Tickets -->
    <div class="col-lg-7">
        <div class="card-custom card-custom-hoverable h-100">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-ticket-perforated me-2 text-primary"></i>Recent Tickets</h5>
                <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border small">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead>
                        <tr class="table-light">
                            <th class="py-2">Number</th>
                            <th class="py-2">Title</th>
                            <th class="py-2">Priority</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets as $ticket)
                            <tr onclick="window.location='{{ route('tickets.show', $ticket) }}'" style="cursor: pointer;">
                                <td class="fw-semibold text-primary py-3">{{ $ticket->ticket_number }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ Str::limit($ticket->title, 32) }}</div>
                                    <span class="text-muted small d-block" style="font-size: 0.75rem;">Reporter: {{ $ticket->reporter->name }}</span>
                                </td>
                                <td>
                                    <span class="badge priority-{{ $ticket->priority }} small" style="font-size: 0.7rem;">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-status status-{{ $ticket->status }} small" style="font-size: 0.7rem;">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">
                                    <i class="bi bi-ticket-detailed fs-2 mb-2 d-block text-muted opacity-50"></i>
                                    No tickets reported yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Panel: Recent Activities -->
    <div class="col-lg-5">
        <div class="card-custom card-custom-hoverable h-100">
            <h5 class="fw-bold text-dark border-bottom pb-3 mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Activities</h5>
            
            <div class="position-relative ps-3" style="border-left: 2px solid #e2e8f0; margin-left: 10px;">
                @forelse($recentActivities as $log)
                    <div class="position-relative mb-3 pb-1" style="font-size: 0.85rem;">
                        <span class="position-absolute bg-white text-primary rounded-circle border border-primary d-flex align-items-center justify-content-center" style="width: 14px; height: 14px; left: -18px; top: 4px;">
                            <i class="bi bi-circle-fill" style="transform: scale(0.5);"></i>
                        </span>
                        <div class="fw-semibold text-dark">{{ $log->description }}</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="text-muted small py-4 text-center">
                        <i class="bi bi-activity fs-2 mb-2 d-block text-muted opacity-50"></i>
                        No activity history yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
