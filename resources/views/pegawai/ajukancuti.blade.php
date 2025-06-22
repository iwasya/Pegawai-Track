@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Ajukan Cuti</h4>

    {{-- Form Ajukan Cuti --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/cuti/store" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-2">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" required>
            </div>
            <div class="col-md-4 mb-2">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" required>
            </div>
            <div class="col-md-4 mb-2">
                <label>Jenis Cuti</label>
                <select name="jenis_cuti" id="jenis_cuti" class="form-control" onchange="cekJenisCuti()" required>
                    <option value="">-- Pilih --</option>
                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                    <option value="Cuti Sakit">Cuti Sakit</option>
                    <option value="Cuti Khusus">Cuti Khusus</option>
                </select>
            </div>
        </div>
        <div class="mb-2">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" placeholder="Keterangan (opsional)"></textarea>
        </div>
        <div class="mb-3" id="uploadFoto" style="display: none;">
            <label>Lampiran Bukti Cuti Sakit (Foto)</label>
            <input type="file" name="foto" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Ajukan Cuti</button>
    </form>

    {{-- Riwayat Pengajuan --}}
    <h5>Riwayat Pengajuan Cuti Saya</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cutis as $cuti)
                <tr>
                    <td>{{ $cuti->tanggal_mulai }}</td>
                    <td>{{ $cuti->tanggal_selesai }}</td>
                    <td>{{ $cuti->jenis_cuti }}</td>
                    <td>
                        <span class="badge 
                            {{ $cuti->status == 'Diajukan' ? 'bg-warning' : ($cuti->status == 'Disetujui' ? 'bg-success' : 'bg-danger') }}">
                            {{ $cuti->status }}
                        </span>
                    </td>
                    <td>{{ $cuti->keterangan }}</td>
                    <td>
                        @if($cuti->foto)
                            <a href="{{ asset('storage/' . $cuti->foto) }}" target="_blank">
                                <img src="{{ asset('storage/' . $cuti->foto) }}" width="80">
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td>
                        @if($cuti->status == 'Diajukan')
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $cuti->id_cuti }}">Edit</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal{{ $cuti->id_cuti }}">Hapus</button>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal{{ $cuti->id_cuti }}" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" action="/cuti/update/{{ $cuti->id_cuti }}" enctype="multipart/form-data">
                      @csrf
                      <div class="modal-content">
                        <div class="modal-header"><h5>Edit Cuti</h5></div>
                        <div class="modal-body">
                            <input type="date" name="tanggal_mulai" class="form-control mb-2" value="{{ $cuti->tanggal_mulai }}" required>
                            <input type="date" name="tanggal_selesai" class="form-control mb-2" value="{{ $cuti->tanggal_selesai }}" required>
                            <select name="jenis_cuti" class="form-control mb-2" onchange="cekJenisEdit({{ $cuti->id_cuti }})" id="jenis_edit_{{ $cuti->id_cuti }}">
                                <option {{ $cuti->jenis_cuti == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                <option {{ $cuti->jenis_cuti == 'Cuti Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                                <option {{ $cuti->jenis_cuti == 'Cuti Khusus' ? 'selected' : '' }}>Cuti Khusus</option>
                            </select>
                            <textarea name="keterangan" class="form-control mb-2">{{ $cuti->keterangan }}</textarea>
                            <div class="mb-2" id="upload_edit_{{ $cuti->id_cuti }}" style="{{ $cuti->jenis_cuti == 'Cuti Sakit' ? '' : 'display: none;' }}">
                                <label>Lampiran Foto</label>
                                <input type="file" name="foto" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="hapusModal{{ $cuti->id_cuti }}" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" action="/cuti/delete/{{ $cuti->id_cuti }}">
                      @csrf
                      <div class="modal-content">
                        <div class="modal-header"><h5>Hapus Pengajuan</h5></div>
                        <div class="modal-body">
                            Yakin ingin menghapus pengajuan ini?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada pengajuan cuti.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function cekJenisCuti() {
    const jenis = document.getElementById('jenis_cuti').value;
    document.getElementById('uploadFoto').style.display = jenis === 'Cuti Sakit' ? 'block' : 'none';
}

function cekJenisEdit(id) {
    const jenis = document.getElementById('jenis_edit_' + id).value;
    document.getElementById('upload_edit_' + id).style.display = jenis === 'Cuti Sakit' ? 'block' : 'none';
}
</script>
@endsection
