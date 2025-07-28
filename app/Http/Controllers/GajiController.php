<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Pegawai;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $query = Gaji::with('pegawai');

        if ($request->has('cari')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->cari . '%');
            })->orWhere('no_slip', 'like', '%' . $request->cari . '%');
        }

        $gajis = $query->latest()->get();
        $pegawais = Pegawai::all();

        return view('admin.gaji', compact('gajis', 'pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'bulan_periode' => 'required',
            'tanggal_cetak' => 'required|date'
        ]);

        $idPegawai = $request->id_pegawai;
        $periode = $request->bulan_periode; // contoh: 2025-06
        $periodeNoStrip = str_replace('-', '', $periode);

        // Generate No Slip otomatis
        $jumlahSlip = Gaji::where('bulan_periode', $periode)->count();
        $noSlip = 'SLIP-' . $periodeNoStrip . '-' . str_pad($jumlahSlip + 1, 4, '0', STR_PAD_LEFT);

        // Ambil data pegawai beserta jabatan
        $pegawai = Pegawai::with('jabatan')->findOrFail($idPegawai);
        if (!$pegawai->jabatan || !$pegawai->jabatan->gaji_pokok) {
            return back()->with('error', 'Pegawai belum memiliki jabatan atau gaji pokok belum diatur.');
        }

        $gajiPokok = $pegawai->jabatan->gaji_pokok;

        // Ambil absensi pegawai bulan ini
        $absensi = Absensi::where('id_pegawai', $idPegawai)
            ->whereMonth('tanggal', Carbon::parse($periode)->month)
            ->whereYear('tanggal', Carbon::parse($periode)->year)
            ->get();

        $hadir = $absensi->where('status', 'Hadir')->count();
        $alpha = $absensi->where('status', 'Alpha')->count();

        // Hitung Tunjangan (positif)
        $tunjanganMakan = 20000 * $hadir;
        $transport = 15000 * $hadir;
        $bonus = 0;
        $thr = 0;
        $penghargaan = 0;
        $lembur = 0;

        $totalTunjangan = $tunjanganMakan + $transport + $bonus + $thr + $penghargaan + $lembur;

        // Potongan (positif)
        $potonganAbsensi = 25000 * $alpha;
        $potonganJHT = 50000;
        $pph21 = 25000;

        $totalPendapatan = $gajiPokok + $totalTunjangan;
        $totalPotongan = $potonganAbsensi + $potonganJHT + $pph21;
        $gajiBersih = $totalPendapatan - $totalPotongan;

        // Simpan ke DB
        Gaji::create([
            'id_pegawai' => $idPegawai,
            'bulan_periode' => $periode,
            'no_slip' => $noSlip,
            'tanggal_cetak' => $request->tanggal_cetak,
            'total_pendapatan' => $totalPendapatan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'tunjangan_total' => $totalTunjangan
        ]);

        return back()->with('success', 'Gaji berhasil dihitung dan disimpan.');
    }

    public function update(Request $request, $id)
    {
        $gaji = Gaji::findOrFail($id);

        $request->validate([
            'id_pegawai' => 'required',
            'no_slip' => 'required|unique:gaji,no_slip,' . $gaji->id_gaji . ',id_gaji',
            'bulan_periode' => 'required',
            'tanggal_cetak' => 'required|date',
            'gaji_bersih' => 'required|numeric'
        ]);

        $gaji->update($request->all());
        return back()->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Gaji::findOrFail($id)->delete();
        return back()->with('success', 'Data gaji berhasil dihapus.');
    }

    
}
