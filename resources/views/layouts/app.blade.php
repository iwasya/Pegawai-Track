<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Style -->
    <style>
        body.logged-in {
            display: flex;
        }

        body.guest {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: transform 0.3s ease;
        }

        .sidebar .user-info {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #495057;
        }

        .sidebar .nav-link {
            color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
        }

        body.logged-in .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
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
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .clock-fixed {
            position: fixed;
            top: 10px;
            right: 20px;
            z-index: 1100;
            color: #000;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: #f8f9fa;
            padding: 6px 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            body.logged-in .content {
                margin-left: 0;
            }
        }
    </style>
    
</head>
<body class="@auth logged-in @else guest @endauth">
    
    @auth
    <!-- Clock -->
    <div class="clock-fixed" id="clock">
        <i class="bi bi-clock"></i>
        <span id="clockText">00:00:00</span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="user-info">
            <h4 class="fw-bold">Hello</h4>

            @php
                $foto = Auth::user()->pegawai->foto ?? null;
            @endphp

            <img 
                src="{{ $foto ? asset('storage/' . $foto) : 'https://via.placeholder.com/80' }}" 
                class="rounded-circle mb-2" 
                alt="User Photo" 
                width="80" height="80"
            >

            <div>{{ Auth::user()->nama_pengguna ?? 'User' }}</div>
            <div>{{ Auth::user()->role ?? 'User' }}</div>
        </div>
        <ul class="nav flex-column p-3">
            <li class="nav-item"><a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>

            @if(Auth::user()->role === 'admin')
        <li class="nav-item"><a class="nav-link" href="/users"><i class="bi bi-people"></i> Kelola User</a></li>
        <li class="nav-item"><a class="nav-link" href="/pegawai"><i class="bi bi-person-lines-fill"></i> Data Pegawai</a></li>
        <li class="nav-item"><a class="nav-link" href="/absensi"><i class="bi bi-check2-square"></i> Absensi</a></li>
        <li class="nav-item"><a class="nav-link" href="/cuti"><i class="bi bi-calendar-check"></i> Pengajuan Cuti</a></li>
        <li class="nav-item"><a class="nav-link" href="/jadwal"><i class="bi bi-calendar-event"></i> Jadwal Kerja</a></li>
        <li class="nav-item"><a class="nav-link" href="/gaji"><i class="bi bi-cash-coin"></i> Data Gaji</a></li>
        <li class="nav-item"><a class="nav-link" href="/jabatan"><i class="bi bi-diagram-3"></i> Data Jabatan</a></li>
        <li class="nav-item"><a class="nav-link" href="/laporan"><i class="bi bi-clipboard-data"></i> Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="/pengaturan"><i class="bi bi-gear"></i> Pengaturan Sistem</a></li>
    @endif

    @if(Auth::user()->role === 'hrd')
        <li class="nav-item"><a class="nav-link" href="/pegawai"><i class="bi bi-person-badge"></i> Data Pegawai</a></li>
        <li class="nav-item"><a class="nav-link" href="/absensi"><i class="bi bi-check2-square"></i> Data Absensi</a></li>
        <li class="nav-item"><a class="nav-link" href="/jabatan"><i class="bi bi-diagram-3"></i> Manajemen Jabatan</a></li>
        <li class="nav-item"><a class="nav-link" href="/cuti"><i class="bi bi-calendar-x"></i> Kelola Cuti</a></li>
        <li class="nav-item"><a class="nav-link" href="/gaji"><i class="bi bi-cash-stack"></i> Gaji Pegawai</a></li>
        <li class="nav-item"><a class="nav-link" href="/laporan"><i class="bi bi-bar-chart"></i> Laporan HRD</a></li>
    @endif

    @if(Auth::user()->role === 'pegawai')
        <li class="nav-item"><a class="nav-link" href="/absensi"><i class="bi bi-check-circle"></i> Isi Absensi</a></li>
        <li class="nav-item"><a class="nav-link" href="/cuti/ajukan"><i class="bi bi-calendar-plus"></i> Ajukan Cuti</a></li>
        <li class="nav-item"><a class="nav-link" href="/jadwal-pegawai"><i class="bi bi-calendar2-week"></i> Jadwal Kerja</a></li>
        <li class="nav-item"><a class="nav-link" href="/gaji/saya"><i class="bi bi-wallet2"></i> Slip Gaji</a></li>
        <li class="nav-item"><a class="nav-link" href="/profil"><i class="bi bi-person-circle"></i> Profil Saya</a></li>
    @endif

            <li class="nav-item mt-2">
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

    <!-- Konten -->
    <div class="content">
        <!-- Toggle Button for Mobile -->
        @auth
        <nav class="navbar navbar-light bg-light d-md-none">
            <button class="btn btn-outline-secondary" id="burgerBtn">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
        @endauth

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        const burgerBtn = document.getElementById('burgerBtn');
        const sidebar = document.getElementById('sidebar');

        burgerBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (
                sidebar.classList.contains('show') &&
                !sidebar.contains(e.target) &&
                !burgerBtn.contains(e.target)
            ) {
                sidebar.classList.remove('show');
            }
        });

        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clockText').innerText = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
