<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\PegawaiExport;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;

        $pegawais = Pegawai::with('jabatan')
            ->where(function ($query) use ($keyword) {
                $query->where('nama_lengkap', 'like', "%$keyword%")
                      ->orWhere('nip', 'like', "%$keyword%");
            })
            ->latest()
            ->get();

        $jabatans = Jabatan::all();
        $terhapus = Pegawai::onlyTrashed()->get(); // Untuk menampilkan pegawai terhapus

        return view('admin.pegawai', compact('pegawais', 'terhapus', 'keyword', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:pegawai,nip',
            'nama_lengkap' => 'required',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-pegawai', 'public');
        }

        Pegawai::create($data);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required',
            'nama_lengkap' => 'required',
            'id_jabatan' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'no_telepon' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
            'status_kerja' => 'required|in:Magang,Kontrak,Tetap',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pegawai = Pegawai::findOrFail($id);
        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto-pegawai', 'public');
        }

        $pegawai->update($data);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete(); // Soft delete

        return redirect()->back()->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function restore($id)
    {
        $pegawai = Pegawai::withTrashed()->findOrFail($id);
        $pegawai->restore();

        return redirect()->back()->with('success', 'Data pegawai berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $pegawai = Pegawai::withTrashed()->findOrFail($id);

        // Hapus foto jika ada
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->forceDelete(); // Permanent delete

        return redirect()->back()->with('success', 'Data pegawai dihapus secara permanen.');
    }

    public function export()
    {
        return Excel::download(new PegawaiExport, 'data_pegawai.xlsx');
    }
}
