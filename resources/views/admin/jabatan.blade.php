@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manajemen Jabatan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex" style="gap: 0.5rem;">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal" title="Tambah Jabatan">
                <i class="bi bi-plus-lg">Tambah</i>
            </button>
            <a href="{{ route('jabatan.export') }}" class="btn btn-success" title="Export ke Excel">
                <i class="bi bi-file-earmark-excel">Export</i>
            </a>
        </div>

        <form method="GET" class="d-flex" style="gap: 0.5rem;">
            <input type="text" name="search" value="{{ $keyword ?? '' }}" placeholder="Cari Jabatan..." class="form-control" style="width: 250px;">
            <button type="submit" class="btn btn-secondary" title="Cari">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jabatan</th>
                <th>Gaji Pokok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jabatans as $jabatan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $jabatan->nama_jabatan }}</td>
                <td>Rp {{ number_format($jabatan->gaji_pokok, 0, ',', '.') }}</td>
                <td>
                    <button 
                        class="btn btn-warning btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal" 
                        data-id="{{ $jabatan->id_jabatan }}" 
                        data-nama="{{ $jabatan->nama_jabatan }}" 
                        data-gaji="{{ $jabatan->gaji_pokok }}"
                        onclick="isiFormEdit(this)"
                        title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <form action="{{ route('jabatan.destroy', $jabatan->id_jabatan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Hapus">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('jabatan.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Jabatan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <input type="text" name="nama_jabatan" class="form-control mb-2" placeholder="Nama Jabatan" required>
              <input type="number" name="gaji_pokok" class="form-control" placeholder="Gaji Pokok" required>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit" method="POST">
        @csrf @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Jabatan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <input type="text" id="edit_nama" name="nama_jabatan" class="form-control mb-2" required>
              <input type="number" id="edit_gaji" name="gaji_pokok" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success">Update</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script>
function isiFormEdit(button) {
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    const gaji = button.getAttribute('data-gaji');

    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_gaji').value = gaji;

    const form = document.getElementById('formEdit');
    form.action = `/jabatan/${id}`;
}
</script>
@endsection
