@extends('layouts.app')

@section('title', 'Report New Ticket - IT Ticketing System')
@section('page_title', 'Report Ticket')

@section('content')
<div class="mb-4">
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border text-muted">
        <i class="bi bi-arrow-left me-1"></i> Back to Ticket List
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="border-bottom pb-3 mb-4">
                <h4 class="fw-bold text-dark mb-1">Report New Issue</h4>
                <p class="text-muted small mb-0">Report bugs, errors, or revises found to the development team</p>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold text-muted small mb-1">Issue / Bug Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Payment button unresponsive on mobile layout" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold text-muted small mb-1">Issue Description & Steps to Reproduce <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror" placeholder="Explain bug details, expected behavior, and step-by-step reproduction steps..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <!-- Priority -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="priority" class="form-label fw-semibold text-muted small mb-1">Priority <span class="text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low (Minor UI tweaks, typos)</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium (Standard issue, doesn't block main flow)</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (Major flow broken, blocks user operation)</option>
                            <option value="critical" {{ old('priority') === 'critical' ? 'selected' : '' }}>Critical (Server crash, data corruption, security threat)</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Screenshot -->
                    <div class="col-md-6">
                        <label for="screenshot" class="form-label fw-semibold text-muted small mb-1">Screenshot / Proof of Error</label>
                        <input type="file" name="screenshot" id="screenshot" class="form-control @error('screenshot') is-invalid @enderror" accept="image/*">
                        <div class="form-text small text-muted">Formats supported: PNG, JPG, JPEG, GIF. Max: 2MB.</div>
                        @error('screenshot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-top pt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('tickets.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-send-fill me-2"></i> Submit Ticket
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
