@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">💰 Data Gaji Pegawai</h4>

    {{-- Tombol Tambah Gaji --}}
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Gaji</button>

    {{-- Form Pencarian --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama pegawai / no slip..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>
    </form>

    {{-- Tabel Data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Pegawai</th>
                    <th>No Slip</th>
                    <th>Periode</th>
                    <th>Tanggal Cetak</th>
                    <th>Gaji Bersih</th>
                    <th>Jumlah Potongan</th>
                    <th>Jumlah Pendapatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gajis as $gaji)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $gaji->pegawai->nama_lengkap ?? '-' }}</td>
                    <td>{{ $gaji->no_slip }}</td>
                    <td>{{ $gaji->bulan_periode }}</td>
                    <td>{{ $gaji->tanggal_cetak }}</td>
                    <td>Rp{{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</td>
                    <td>
                        @if(Auth::user()->role !== 'hrd')
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $gaji->id_gaji }}">Edit</button>
                        @endif
                        <form action="{{ route('gaji.destroy', $gaji->id_gaji) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="modalEdit{{ $gaji->id_gaji }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('gaji.update', $gaji->id_gaji) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Gaji</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row g-3">
                                    <div class="col-md-6">
                                        <label>Pegawai</label>
                                        <select name="id_pegawai" class="form-select" readonly required>
                                            @foreach($pegawais as $p)
                                                <option value="{{ $p->id_pegawai }}" {{ $p->id_pegawai == $gaji->id_pegawai ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>No Slip</label>
                                        <input type="text" name="no_slip" class="form-control" value="{{ $gaji->no_slip }}" readonly required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Bulan Periode</label>
                                        <input type="month" name="bulan_periode" class="form-control" value="{{ $gaji->bulan_periode }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Tanggal Cetak</label>
                                        <input type="date" name="tanggal_cetak" class="form-control" value="{{ $gaji->tanggal_cetak }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Gaji Bersih</label>
                                        <input type="number" name="gaji_bersih" class="form-control" value="{{ $gaji->gaji_bersih }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('gaji.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Gaji Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Nama Pegawai</label>
                        <select name="id_pegawai" class="form-select" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($pegawais as $p)
                                <option value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>No Slip Gaji</label>
                        <input type="text" name="no_slip" class="form-control" readonly required>
                    </div>
                    <div class="col-md-6">
                        <label>Bulan Periode</label>
                        <input type="month" name="bulan_periode" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Cetak</label>
                        <input type="date" name="tanggal_cetak" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Hitung & Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
