@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">📋 Data Absensi Pegawai</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- Pesan error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Absensi</button>

    {{-- Tabel Absensi --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $absen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absen->pegawai->nama_lengkap ?? '-' }}</td>
                    <td>{{ $absen->tanggal }}</td>
                    <td>{{ $absen->jam_masuk }}</td>
                    <td>{{ $absen->jam_pulang }}</td>
                    <td><span class="badge bg-info">{{ $absen->status }}</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $absen->id_absen }}">Edit</button>
                        <form action="{{ route('absensi.destroy', $absen->id_absen) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $absen->id_absen }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $absen->id_absen }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('absensi.update', $absen->id_absen) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditLabel{{ $absen->id_absen }}">Edit Absensi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="pegawai{{ $absen->id_absen }}" class="form-label">Pegawai</label>
                                        <select name="id_pegawai" id="pegawai{{ $absen->id_absen }}" class="form-select select-pegawai" required>
                                            <option disabled>Pilih Pegawai</option>
                                            @foreach($pegawai as $p)
                                            <option value="{{ $p->id_pegawai }}" {{ $absen->id_pegawai == $p->id_pegawai ? 'selected' : '' }}>
                                                {{ $p->nama_lengkap }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jadwal{{ $absen->id_absen }}" class="form-label">Jadwal</label>
                                        <select name="id_jadwal" id="jadwal{{ $absen->id_absen }}" class="form-select select-jadwal" required>
                                            <option disabled>Pilih Jadwal</option>
                                            @foreach($jadwal as $j)
                                            <option value="{{ $j->id_jadwal }}" data-tanggal="{{ $j->tanggal }}" data-jam-mulai="{{ $j->jam_mulai }}" data-jam-selesai="{{ $j->jam_selesai }}" data-id-pegawai="{{ $j->id_pegawai }}" {{ $absen->id_jadwal == $j->id_jadwal ? 'selected' : '' }}>
                                                {{ $j->shift }} - {{ $j->tanggal }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal{{ $absen->id_absen }}" class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal" id="tanggal{{ $absen->id_absen }}" class="form-control" value="{{ $absen->tanggal }}" required>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="jam_masuk{{ $absen->id_absen }}" class="form-label">Jam Masuk</label>
                                            <input type="time" name="jam_masuk" id="jam_masuk{{ $absen->id_absen }}" class="form-control" value="{{ $absen->jam_masuk }}">
                                        </div>
                                        <div class="col">
                                            <label for="jam_pulang{{ $absen->id_absen }}" class="form-label">Jam Pulang</label>
                                            <input type="time" name="jam_pulang" id="jam_pulang{{ $absen->id_absen }}" class="form-control" value="{{ $absen->jam_pulang }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tuker_sift{{ $absen->id_absen }}" class="form-label">Tuker Sift</label>
                                        <select name="tuker_sift" id="tuker_sift{{ $absen->id_absen }}" class="form-select">
                                            <option value="">Tidak</option>
                                            <option value="1" {{ $absen->tuker_sift == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ $absen->tuker_sift == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ $absen->tuker_sift == '3' ? 'selected' : '' }}>3</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status{{ $absen->id_absen }}" class="form-label">Status</label>
                                        <select name="status" id="status{{ $absen->id_absen }}" class="form-select" required>
                                            <option disabled>Pilih Status</option>
                                            @foreach(['Hadir','Sakit','Izin','Alpha','Cuti'] as $st)
                                            <option value="{{ $st }}" {{ $absen->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan{{ $absen->id_absen }}" class="form-label">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan{{ $absen->id_absen }}" class="form-control">{{ $absen->keterangan }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="pegawaiTambah" class="form-label">Pegawai</label>
                        <select name="id_pegawai" id="pegawaiTambah" class="form-select select-pegawai" required>
                            <option disabled selected>Pilih Pegawai</option>
                            @foreach($pegawai as $p)
                            <option value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jadwalTambah" class="form-label">Jadwal</label>
                        <select name="id_jadwal" id="jadwalTambah" class="form-select select-jadwal" required>
                            <option disabled selected>Pilih Jadwal</option>
                            @foreach($jadwal as $j)
                            <option value="{{ $j->id_jadwal }}" data-tanggal="{{ $j->tanggal }}" data-jam-mulai="{{ $j->jam_mulai }}" data-jam-selesai="{{ $j->jam_selesai }}" data-id-pegawai="{{ $j->id_pegawai }}">
                                {{ $j->shift }} - {{ $j->tanggal }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tanggalTambah" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggalTambah" class="form-control" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="jam_masukTambah" class="form-label">Jam Masuk</label>
                            <input type="time" name="jam_masuk" id="jam_masukTambah" class="form-control">
                        </div>
                        <div class="col">
                            <label for="jam_pulangTambah" class="form-label">Jam Pulang</label>
                            <input type="time" name="jam_pulang" id="jam_pulangTambah" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tuker_siftTambah" class="form-label">Tuker Sift</label>
                        <select name="tuker_sift" id="tuker_siftTambah" class="form-select">
                            <option value="">Tidak</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="statusTambah" class="form-label">Status</label>
                        <select name="status" id="statusTambah" class="form-select" required>
                            <option disabled selected>Pilih Status</option>
                            @foreach(['Hadir','Sakit','Izin','Alpha','Cuti'] as $st)
                            <option value="{{ $st }}">{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="keteranganTambah" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keteranganTambah" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateFormByPegawai(selectPegawai) {
        const pegawaiId = selectPegawai.value;
        const form = selectPegawai.closest('form');
        const jadwalSelect = form.querySelector('.select-jadwal');
        const tanggalInput = form.querySelector('input[name="tanggal"]');
        const jamMasukInput = form.querySelector('input[name="jam_masuk"]');
        const jamPulangInput = form.querySelector('input[name="jam_pulang"]');

        // Filter option jadwal sesuai pegawai terpilih
        Array.from(jadwalSelect.options).forEach(option => {
            option.style.display = option.dataset.idPegawai == pegawaiId ? '' : 'none';
        });

        // Reset jadwal yang dipilih
        jadwalSelect.value = '';
        tanggalInput.value = '';
        jamMasukInput.value = '';
        jamPulangInput.value = '';
    }

    // Update form saat pilih pegawai
    document.querySelectorAll('.select-pegawai').forEach(selectPegawai => {
        selectPegawai.addEventListener('change', function () {
            updateFormByPegawai(this);
        });
    });

    // Update tanggal, jam masuk & jam pulang saat pilih jadwal
    document.querySelectorAll('.select-jadwal').forEach(selectJadwal => {
        selectJadwal.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const form = this.closest('form');
            const tanggalInput = form.querySelector('input[name="tanggal"]');
            const jamMasukInput = form.querySelector('input[name="jam_masuk"]');
            const jamPulangInput = form.querySelector('input[name="jam_pulang"]');

            tanggalInput.value = selectedOption.dataset.tanggal || '';
            jamMasukInput.value = selectedOption.dataset.jamMulai || '';
            jamPulangInput.value = selectedOption.dataset.jamSelesai || '';
        });
    });
});
</script>
@endsection
