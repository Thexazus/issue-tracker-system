<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Ticketing System')</title>
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
            --sidebar-width: 260px;
            --bg-color: #f8fafc;
            --text-color: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            height: calc(100vh - 150px);
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu-item:hover, .menu-item.active {
            color: var(--primary-color);
            background-color: var(--primary-light);
        }

        .menu-item i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
        }

        /* Content Area Styling */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .main-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            min-height: 60px;
        }

        .main-content {
            flex-grow: 1;
            padding: 2rem;
        }

        /* Card Styling */
        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Badges */
        .badge-status {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 0.5rem;
        }

        .status-open { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-in_progress { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .status-resolved { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .status-closed { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

        .priority-low { background-color: #f1f5f9; color: #475569; }
        .priority-medium { background-color: #e0f2fe; color: #0369a1; }
        .priority-high { background-color: #ffedd5; color: #c2410c; }
        .priority-critical { background-color: #fee2e2; color: #b91c1c; }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <span class="fs-5 fw-bold text-primary"><i class="bi bi-ticket-perforated-fill me-2"></i>IT Ticketing</span>
            <button class="btn btn-sm d-lg-none" onclick="toggleSidebar()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="{{ route('tickets.index') }}" class="menu-item {{ Request::is('tickets*') ? 'active' : '' }}">
                <i class="bi bi-ticket"></i> Tiket Issue
            </a>
            <a href="{{ route('activity-logs.index') }}" class="menu-item {{ Request::is('activity-logs*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Log Aktivitas
            </a>
            <a href="{{ route('profile.edit') }}" class="menu-item {{ Request::is('profile*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profil Saya
            </a>
            
            <div class="border-top my-3"></div>

            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="menu-item w-100 text-start border-0 bg-transparent text-danger">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <nav class="main-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-light d-lg-none me-3" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="mb-0 fw-semibold">@yield('page_title', 'Dashboard')</h5>
            </div>
            
            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary-subtle text-primary fw-semibold me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold small lh-1">{{ Auth::user()->name }}</div>
                        <span class="text-muted" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 0.75rem;">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button type="submit" form="logout-form" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Toast-like alerts inside container -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 0.75rem; background-color: #dcfce7; color: #15803d;">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 0.75rem; background-color: #fee2e2; color: #b91c1c;">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>
