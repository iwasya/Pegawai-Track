@extends('layouts.app')

@section('content')
@php
    $role = Auth::user()->role;
@endphp

<div class="container">
    <h3 class="mb-4"><i class="bi bi-calendar-check"></i> Pengajuan Cuti</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/cuti" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Cari pegawai...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                    <a href="/cuti/export" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
                    @if($role !== 'hrd')
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Foto</th>
                    @if($role !== 'hrd')
                        <th>Status</th>
                        <th>Aksi</th>
                    @else
                        <th>Status</th>
                        <th>Persetujuan</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($cutis as $cuti)
                <tr>
                    <td>{{ $cuti->pegawai->nama_lengkap ?? '-' }}</td>
                    <td>{{ $cuti->tanggal_mulai }}</td>
                    <td>{{ $cuti->tanggal_selesai }}</td>
                    <td>{{ $cuti->jenis_cuti }}</td>
                    <td>{{ $cuti->keterangan }}</td>
                    <td class="text-center">
                        @if($cuti->foto)
                            <img src="{{ asset('storage/' . $cuti->foto) }}" alt="Foto Cuti" class="img-thumbnail" style="max-width: 100px;">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    @if($role !== 'hrd')
                        <td>
                            <span class="badge 
                                @if($cuti->status === 'Diajukan') bg-warning 
                                @elseif($cuti->status === 'Disetujui') bg-success 
                                @else bg-danger @endif">
                                {{ $cuti->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $cuti->id_cuti }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $cuti->id_cuti }}">Hapus</button>
                            </div>
                        </td>
                    @else
                        <td>
                            <span class="badge 
                                @if($cuti->status === 'Diajukan') bg-warning 
                                @elseif($cuti->status === 'Disetujui') bg-success 
                                @else bg-danger @endif">
                                {{ $cuti->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($cuti->status === 'Diajukan')
                                <form method="POST" action="{{ route('cuti.persetujuan', $cuti->id_cuti) }}">
                                    @csrf
                                    <button type="submit" name="status" value="Disetujui" class="btn btn-sm btn-success">Setujui</button>
                                    <button type="submit" name="status" value="Ditolak" class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            @else
                                <small><i>Aksi telah dilakukan</i></small>
                            @endif
                        </td>
                    @endif
                </tr>

                @if($role !== 'hrd')
                {{-- Modal Edit --}}
                <div class="modal fade" id="editModal{{ $cuti->id_cuti }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="/cuti/update/{{ $cuti->id_cuti }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Cuti</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label>Nama Pegawai</label>
                                    <select name="id_pegawai" class="form-control mb-2" required>
                                        @foreach($pegawais as $p)
                                            <option value="{{ $p->id_pegawai }}" {{ $cuti->id_pegawai == $p->id_pegawai ? 'selected' : '' }}>
                                                {{ $p->nama_lengkap }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control mb-2" value="{{ $cuti->tanggal_mulai }}" required>
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control mb-2" value="{{ $cuti->tanggal_selesai }}" required>
                                    <label>Jenis Cuti</label>
                                    <select name="jenis_cuti" class="form-control mb-2">
                                        <option {{ $cuti->jenis_cuti == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                        <option {{ $cuti->jenis_cuti == 'Cuti Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                                        <option {{ $cuti->jenis_cuti == 'Cuti Khusus' ? 'selected' : '' }}>Cuti Khusus</option>
                                    </select>
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control">{{ $cuti->keterangan }}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Hapus --}}
                <div class="modal fade" id="hapusModal{{ $cuti->id_cuti }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="/cuti/delete/{{ $cuti->id_cuti }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Yakin ingin menghapus data cuti ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($role !== 'hrd')
{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="/cuti/store" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengajuan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Pegawai</label>
                    <select name="id_pegawai" class="form-control mb-2" required>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control mb-2" required>
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control mb-2" required>
                    <label>Jenis Cuti</label>
                    <select name="jenis_cuti" class="form-control mb-2">
                        <option>Cuti Tahunan</option>
                        <option>Cuti Sakit</option>
                        <option>Cuti Khusus</option>
                    </select>
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control mb-2" placeholder="Keterangan..."></textarea>
                    <label>Lampiran Foto (khusus Cuti Sakit)</label>
                    <input type="file" name="foto" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
