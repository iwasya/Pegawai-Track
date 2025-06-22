<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Jangan lupa import DB
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $users = User::with('pegawai')->get(); // jika kamu pakai relasi
        $pegawaiList = Pegawai::whereNull('id_user')->get(); // hanya pegawai yang belum punya user


        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('nama_pengguna', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('role', 'like', "%$search%");
            });
        }

        // Ambil semua user sesuai filter
        $users = $query->get();

        // Ambil user yang online dengan join ke log aktivitas untuk mendapatkan ip_address terbaru
        $onlineUsers = DB::table('akun_user')
        ->where('akun_user.is_online', true)
        ->leftJoin('log_aktivitas_pengguna', function ($join) {
            $join->on('akun_user.id_user', '=', 'log_aktivitas_pengguna.id_user')
                ->where('log_aktivitas_pengguna.id_logaktiv', function ($query) {
                    $query->select(DB::raw('MAX(id_logaktiv)'))
                        ->from('log_aktivitas_pengguna as sub')
                        ->whereColumn('sub.id_user', 'akun_user.id_user');
                });
        })
        ->select('akun_user.*', 'log_aktivitas_pengguna.ip_address')
        ->get();

        return view('admin.users', compact('users', 'onlineUsers',  'pegawaiList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'role' => 'required|in:admin,hrd,pegawai',
            'email' => 'required|email',
            'status_akun' => 'required',
            'password' => 'nullable|min:6',
            'id_pegawai' => 'nullable|exists:pegawai,id_pegawai',
        ]);

        // Jika id_pegawai dipilih, ambil data dari pegawai
        if ($request->filled('id_pegawai')) {
            $pegawai = Pegawai::findOrFail($request->id_pegawai);
            $data['nama_pengguna'] = $pegawai->nama_lengkap;
            $data['email'] = $pegawai->email; // ⬅️ Email otomatis dari pegawai
        } else {
            $data['nama_pengguna'] = $request->nama_pengguna;
            // email tetap pakai input manual
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user = User::create($data);

        // Hubungkan user ke pegawai
        if (isset($pegawai)) {
            $pegawai->id_user = $user->id_user;
            $pegawai->save();
        }

        return redirect('/users')->with('success', 'User berhasil ditambahkan.');
    }


    public function update(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        $data = $request->validate([
            'username' => 'required',
            'nama_pengguna' => 'required',
            'role' => 'required|in:admin,hrd,pegawai',
            'email' => 'required|email',
            'status_akun' => 'required',
            'password' => 'nullable|min:6',
        ]);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect('/users')->with('success', 'User berhasil diubah.');
    }

    public function destroy($id)
    {
        // Hapus log aktivitas yang terkait user ini
        DB::table('log_aktivitas_pengguna')->where('id_user', $id)->delete();

        // Hapus user-nya
        User::destroy($id);

        return redirect('/users')->with('success', 'User berhasil dihapus.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_detail', compact('user'));
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
