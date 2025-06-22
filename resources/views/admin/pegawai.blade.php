@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Data Pegawai</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3 align-items-center">
        <div class="col-md-6 mb-2 mb-md-0 d-flex justify-content-start" style="gap: 0.5rem;">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Pegawai</button>
            <a href="{{ route('pegawai.export') }}" class="btn btn-success">Export</a>
        </div>
        <div class="col-md-6">
            <form method="GET" class="d-flex justify-content-end" style="gap: 0.5rem;">
                <input type="text" name="search" value="{{ $keyword }}" placeholder="Cari Pegawai..." class="form-control">
                <button class="btn btn-secondary">Cari</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-nowrap">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Tanggal Masuk</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pegawais as $p)
                <tr>
                    <td class="text-center">{{ $loop->iteration}}</td>
                    <td>{{ $p->nip }}</td>
                    <td>{{ $p->nama_lengkap }}</td>
                    <td>{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
                    <td>{{ $p->jenis_kelamin }}</td>
                    <td>{{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $p->tanggal_masuk ? \Carbon\Carbon::parse($p->tanggal_masuk)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $p->no_telepon }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->status_kerja }}</td>
                    <td>
                        @if ($p->foto)
                            <img src="{{ asset('storage/' . $p->foto) }}" width="80" class="rounded">
                        @else
                            <span class="text-muted">Tidak ada foto</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button 
                            class="btn btn-warning btn-sm mb-1" 
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit"
                            onclick="isiEdit({{ $p }})">Edit</button>

                        <form action="{{ route('pegawai.destroy', $p->id_pegawai) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Riwayat Pegawai Terhapus --}}
    <div class="mt-5">
        <h4>Riwayat Pegawai yang Telah Dihapus</h4>
        @if ($terhapus->isEmpty())
            <div class="alert alert-info">Tidak ada data pegawai yang terhapus.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th>Status Kerja</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terhapus as $t)
                        <tr class="table-warning">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $t->nip }}</td>
                            <td>{{ $t->nama_lengkap }}</td>
                            <td>{{ $t->jabatan->nama_jabatan ?? '-' }}</td>
                            <td>{{ $t->email }}</td>
                            <td>{{ $t->status_kerja }}</td>
                            <td class="text-center">
                                <form action="{{ route('pegawai.restore', $t->id_pegawai) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Pulihkan data ini?')">Restore</button>
                                </form>
                                <form action="{{ route('pegawai.forceDelete', $t->id_pegawai) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus permanen data ini?')">Hapus Permanen</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Modal Tambah dan Edit tetap sama --}}

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header"><h5>Tambah Pegawai</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6"><input name="nip" class="form-control" placeholder="NIP" required></div>
                <div class="col-md-6"><input name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required></div>
                <div class="col-md-6">
                    <select name="id_jabatan" class="form-control @error('id_jabatan') is-invalid @enderror">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatans as $j)
                            <option value="{{ $j->id_jabatan }}" {{ old('id_jabatan', $pegawai->id_jabatan ?? '') == $j->id_jabatan ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <select name="jenis_kelamin" class="form-control">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6"><input name="tanggal_lahir" type="date" class="form-control"></div>
                <div class="col-md-6"><input name="tanggal_masuk" type="date" class="form-control"></div>
                <div class="col-md-6"><input name="no_telepon" class="form-control" placeholder="No. Telepon"></div>
                <div class="col-md-6"><input name="email" class="form-control" placeholder="Email"></div>
                <div class="col-12"><textarea name="alamat" class="form-control" placeholder="Alamat"></textarea></div>
                <div class="col-md-6">
                    <select name="status_kerja" class="form-control">
                        <option value="">-- Pilih Status Kerja --</option>
                        <option value="Magang">Magang</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Tetap">Tetap</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="foto" class="form-label">Foto Pegawai</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="formEdit" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="modal-content">
          <div class="modal-header"><h5>Edit Pegawai</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6"><input name="nip" id="edit_nip" class="form-control" required></div>
                <div class="col-md-6"><input name="nama_lengkap" id="edit_nama" class="form-control" required></div>
                <div class="col-md-6">
                    <select name="id_jabatan" id="edit_jabatan" class="form-control" >
                        @foreach ($jabatans as $j) 
                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option> 
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="jenis_kelamin" id="edit_kelamin" class="form-control">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6"><input name="tanggal_lahir" id="edit_lahir" type="date" class="form-control"></div>
                <div class="col-md-6"><input name="tanggal_masuk" id="edit_masuk" type="date" class="form-control"></div>
                <div class="col-md-6"><input name="no_telepon" id="edit_telp" class="form-control"></div>
                <div class="col-md-6"><input name="email" id="edit_email" class="form-control"></div>
                <div class="col-12"><textarea name="alamat" id="edit_alamat" class="form-control"></textarea></div>
                <div class="col-md-6">
                    <select name="status_kerja" id="edit_status" class="form-control">
                        <option value="">-- Pilih Status Kerja --</option>
                        <option value="Magang">Magang</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Tetap">Tetap</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="edit_foto" class="form-label">Foto Pegawai</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success">Update</button>
          </div>
        </div>
    </form>
  </div>
</div>
<script>
function isiEdit(data) {
    document.getElementById('edit_nip').value = data.nip;
    document.getElementById('edit_nama').value = data.nama_lengkap;
    document.getElementById('edit_jabatan').value = data.id_jabatan;
    document.getElementById('edit_kelamin').value = data.jenis_kelamin;
    document.getElementById('edit_lahir').value = data.tanggal_lahir;
    document.getElementById('edit_masuk').value = data.tanggal_masuk;
    document.getElementById('edit_telp').value = data.no_telepon;
    document.getElementById('edit_email').value = data.email;
    document.getElementById('edit_alamat').value = data.alamat;
    document.getElementById('edit_status').value = data.status_kerja;

    let url = "{{ url('pegawai') }}/" + data.id_pegawai;
    document.getElementById('formEdit').action = url;
}
</script>
@endsection
