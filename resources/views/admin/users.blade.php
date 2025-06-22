@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 fw-bold text">
        <i class="bi bi-people-fill me-2"></i> Kelola User
    </h3>

    <div class="d-flex mb-3 gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal" onclick="openAddUserModal()">
            <i class="bi bi-plus-circle me-1"></i> Tambah
        </button>
        <a href="{{ route('users.export') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Export
        </a>
    </div>

    <form method="GET" action="{{ url('/users') }}" class="d-flex align-items-center gap-2 mb-3">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Cari nama, email, Role, atau username..." 
            value="{{ request('search') }}"
        >
        @if(request('search'))
            <a href="{{ url('/users') }}" class="btn btn-light border">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        @endif
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i>
        </button>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Last Login</th>
                <th>Role</th>
                <th>Status Akun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $u->nama_pengguna }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->last_login ?? '-' }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td>{{ ucfirst($u->status_akun) }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editUserModal"
                        data-id="{{ $u->id_user }}"
                        data-username="{{ $u->username }}"
                        data-nama="{{ $u->nama_pengguna }}"
                        data-email="{{ $u->email }}"
                        data-role="{{ $u->role }}"
                        data-status="{{ $u->status_akun }}"
                        onclick="fillEditModal(this)">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <form action="{{ url('/users/' . $u->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(Auth::user()->role === 'admin')
<div class="container mb-4">
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
                            <p class="card-text text-muted mb-0">
                                <small>IP: {{ $onlineUser->ip_address ?? '-' }}</small>
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
@endif


<!-- Modal Tambah -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addUserForm" method="POST" action="{{ url('/users') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

              <!-- Pilih Pegawai -->
              <select name="id_pegawai" id="id_pegawai" class="form-control mb-2">
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach ($pegawaiList as $pegawai)
                      <option value="{{ $pegawai->id_pegawai }}"
                          data-nama="{{ $pegawai->nama_lengkap }}"
                          data-email="{{ $pegawai->email }}">
                          {{ $pegawai->nama_lengkap }}
                      </option>
                  @endforeach
              </select>

              <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

              <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control mb-2" placeholder="Nama Pengguna" readonly required>

              <input type="email" name="email" id="email" class="form-control mb-2" placeholder="Email" readonly required>

              <select name="role" class="form-control mb-2" required>
                  <option value="" disabled selected>Pilih Role</option>
                  <option value="admin">Admin</option>
                  <option value="hrd">HRD</option>
                  <option value="pegawai">Pegawai</option>
              </select>

              <select name="status_akun" class="form-control mb-2" required>
                  <option value="" disabled selected>Pilih Status</option>
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Nonaktif</option>
              </select>

              <input type="password" name="password" class="form-control mb-2" placeholder="Password">

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editUserForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_user" id="edit_id_user">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-2">
                  <label for="edit_username" class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" id="edit_username" required>
              </div>
              <div class="mb-2">
                  <label for="edit_nama_pengguna" class="form-label">Nama Pengguna</label>
                  <input type="text" name="nama_pengguna" class="form-control" id="edit_nama_pengguna" required>
              </div>
              <div class="mb-2">
                  <label for="edit_email" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="edit_email" required>
              </div>
              <div class="mb-2">
                  <label for="edit_role" class="form-label">Role</label>
                  <select name="role" class="form-control" id="edit_role" required>
                      <option value="">Pilih Role</option>
                      <option value="admin">Admin</option>
                      <option value="hrd">HRD</option>
                      <option value="pegawai">Pegawai</option>
                  </select>
              </div>
              <div class="mb-2">
                  <label for="edit_status_akun" class="form-label">Status Akun</label>
                  <select name="status_akun" class="form-control" id="edit_status_akun" required>
                      <option value="">Pilih Status</option>
                      <option value="aktif">Aktif</option>
                      <option value="nonaktif">Nonaktif</option>
                  </select>
              </div>
              <div class="mb-2">
                  <label for="edit_password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="edit_password" placeholder="Kosongkan jika tidak ingin ubah password">
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script>
    const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
    const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));

    function openAddUserModal() {
        document.getElementById('addUserForm').reset();
        addUserModal.show();
    }

    function fillEditModal(button) {
        const form = document.getElementById('editUserForm');
        form.action = `/users/${button.dataset.id}`;
        document.getElementById('edit_id_user').value = button.dataset.id;
        document.getElementById('edit_username').value = button.dataset.username;
        document.getElementById('edit_nama_pengguna').value = button.dataset.nama;
        document.getElementById('edit_email').value = button.dataset.email;
        document.getElementById('edit_role').value = button.dataset.role;
        document.getElementById('edit_status_akun').value = button.dataset.status;
        document.getElementById('edit_password').value = '';
        editUserModal.show();
    }
</script>
<script>
    document.getElementById('id_pegawai').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const nama = selectedOption.getAttribute('data-nama');
        const email = selectedOption.getAttribute('data-email');

        document.getElementById('nama_pengguna').value = nama || '';
        document.getElementById('email').value = email || '';
    });
</script>

@endsection
