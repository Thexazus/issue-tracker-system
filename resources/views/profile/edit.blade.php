@extends('layouts.app')

@section('title', 'My Profile - IT Ticketing System')
@section('page_title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="border-bottom pb-3 mb-4">
                <h4 class="fw-bold text-dark mb-1">Profile Settings</h4>
                <p class="text-muted small mb-0">Update your account details and password</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar Upload Section -->
                <div class="row mb-4 align-items-center bg-light rounded p-3 mx-0">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        @if($user->avatar)
                            <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle border border-primary border-2 shadow-sm" width="96" height="96" style="object-fit: cover;">
                        @else
                            <div id="avatar-placeholder" class="rounded-circle bg-primary-subtle text-primary fw-bold mx-auto d-flex align-items-center justify-content-center border border-primary border-2 shadow-sm" style="width: 96px; height: 96px; font-size: 2.25rem;">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <img id="avatar-preview" src="" class="rounded-circle border border-primary border-2 shadow-sm d-none" width="96" height="96" style="object-fit: cover;">
                        @endif
                    </div>
                    <div class="col-md-9">
                        <label for="avatar" class="form-label fw-semibold text-muted small mb-1">Profile Picture (Avatar)</label>
                        <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        <div class="form-text small text-muted">Supported formats: PNG, JPG, JPEG, GIF (Max: 2MB).</div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold text-muted small mb-1">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold text-muted small mb-1">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold text-muted small mb-1">New Password (Optional)</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold text-muted small mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Retype new password">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-top pt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-save2-fill me-2"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('avatar').addEventListener('change', function(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
@endsection
