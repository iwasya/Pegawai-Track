@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>📋 Absensi Saya</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah absensi --}}
    @if($bolehAbsen)
        <div class="card mb-4">
            <div class="card-header">Isi Absensi</div>
            <div class="card-body">
                <form action="{{ route('pegawai.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_jadwal" value="{{ $jadwalHariIni->id_jadwal }}">
                    <input type="hidden" name="tanggal" value="{{ $jadwalHariIni->tanggal }}">

                    <div class="mb-3">
                        <label class="form-label">Shift Hari Ini</label>
                        <input type="text" class="form-control" value="{{ $jadwalHariIni->shift }} - {{ $jadwalHariIni->tanggal }}" readonly>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" value="{{ $jadwalHariIni->jam_mulai }}" readonly>
                        </div>
                        <div class="col">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" value="{{ $jadwalHariIni->jam_selesai }}" readonly>
                        </div>
                    </div>

                    @if(!$sudahMasuk)
                        <input type="hidden" name="absen_type" value="masuk">
                        <button type="submit" class="btn btn-success">Absen Masuk</button>
                    @elseif(!$sudahPulang)
                        <input type="hidden" name="absen_type" value="pulang">
                        <button type="submit" class="btn btn-primary">Absen Pulang</button>
                    @endif
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info">Anda sudah absen jam kerja dan jam pulang hari ini.</div>
    @endif

    {{-- Tabel absensi pegawai --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $absen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absen->tanggal }}</td>
                    <td>{{ $absen->jadwal->shift ?? '-' }}</td>
                    <td>{{ $absen->jam_masuk ?? '-' }}</td>
                    <td>{{ $absen->jam_pulang ?? '-' }}</td>
                    <td>{{ $absen->status }}</td>
                    <td>{{ $absen->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">Belum ada data absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
