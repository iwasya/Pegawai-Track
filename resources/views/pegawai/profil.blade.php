@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3><i class="bi bi-person-circle"></i> Profil Saya</h3>
    <hr>

    @if ($pegawai)
    <div class="row">
        <div class="col-md-4 text-center">
            @if ($pegawai->foto)
                <img src="{{ asset('storage/' . $pegawai->foto) }}" class="img-thumbnail mb-3" style="max-width: 200px;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama_lengkap) }}" class="img-thumbnail mb-3" style="max-width: 200px;">
            @endif
        </div>
        <div class="col-md-8">
            <table class="table table-bordered">
                <tr><th>NIP</th><td>{{ $pegawai->nip }}</td></tr>
                <tr><th>Nama Lengkap</th><td>{{ $pegawai->nama_lengkap }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                <tr><th>Tanggal Lahir</th><td>{{ $pegawai->tanggal_lahir }}</td></tr>
                <tr><th>Email</th><td>{{ $pegawai->email }}</td></tr>
                <tr><th>No. Telepon</th><td>{{ $pegawai->no_telepon }}</td></tr>
                <tr><th>Alamat</th><td>{{ $pegawai->alamat }}</td></tr>
                <tr><th>Jabatan</th><td>{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</td></tr>
                <tr><th>Status Kerja</th><td>{{ $pegawai->status_kerja }}</td></tr>
                <tr><th>Tanggal Masuk</th><td>{{ $pegawai->tanggal_masuk }}</td></tr>
            </table>
        </div>
    </div>
    @else
        <div class="alert alert-warning">Data pegawai tidak ditemukan.</div>
    @endif
</div>
@endsection
