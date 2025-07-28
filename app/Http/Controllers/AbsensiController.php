<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        // Ambil data absensi lengkap dengan relasi pegawai dan jadwal
        $absensi = Absensi::with(['pegawai', 'jadwal'])->latest()->get();

        // Ambil semua pegawai
        $pegawai = Pegawai::all();

        // Ambil semua jadwal lengkap, pastikan ada field id_pegawai untuk filter nanti
        $jadwal = Jadwal::select('id_jadwal', 'id_pegawai', 'shift', 'tanggal', 'jam_mulai', 'jam_selesai')->get();

        // Kirim ke view
        return view('admin.absensi', compact('absensi', 'pegawai', 'jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_jadwal'  => 'required|exists:jadwal,id_jadwal',
            'tanggal'    => 'required|date',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'tuker_sift' => 'nullable|in:1,2,3',
            'status'     => 'required|in:Hadir,Sakit,Izin,Alpha,Cuti',
            'keterangan' => 'nullable|string',
        ]);

        Absensi::create($request->all());

        return redirect()->back()->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_jadwal'  => 'required|exists:jadwal,id_jadwal',
            'tanggal'    => 'required|date',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'tuker_sift' => 'nullable|in:1,2,3',
            'status'     => 'required|in:Hadir,Sakit,Izin,Alpha,Cuti',
            'keterangan' => 'nullable|string',
        ]);

        $absen = Absensi::findOrFail($id);

        $absen->update($request->all());

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diupdate');
    }

    public function destroy($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->back()->with('success', 'Data absensi berhasil dihapus.');
    }
}
