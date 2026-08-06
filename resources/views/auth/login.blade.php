<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Ticketing System</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --bg-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #eff6ff 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient glowing circles */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(85px);
            z-index: 0;
            opacity: 0.65;
        }

        .orb-1 {
            width: 350px;
            height: 350px;
            background: #93c5fd;
            top: -50px;
            left: -50px;
        }

        .orb-2 {
            width: 450px;
            height: 450px;
            background: #bae6fd;
            bottom: -100px;
            right: -100px;
        }

        .login-container {
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 15px;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.08), 0 8px 12px -6px rgba(37, 99, 235, 0.08);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -5px rgba(37, 99, 235, 0.12), 0 12px 20px -6px rgba(37, 99, 235, 0.12);
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: var(--primary-light);
            border-radius: 1rem;
            color: var(--primary-color);
            font-size: 1.85rem;
            margin-bottom: 1.5rem;
            box-shadow: inset 0 2px 4px rgba(37, 99, 235, 0.06);
        }

        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .input-group-text {
            border: 1px solid #cbd5e1;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .form-control:focus ~ .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 0.75rem;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .role-hint {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.825rem;
            color: var(--text-muted);
        }

        .role-badge {
            cursor: pointer;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .role-badge:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <!-- Background Orbs -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="text-center">
                <div class="logo-box">
                    <i class="bi bi-ticket-perforated-fill"></i>
                </div>
                <h4 class="fw-bold mb-1">IT Ticketing System</h4>
                <p class="text-muted mb-4">Masuk untuk mengelola tugas & issue tim</p>
            </div>

            <!-- Toast-like alert messages -->
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center py-2 px-3 border-0 mb-3" style="border-radius: 0.75rem; background-color: #dcfce7; color: #15803d; font-size: 0.9rem;" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center py-2 px-3 border-0 mb-3" style="border-radius: 0.75rem; background-color: #fee2e2; color: #b91c1c; font-size: 0.9rem;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small text-muted mb-1">Email Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted" style="border-radius: 0.75rem 0 0 0.75rem;"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" style="border-radius: 0 0.75rem 0.75rem 0;" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold small text-muted mb-1">Password</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted" style="border-radius: 0.75rem 0 0 0.75rem;"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" style="border-radius: 0 0.75rem 0.75rem 0;" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 mb-4">
                    Masuk Sekarang <i class="bi bi-arrow-right-short ms-1"></i>
                </button>

                <!-- Demo Help Section -->
                <div class="role-hint">
                    <div class="fw-semibold text-dark mb-2" style="font-size: 0.85rem;"><i class="bi bi-info-circle me-1 text-primary"></i> Akun Demo (Klik untuk Autofill)</div>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle role-badge" onclick="fillCredentials('admin@ticketing.com')">Admin</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle role-badge" onclick="fillCredentials('dev1@ticketing.com')">Developer</span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle role-badge" onclick="fillCredentials('qa1@ticketing.com')">QA</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillCredentials(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
