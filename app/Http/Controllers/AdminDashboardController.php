<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\akun_user;
use App\Models\Pegawai;
use App\Models\Gaji;
use App\Models\Absensi;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah akun
        $jumlahAkun = akun_user::count();

        // Hitung jumlah pegawai
        $jumlahPegawai = Pegawai::count();

        // Hitung jumlah slip gaji
        $jumlahGaji = Gaji::count();

        // Hitung jumlah total absensi
        $jumlahAbsensi = Absensi::count();

        // Ambil daftar user yang sedang online (sesuaikan dengan logikamu)
        $onlineUsers = akun_user::where('is_online', true)->get();

        // Kirim data ke view dashboard
        return view('admin.dashboard', compact(
            'jumlahAkun',
            'jumlahPegawai',
            'jumlahGaji',
            'jumlahAbsensi',
            'onlineUsers'
        ));
    }
}
