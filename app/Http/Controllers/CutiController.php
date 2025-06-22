<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\CutiLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CutiExport;

use Illuminate\Support\Facades\Auth;



class CutiController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $cutis = Cuti::with('pegawai')
            ->when($keyword, function ($query) use ($keyword) {
                return $query->whereHas('pegawai', function ($q) use ($keyword) {
                    $q->where('nama_lengkap', 'like', "%$keyword%");
                });
            })
            ->orderBy('id_cuti', 'desc')
            ->get();

        $pegawais = Pegawai::all();

        return view('admin.cuti', compact('cutis', 'pegawais', 'keyword'));
    }

    public function store(Request $request)
    {
        // Cari id pegawai dari user login
        $pegawai = Pegawai::where('id_user', Auth::user()->id_user)->first();

        $rules = [
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_cuti'       => 'required|in:Cuti Tahunan,Cuti Sakit,Cuti Khusus',
            'keterangan'       => 'nullable|string',
        ];

        if ($request->jenis_cuti === 'Cuti Sakit') {
            $rules['foto'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        } else {
            $rules['foto'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);
        $validated['id_pegawai'] = $pegawai->id_pegawai;
        $validated['status'] = 'Diajukan';

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_cuti', 'public');
            $validated['foto'] = $path;
        }

        Cuti::create($validated);
        return redirect('/cuti/ajukan')->with('success', 'Pengajuan cuti berhasil disimpan.');
    }



    public function update(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);

        $rules = [
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_cuti'       => 'required|in:Cuti Tahunan,Cuti Sakit,Cuti Khusus',
            'keterangan'       => 'nullable|string',
        ];

        if ($request->jenis_cuti === 'Cuti Sakit') {
            $rules['foto'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_cuti', 'public');
            $validated['foto'] = $path;
        }

        $cuti->update($validated);

        if (Auth::user()->role === 'pegawai') {
            return redirect('/cuti/ajukan')->with('success', 'Pengajuan cuti berhasil diperbarui.');
        } else {
            return redirect('/cuti')->with('success', 'Data cuti berhasil diperbarui.');
        }
    }


    public function destroy($id)
    {
        Cuti::destroy($id);

        if (Auth::user()->role === 'pegawai') {
            return redirect('/cuti/ajukan')->with('success', 'Pengajuan cuti berhasil dihapus.');
        } else {
            return redirect('/cuti')->with('success', 'Data cuti berhasil dihapus.');
        }
    }


    public function export()
    {
        return Excel::download(new CutiExport, 'data-cuti.xlsx');
    }

    public function ajukan()
    {
        $user = Auth::user();
        $pegawai = Pegawai::where('id_user', $user->id_user)->first();

        $cutis = Cuti::where('id_pegawai', $pegawai->id_pegawai)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pegawai.ajukancuti', compact('pegawai', 'cutis'));
    }

    public function persetujuan(Request $request, $id)
    {
        $cuti = Cuti::with('pegawai.user')->findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Update status pengajuan cuti
        $cuti->status = $status;
        $cuti->save();

        // Simpan ke tabel cuti_log
        CutiLog::create([
            'id_cuti'        => $cuti->id_cuti,
            'id_user'        => $cuti->pegawai->id_user,
            'id_pegawai'     => $cuti->id_pegawai,
            'tanggal_mulai'  => $cuti->tanggal_mulai,
            'tanggal_selesai'=> $cuti->tanggal_selesai,
            'jenis_cuti'     => $cuti->jenis_cuti,
            'status'         => $cuti->status,
            'keterangan'     => $cuti->keterangan,
            'aksi'           => 'UPDATE',
            'dilakukan_oleh' => Auth::user()->id_user,
            'approved_by'    => Auth::user()->id_user,
        ]);

        return redirect('/cuti')->with('success', 'Status cuti berhasil diperbarui.');
    }

}