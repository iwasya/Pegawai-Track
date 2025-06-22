@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Data Jadwal Kerja</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6 d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Jadwal</button>
            <a href="{{ route('jadwal.export') }}" class="btn btn-success">Export</a>
            <a href="{{ route('jadwal.generate') }}" class="btn btn-outline-primary"
                onclick="return confirm('Yakin generate ulang jadwal minggu ini untuk semua pegawai?')">
                🔁 Generate Mingguan
            </a>
            <form action="{{ route('jadwal.hapus-semua') }}" method="POST" onsubmit="return confirm('Yakin hapus semua data jadwal?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">🗑 Hapus Semua Jadwal</button>
            </form>


        </div>
        <div class="col-md-6">
            <form method="GET" class="d-flex justify-content-end gap-2">
                <input type="text" name="search" value="{{ $keyword }}" placeholder="Cari nama pegawai..." class="form-control">
                <button class="btn btn-secondary">Cari</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->pegawai->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('l') }}</td>
                    <td>{{ $j->tanggal }}</td>
                    <td>{{ $j->shift }}</td>
                    <td>{{ $j->jam_mulai }}</td>
                    <td>{{ $j->jam_selesai }}</td>
                    <td>{{ $j->keterangan }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modalEdit" onclick="isiEdit({{ $j }})">Edit</button>
                        <form action="{{ route('jadwal.destroy', $j->id_jadwal) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


{{-- Modal Tambah Jadwal --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header"><h5>Tambah Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body row g-3">
              <div class="col-md-6">
                  <label>Pegawai</label>
                  <select name="id_pegawai" class="form-control">
                      <option value="">-- Pilih Pegawai --</option>
                      @foreach($pegawais as $p)
                        <option value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Hari</label>
                  <select name="hari" class="form-control">
                      <option value="">-- Pilih Hari --</option>
                      <option value="Senin">Senin</option>
                      <option value="Selasa">Selasa</option>
                      <option value="Rabu">Rabu</option>
                      <option value="Kamis">Kamis</option>
                      <option value="Jumat">Jumat</option>
                      <option value="Sabtu">Sabtu</option>
                      <option value="Minggu">Minggu</option>
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control">
              </div>
              <div class="col-md-6">
                  <label>Shift</label>
                  <select name="shift" class="form-control">
                      <option value="Pagi">Pagi</option>
                      <option value="Siang">Siang</option>
                      <option value="Sore">Sore</option>
                      <option value="Malam">Malam</option>
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Jam Mulai</label>
                  <input type="time" name="jam_mulai" class="form-control">
              </div>
              <div class="col-md-6">
                  <label>Jam Selesai</label>
                  <input type="time" name="jam_selesai" class="form-control">
              </div>
              <div class="col-md-12">
                  <label>Keterangan</label>
                  <textarea name="keterangan" class="form-control"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>

{{-- Modal Edit Jadwal --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="formEdit">
        @csrf @method('PUT')
        <div class="modal-content">
          <div class="modal-header"><h5>Edit Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body row g-3">
              <div class="col-md-6">
                  <label>Pegawai</label>
                  <select name="id_pegawai" id="edit_pegawai" class="form-control">
                      @foreach($pegawais as $p)
                        <option value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Hari</label>
                  <select name="hari" id="edit_hari" class="form-control">
                      <option value="Senin">Senin</option>
                      <option value="Selasa">Selasa</option>
                      <option value="Rabu">Rabu</option>
                      <option value="Kamis">Kamis</option>
                      <option value="Jumat">Jumat</option>
                      <option value="Sabtu">Sabtu</option>
                      <option value="Minggu">Minggu</option>
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" id="edit_tanggal" class="form-control">
              </div>
              <div class="col-md-6">
                  <label>Shift</label>
                  <select name="shift" id="edit_shift" class="form-control">
                      <option value="Pagi">Pagi</option>
                      <option value="Siang">Siang</option>
                      <option value="Sore">Sore</option>
                      <option value="Malam">Malam</option>
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Jam Mulai</label>
                  <input type="time" name="jam_mulai" id="edit_mulai" class="form-control">
              </div>
              <div class="col-md-6">
                  <label>Jam Selesai</label>
                  <input type="time" name="jam_selesai" id="edit_selesai" class="form-control">
              </div>
              <div class="col-md-12">
                  <label>Keterangan</label>
                  <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success">Update</button>
          </div>
        </div>
    </form>
  </div>
</div>
{{-- Script Modal --}}
<script>
function isiEdit(data) {
    document.getElementById('edit_pegawai').value = data.id_pegawai;
    document.getElementById('edit_tanggal').value = data.tanggal;
    document.getElementById('edit_shift').value = data.shift;
    document.getElementById('edit_mulai').value = data.jam_mulai;
    document.getElementById('edit_selesai').value = data.jam_selesai;
    document.getElementById('edit_keterangan').value = data.keterangan;

    let url = "{{ url('jadwal') }}/" + data.id_jadwal;
    document.getElementById('formEdit').action = url;
}
</script>
@endsection
