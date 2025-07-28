<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Preconnect untuk optimasi loading -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        /* Layout Styles */
        body.logged-in {
            display: flex;
            min-height: 100vh;
        }

        body.guest {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid #334155;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #475569 var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 2px;
        }

        .sidebar .user-info {
            padding: 24px 20px;
            border-bottom: 1px solid #334155;
            text-align: center;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .sidebar .user-info h4 {
            margin-bottom: 16px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .sidebar .user-info img {
            border: 3px solid #475569;
            transition: var(--transition);
            margin-bottom: 12px;
        }

        .sidebar .user-info img:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .sidebar .user-name {
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 4px;
        }

        .sidebar .user-role {
            color: #94a3b8;
            font-size: 0.875rem;
            padding: 4px 12px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            display: inline-block;
        }

        .sidebar .nav {
            padding: 16px 12px;
        }

        .sidebar .nav-item {
            margin-bottom: 4px;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 16px;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link.text-danger {
            color: #ef4444;
            margin-top: 16px;
            border-top: 1px solid #334155;
            padding-top: 16px;
        }

        .sidebar .nav-link.text-danger:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Content Area */
        body.logged-in .content {
            margin-left: 280px;
            flex: 1;
            padding: 0;
            min-height: 100vh;
            background: #f8fafc;
        }

        body.guest .content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            width: 100%;
        }

        body.guest .content main {
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Main Content */
        main {
            padding: 24px;
        }

        /* Clock Widget */
        .clock-widget {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .clock-widget:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .clock-widget i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        /* Mobile Navigation */
        .mobile-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 16px;
            margin-bottom: 0;
        }

        .mobile-nav .btn {
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 8px;
            background: #fff;
            transition: var(--transition);
        }

        .mobile-nav .btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            body.logged-in .content {
                margin-left: 0;
            }

            .clock-widget {
                top: 76px;
                right: 16px;
                font-size: 0.8rem;
                padding: 8px 12px;
            }

            main {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            body.guest .content main {
                margin: 20px;
                padding: 24px;
                border-radius: 12px;
            }

            .sidebar {
                width: 260px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Improved Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Card Improvements */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Performance Optimizations */
        .sidebar .nav-link,
        .btn,
        .card {
            will-change: transform;
        }

        /* Accessibility Improvements */
        .sidebar .nav-link:focus,
        .btn:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Dark mode support preparation */
        @media (prefers-color-scheme: dark) {
            :root {
                --text-color: #f1f5f9;
                --bg-color: #0f172a;
            }
        }
    </style>
</head>
<body class="@auth logged-in @else guest @endauth">
    
    @auth
    <!-- Clock Widget -->
    <div class="clock-widget fade-in" id="clock">
        <i class="bi bi-clock"></i>
        <span id="clockText">00:00:00</span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="user-info">
            <h4>Dashboard</h4>

            @php
                $foto = Auth::user()->pegawai->foto ?? null;
            @endphp

            <img 
                src="{{ $foto ? asset('storage/' . $foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_pengguna ?? 'User') . '&background=3b82f6&color=fff&size=80' }}" 
                class="rounded-circle" 
                alt="Foto Profil" 
                width="80" 
                height="80"
                loading="lazy"
            >

            <div class="user-name">{{ Auth::user()->nama_pengguna ?? 'User' }}</div>
            <div class="user-role">{{ Auth::user()->role ?? 'User' }}</div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>


            @if(Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="/users">
                        <i class="bi bi-people"></i> Kelola User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pegawai*') ? 'active' : '' }}" href="/pegawai">
                        <i class="bi bi-person-lines-fill"></i> Data Pegawai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('absensi*') ? 'active' : '' }}" href="/absensi">
                        <i class="bi bi-check2-square"></i> Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('cuti*') ? 'active' : '' }}" href="/cuti">
                        <i class="bi bi-calendar-check"></i> Pengajuan Cuti
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('jadwal*') ? 'active' : '' }}" href="/jadwal">
                        <i class="bi bi-calendar-event"></i> Jadwal Kerja
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('gaji*') ? 'active' : '' }}" href="/gaji">
                        <i class="bi bi-cash-coin"></i> Data Gaji
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('jabatan*') ? 'active' : '' }}" href="/jabatan">
                        <i class="bi bi-diagram-3"></i> Data Jabatan
                    </a>
                </li>
            @endif

            @if(Auth::user()->role === 'hrd')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pegawai*') ? 'active' : '' }}" href="/pegawai">
                        <i class="bi bi-person-badge"></i> Data Pegawai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('absensi*') ? 'active' : '' }}" href="/absensi">
                        <i class="bi bi-check2-square"></i> Data Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('jabatan*') ? 'active' : '' }}" href="/jabatan">
                        <i class="bi bi-diagram-3"></i> Manajemen Jabatan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('cuti*') ? 'active' : '' }}" href="/cuti">
                        <i class="bi bi-calendar-x"></i> Kelola Cuti
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('gaji*') ? 'active' : '' }}" href="/gaji">
                        <i class="bi bi-cash-stack"></i> Gaji Pegawai
                    </a>
                </li>
            @endif

            @if(Auth::user()->role === 'pegawai')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('absensi/pegawai*') ? 'active' : '' }}" href="/absensi/pegawai">
                        <i class="bi bi-check-circle"></i> Isi Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('cuti/ajukan*') ? 'active' : '' }}" href="/cuti/ajukan">
                        <i class="bi bi-calendar-plus"></i> Ajukan Cuti
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('jadwal-pegawai*') ? 'active' : '' }}" href="/jadwal-pegawai">
                        <i class="bi bi-calendar2-week"></i> Jadwal Kerja
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gaji.saya') ? 'active' : '' }}" href="{{ route('gaji.saya') }}">
                        <i class="bi bi-wallet2"></i> Slip Gaji
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('profil*') ? 'active' : '' }}" href="/profil">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    @endauth

    <!-- Content Area -->
    <div class="content">
        @auth
        <nav class="navbar mobile-nav d-md-none">
            <button class="btn" id="burgerBtn" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
        </nav>
        @endauth

        <main class="fade-in">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        const burgerBtn = document.getElementById('burgerBtn');
        const sidebar = document.getElementById('sidebar');

        if (burgerBtn && sidebar) {
            burgerBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (sidebar.classList.contains('show') && 
                    !sidebar.contains(e.target) && 
                    !burgerBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });
        }

        // Clock functionality
        function updateClock() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeString = now.toLocaleTimeString('id-ID', options);
            const clockElement = document.getElementById('clockText');
            if (clockElement) {
                clockElement.textContent = timeString;
            }
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock();

        // Optimize performance with requestAnimationFrame for smooth animations
        let ticking = false;
        
        function optimizeScroll() {
            if (!ticking) {
                requestAnimationFrame(() => {
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', optimizeScroll);

        // Preload critical resources
        document.addEventListener('DOMContentLoaded', () => {
            // Add fade-in class to elements after DOM is loaded
            document.body.classList.add('fade-in');
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>