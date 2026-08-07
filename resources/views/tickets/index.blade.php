@extends('layouts.app')

@section('title', 'Ticket List - IT Ticketing System')
@section('page_title', 'Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Ticket List</h4>
        <p class="text-muted small mb-0">List of bug reports, errors, and revises from QA</p>
    </div>
    @if(Auth::user()->isQA() || Auth::user()->isAdmin())
        <a href="{{ route('tickets.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-lg me-2"></i> Report New Issue
        </a>
    @endif
</div>

<!-- Search & Filter Card -->
<div class="card-custom card-custom-hoverable mb-4">
    <form action="{{ route('tickets.index') }}" method="GET" class="row g-3">
        <!-- Search Input -->
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by ticket ID, title, or description..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filter Status -->
        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Statuses --</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <!-- Filter Priority -->
        <div class="col-md-3">
            <select name="priority" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Priorities --</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
            </select>
        </div>

        <!-- Reset Button -->
        <div class="col-md-1 d-grid">
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="card-custom card-custom-hoverable p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light border-bottom">
                <tr>
                    <th class="ps-4 py-3" style="width: 150px;">Ticket ID</th>
                    <th class="py-3">Issue Title</th>
                    <th class="py-3" style="width: 120px;">Priority</th>
                    <th class="py-3" style="width: 140px;">Status</th>
                    <th class="py-3">Reporter</th>
                    <th class="py-3">Assignee</th>
                    <th class="py-3 text-end pe-4" style="width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr style="transition: all 0.2s;">
                        <td class="ps-4 fw-bold text-primary" style="font-size: 0.9rem;">
                            {{ $ticket->ticket_number }}
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ Str::limit($ticket->title, 45) }}</div>
                            <span class="text-muted small d-block">{{ Str::limit($ticket->description, 60) }}</span>
                        </td>
                        <td>
                            <span class="badge priority-{{ $ticket->priority }} small">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-status status-{{ $ticket->status }}">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                        </td>
                        <td class="small text-dark">
                            {{ $ticket->reporter->name }}
                        </td>
                        <td>
                            @if($ticket->assignee)
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ substr($ticket->assignee->name, 0, 2) }}
                                    </div>
                                    <span class="small text-dark">{{ $ticket->assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small italic"><i class="bi bi-person-dash me-1"></i>Unassigned</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-light border" title="Ticket Details">
                                <i class="bi bi-eye text-primary"></i>
                            </a>
                            @if(Auth::user()->isAdmin() || (Auth::user()->isQA() && $ticket->reporter_id === Auth::id()) || (Auth::user()->isDeveloper() && $ticket->assigned_to_id === Auth::id()))
                                <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-light border ms-1" title="Edit Ticket">
                                    <i class="bi bi-pencil-square text-success"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-ticket-detailed fs-1 text-primary-subtle"></i></div>
                            <h5>No tickets found</h5>
                            <p class="small text-muted mb-0">Try changing search filters or report a new issue.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
        <div class="px-4 py-3 border-top bg-light d-flex align-items-center justify-content-between">
            <div class="small text-muted">
                Showing {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets
            </div>
            <div>
                {{ $tickets->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
