@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Untuk ADMIN --}}
    @if(in_array(Auth::user()->role, ['admin', 'hrd']))

        <h4 class="mb-4">📊 Dashboard Admin</h4>

        <div class="row">
            <!-- Kartu Jumlah Akun -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-dark shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">Jumlah Akun</h6>
                        <h3>{{ $jumlahAkun }}</h3>
                    </div>
                </div>
            </div>

            <!-- Kartu Jumlah Pegawai -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">Jumlah Pegawai</h6>
                        <h3>{{ $jumlahPegawai }}</h3>
                    </div>
                </div>
            </div>

            <!-- Kartu Jumlah Slip Gaji -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">Slip Gaji</h6>
                        <h3>{{ $jumlahGaji }}</h3>
                    </div>
                </div>
            </div>

            <!-- Kartu Jumlah Absensi -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">Total Absensi</h6>
                        <h3>{{ $jumlahAbsensi }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="card mt-4">
            <div class="card-header bg-light">
                📈 Grafik Jumlah Akun dan Pegawai
            </div>
            <div class="card-body">
                <canvas id="userPegawaiChart" height="100"></canvas>
            </div>
        </div>

        {{-- Daftar User Online --}}
        <div class="container mt-5 mb-4">
            <h5 class="mb-3">Daftar User yang Sedang Aktif:</h5>

            @if($onlineUsers->count() > 0)
                <div class="row g-3">
                    @foreach($onlineUsers as $onlineUser)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">
                                        {{ $onlineUser->nama_pengguna }}
                                        <span class="badge 
                                            @if($onlineUser->role === 'admin') bg-danger 
                                            @elseif($onlineUser->role === 'hrd') bg-primary 
                                            @else bg-secondary 
                                            @endif">
                                            {{ ucfirst($onlineUser->role) }}
                                        </span>
                                    </h6>
                                    <p class="card-text text-success mb-1">
                                        <strong>Sedang aktif</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted fst-italic">Tidak ada user yang aktif saat ini.</p>
            @endif
        </div>

    {{-- Untuk PEGAWAI --}}
    @elseif(Auth::user()->role === 'pegawai')
        <div class="alert alert-success">
            <h4>Selamat datang, {{ Auth::user()->nama_pengguna ?? Auth::user()->name }}! 👋</h4>
            <p>Anda masuk sebagai <strong>Pegawai</strong>. Silakan akses menu di sidebar untuk melihat data slip gaji, absensi, atau informasi lainnya.</p>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <p><strong>Nama Lengkap:</strong> {{ Auth::user()->nama_pengguna ?? '-' }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Role:</strong> Pegawai</p>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if(in_array(Auth::user()->role, ['admin', 'hrd']))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userPegawaiChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Akun', 'Pegawai'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $jumlahAkun }}, {{ $jumlahPegawai }}],
                backgroundColor: ['#343a40', '#007bff'],
                borderColor: ['#343a40', '#007bff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
