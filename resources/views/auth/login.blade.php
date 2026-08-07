<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Ticketing System</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #f0f6ff;
            --bg-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #eff6ff 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
            margin: 0;
            padding: 15px;
        }

        /* Ambient glowing circles */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
            opacity: 0.7;
        }

        .orb-1 {
            width: 380px;
            height: 380px;
            background: #93c5fd;
            top: -60px;
            left: -60px;
        }

        .orb-2 {
            width: 480px;
            height: 480px;
            background: #bae6fd;
            bottom: -100px;
            right: -100px;
        }

        .login-container {
            z-index: 10;
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            padding: 2.25rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -6px rgba(0, 0, 0, 0.04);
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border: 1px solid #dbeafe;
            border-radius: 0.75rem;
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        h4 {
            letter-spacing: -0.025em;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.65rem 0.85rem;
            font-size: 0.925rem;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-group-text {
            border: 1px solid var(--border-color);
            background-color: #f8fafc;
            color: var(--text-muted);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Demo Panel Custom Styling */
        .demo-panel {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .demo-card {
            cursor: pointer;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.65rem 0.5rem;
            transition: all 0.15s ease-in-out;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .demo-card:hover {
            border-color: var(--primary-color);
            background-color: var(--primary-light);
        }

        .demo-card i {
            font-size: 1.1rem;
            margin-bottom: 0.15rem;
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
                <h4 class="text-dark mb-1">IT Ticketing System</h4>
                <p class="text-muted small mb-4">Sign in to manage team tasks & issues</p>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center py-2 px-3 border-0 mb-3" style="border-radius: 0.5rem; background-color: #dcfce7; color: #15803d; font-size: 0.85rem;" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center py-2 px-3 border-0 mb-3" style="border-radius: 0.5rem; background-color: #fee2e2; color: #b91c1c; font-size: 0.85rem;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label small mb-1">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0" style="border-radius: 0.5rem 0 0 0.5rem;"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@email.com" style="border-radius: 0 0.5rem 0.5rem 0;" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="form-label small mb-1">Password</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0" style="border-radius: 0.5rem 0 0 0.5rem;"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" style="border-radius: 0 0.5rem 0.5rem 0;" required>
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
                            Remember me on this device
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 mb-4">
                    Sign In <i class="bi bi-arrow-right-short ms-1"></i>
                </button>

                <!-- Demo Help Section -->
                <div class="demo-panel">
                    <div class="fw-semibold text-dark mb-2" style="font-size: 0.8rem;">
                        <i class="bi bi-info-circle-fill me-1 text-primary"></i> Demo Accounts (Click to Autofill)
                    </div>
                    <div class="row g-2">
                        <!-- Admin Card -->
                        <div class="col-4">
                            <div class="demo-card" onclick="fillCredentials('rae@ticketing.com')">
                                <i class="bi bi-shield-lock-fill text-primary"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.725rem;">Admin</span>
                                <span class="text-muted d-block text-truncate w-100" style="font-size: 0.6rem;">rae@ticketing.com</span>
                            </div>
                        </div>
                        <!-- Developer Card -->
                        <div class="col-4">
                            <div class="demo-card" onclick="fillCredentials('alex@ticketing.com')">
                                <i class="bi bi-code-slash text-success"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.725rem;">Developer</span>
                                <span class="text-muted d-block text-truncate w-100" style="font-size: 0.6rem;">alex@ticketing.com</span>
                            </div>
                        </div>
                        <!-- QA Card -->
                        <div class="col-4">
                            <div class="demo-card" onclick="fillCredentials('sarah@ticketing.com')">
                                <i class="bi bi-patch-exclamation-fill text-info"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.725rem;">QA</span>
                                <span class="text-muted d-block text-truncate w-100" style="font-size: 0.6rem;">sarah@ticketing.com</span>
                            </div>
                        </div>
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
