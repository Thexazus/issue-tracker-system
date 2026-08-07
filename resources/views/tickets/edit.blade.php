@extends('layouts.app')

@section('title', 'Edit Ticket - IT Ticketing System')
@section('page_title', 'Edit Ticket')

@section('content')
<div class="mb-4">
    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-light border text-muted">
        <i class="bi bi-arrow-left me-1"></i> Back to Ticket Details
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="border-bottom pb-3 mb-4">
                <h4 class="fw-bold text-dark mb-1">Edit Ticket {{ $ticket->ticket_number }}</h4>
                <p class="text-muted small mb-0">Update ticket details according to your role ({{ ucfirst(Auth::user()->role) }})</p>
            </div>

            <form action="{{ route('tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if(Auth::user()->isDeveloper())
                    <!-- Developer View: Only show Status -->
                    <div class="alert alert-info border-0 mb-4" style="border-radius: 0.75rem; background-color: #eff6ff; color: #1e40af;">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        As a <strong>Developer</strong>, you are only authorized to change the progress status of this ticket.
                    </div>
                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold text-muted small mb-1">Progress Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                @else
                    <!-- Admin & QA View: Full Form -->
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold text-muted small mb-1">Issue / Bug Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $ticket->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold text-muted small mb-1">Issue Description & Steps to Reproduce <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <!-- Priority -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="priority" class="form-label fw-semibold text-muted small mb-1">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority', $ticket->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $ticket->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $ticket->priority) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('priority', $ticket->priority) === 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold text-muted small mb-1">Ticket Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="open" {{ old('status', $ticket->status) === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $ticket->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ old('status', $ticket->status) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ old('status', $ticket->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <!-- Assignee (Admin only) -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="assigned_to_id" class="form-label fw-semibold text-muted small mb-1">Assigned To (Developer)</label>
                            @if(Auth::user()->isAdmin())
                                <select name="assigned_to_id" id="assigned_to_id" class="form-select @error('assigned_to_id') is-invalid @enderror">
                                    <option value="">-- Unassigned --</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->id }}" {{ old('assigned_to_id', $ticket->assigned_to_id) == $dev->id ? 'selected' : '' }}>
                                            {{ $dev->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $ticket->assignee ? $ticket->assignee->name : 'Unassigned' }}" disabled>
                                <input type="hidden" name="assigned_to_id" value="{{ $ticket->assigned_to_id }}">
                            @endif
                            @error('assigned_to_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Screenshot -->
                        <div class="col-md-6">
                            <label for="screenshot" class="form-label fw-semibold text-muted small mb-1">Update Screenshot (Optional)</label>
                            <input type="file" name="screenshot" id="screenshot" class="form-control @error('screenshot') is-invalid @enderror" accept="image/*">
                            <div class="form-text small text-muted">Leave empty to keep current screenshot.</div>
                            @error('screenshot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="border-top pt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-save-fill me-2"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
