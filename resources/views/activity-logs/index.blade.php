@extends('layouts.app')

@section('title', 'Activity Logs - IT Ticketing System')
@section('page_title', 'Activity Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Activity Logs</h4>
        <p class="text-muted small mb-0">System audit trail of all ticket modifications and team interactions</p>
    </div>
</div>

<!-- Filter Card -->
<div class="card-custom card-custom-hoverable mb-4">
    <form action="{{ route('activity-logs.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-4">
            <label for="action" class="form-label fw-semibold text-muted small mb-1">Filter by Action</label>
            <select name="action" id="action" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Actions --</option>
                <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Ticket Created</option>
                <option value="assigned" {{ request('action') === 'assigned' ? 'selected' : '' }}>Ticket Assigned</option>
                <option value="status_changed" {{ request('action') === 'status_changed' ? 'selected' : '' }}>Status Changed</option>
                <option value="priority_changed" {{ request('action') === 'priority_changed' ? 'selected' : '' }}>Priority Changed</option>
                <option value="commented" {{ request('action') === 'commented' ? 'selected' : '' }}>Comment Added</option>
                <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Ticket Deleted</option>
            </select>
        </div>
        <div class="col-md-2 mt-md-4 d-grid">
            <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm py-2">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
            </a>
        </div>
    </form>
</div>

<!-- Log List Card -->
<div class="card-custom card-custom-hoverable p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
            <thead class="table-light border-bottom">
                <tr>
                    <th class="ps-4 py-3" style="width: 180px;">Timestamp</th>
                    <th class="py-3" style="width: 180px;">Actor (User)</th>
                    <th class="py-3" style="width: 140px;">Action Category</th>
                    <th class="py-3">Log Description</th>
                    <th class="py-3" style="width: 150px;">Related Ticket</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 text-muted" style="font-size: 0.85rem;">
                            {{ $log->created_at->format('M d, Y H:i:s') }}
                            <div class="small opacity-75">({{ $log->created_at->diffForHumans() }})</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $log->user->name }}</div>
                            <span class="badge bg-secondary-subtle text-secondary py-0 px-2 fw-semibold" style="font-size: 0.65rem;">{{ strtoupper($log->user->role) }}</span>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->action) {
                                    'created' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    'assigned' => 'bg-info-subtle text-info border-info-subtle',
                                    'status_changed' => 'bg-warning-subtle text-warning border-warning-subtle',
                                    'priority_changed' => 'bg-warning-subtle text-warning border-warning-subtle',
                                    'commented' => 'bg-success-subtle text-success border-success-subtle',
                                    'deleted' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} border small px-2">
                                {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </td>
                        <td>
                            <div class="text-dark fw-medium">{{ $log->description }}</div>
                            @if($log->changes)
                                <div class="bg-light p-2 rounded mt-1 border" style="font-size: 0.75rem; max-width: 500px; line-height: 1.4;">
                                    <span class="text-muted fw-semibold d-block mb-1">Data Mutation Details:</span>
                                    <div class="d-flex align-items-center">
                                        <code class="text-danger bg-danger-subtle px-1 rounded">{{ is_array($log->changes['old']) ? json_encode($log->changes['old']) : ($log->changes['old'] ?: 'NULL') }}</code>
                                        <span class="mx-2 text-muted">&rarr;</span>
                                        <code class="text-success bg-success-subtle px-1 rounded">{{ is_array($log->changes['new']) ? json_encode($log->changes['new']) : ($log->changes['new'] ?: 'NULL') }}</code>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($log->ticket)
                                <a href="{{ route('tickets.show', $log->ticket) }}" class="fw-semibold text-decoration-none">
                                    <i class="bi bi-ticket-perforated me-1"></i>{{ $log->ticket->ticket_number }}
                                </a>
                            @else
                                <span class="text-muted small italic"><i class="bi bi-trash3 me-1"></i>Ticket Deleted</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-journal-x fs-1 text-muted opacity-50"></i></div>
                            <h5>No activity logs found</h5>
                            <p class="small text-muted mb-0">All matching system activities will be recorded here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="px-4 py-3 border-top bg-light d-flex align-items-center justify-content-between">
            <div class="small text-muted">
                Showing {{ $logs->firstItem() }} - {{ $logs->lastItem() }} of {{ $logs->total() }} logs
            </div>
            <div>
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
