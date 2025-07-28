<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Jadwal;
use Carbon\Carbon;

class AbsensiPegawaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        $today = Carbon::today()->toDateString();

        // Ambil jadwal hari ini
        $jadwalHariIni = Jadwal::where('id_pegawai', $pegawai->id_pegawai)
            ->whereDate('tanggal', $today)
            ->first();

        $bolehAbsen = $jadwalHariIni !== null;

        // Cek absensi hari ini
        $absenHariIni = Absensi::where('id_pegawai', $pegawai->id_pegawai)
            ->whereDate('tanggal', $today)
            ->first();

        $sudahMasuk = $absenHariIni && $absenHariIni->jam_masuk !== null;
        $sudahPulang = $absenHariIni && $absenHariIni->jam_pulang !== null;

        $absensi = Absensi::where('id_pegawai', $pegawai->id_pegawai)
            ->with('jadwal')
            ->orderByDesc('tanggal')
            ->get();

        return view('pegawai.absensi', compact(
            'jadwalHariIni',
            'absensi',
            'bolehAbsen',
            'sudahMasuk',
            'sudahPulang'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        $now = Carbon::now();
        $today = $now->toDateString();

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'absen_type' => 'required|in:masuk,pulang',
        ]);

        $absensi = Absensi::firstOrNew([
            'id_pegawai' => $pegawai->id_pegawai,
            'tanggal' => $today,
        ]);

        if ($request->absen_type === 'masuk') {
            if ($absensi->jam_masuk) {
                return back()->with('error', 'Anda sudah absen masuk hari ini.');
            }

            $absensi->id_jadwal = $request->id_jadwal;
            $absensi->jam_masuk = $now->format('H:i:s');
            $absensi->status = 'Hadir';
            $absensi->keterangan = 'Absen Masuk';
        } elseif ($request->absen_type === 'pulang') {
            if (!$absensi->jam_masuk) {
                return back()->with('error', 'Silakan absen masuk terlebih dahulu.');
            }

            if ($absensi->jam_pulang) {
                return back()->with('error', 'Anda sudah absen pulang hari ini.');
            }

            $absensi->jam_pulang = $now->format('H:i:s');
            $absensi->keterangan = 'Absen Pulang';
        }

        $absensi->save();

        return back()->with('success', 'Absensi berhasil disimpan.');
    }
}
