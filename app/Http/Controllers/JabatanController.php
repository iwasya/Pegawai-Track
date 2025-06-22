<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use App\Exports\JabatanExport;
use Maatwebsite\Excel\Facades\Excel;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $jabatans = Jabatan::when($keyword, function ($query, $keyword) {
            return $query->where('nama_jabatan', 'like', "%$keyword%");
        })->get();

        return view('admin.jabatan', compact('jabatans', 'keyword'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'gaji_pokok' => 'required|numeric',
        ]);

        Jabatan::create($request->all());
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'gaji_pokok' => 'required|numeric',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Jabatan::destroy($id);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
    public function export()
    {
        return Excel::download(new JabatanExport, 'data_jabatan.xlsx');
    }

}
