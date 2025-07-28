<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gaji;
use App\Models\Absensi;
use Carbon\Carbon;
use PDF;

class SlipGajiController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        // Ambil semua slip gaji pegawai
        $gajis = Gaji::where('id_pegawai', $pegawai->id_pegawai)
                    ->orderByDesc('tanggal_cetak')
                    ->get();

        // Loop setiap slip gaji dan hitung absensi per bulan slip tersebut
        foreach ($gajis as $gaji) {
            $periode = $gaji->bulan_periode; // contoh: "2025-06"
            $year = date('Y', strtotime($periode));
            $month = date('m', strtotime($periode));

            $absensi = \App\Models\Absensi::where('id_pegawai', $pegawai->id_pegawai)
                        ->whereYear('tanggal', $year)
                        ->whereMonth('tanggal', $month)
                        ->get();

            // Hitung jumlah status absensi
            $gaji->absen_hadir = $absensi->where('status', 'hadir')->count();
            $gaji->absen_izin  = $absensi->where('status', 'izin')->count();
            $gaji->absen_sakit = $absensi->where('status', 'sakit')->count();
            $gaji->absen_cuti  = $absensi->where('status', 'cuti')->count();
            $gaji->absen_alpha = $absensi->where('status', 'alpha')->count();
        }

        return view('pegawai.slip_gaji', compact('gajis'));
    }
    public function downloadPdf($id)
{
    $pegawai = Auth::user()->pegawai;
    $gaji = Gaji::with(['pegawai.jabatan'])->where('id_gaji', $id)->where('id_pegawai', $pegawai->id_pegawai)->firstOrFail();

    $pdf = PDF::loadView('pegawai.slip_gaji_pdf', compact('gaji'))->setPaper('A4', 'portrait');
    return $pdf->download('slip_gaji_'.$gaji->no_slip.'.pdf');
}

}
