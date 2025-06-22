<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Exports\JadwalExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;
        $jadwals = Jadwal::with('pegawai')
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('pegawai', function ($q) use ($keyword) {
                    $q->where('nama_lengkap', 'like', "%$keyword%");
                });
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $pegawais = Pegawai::all();
        return view('admin.jadwal', compact('jadwals', 'pegawais', 'keyword'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'tanggal' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Sore,Malam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable'
        ]);

        Jadwal::create($request->all());
        return back()->with('success', '✅ Jadwal berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pegawai' => 'required',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'tanggal' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Sore,Malam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable'
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($request->all());

        return back()->with('success', '✅ Jadwal berhasil diperbarui');
    }

    public function destroy($id)
    {
        Jadwal::destroy($id);
        return back()->with('success', '🗑️ Jadwal berhasil dihapus');
    }

    public function hapusSemua()
    {
        \App\Models\Jadwal::query()->delete(); // Ini benar, bukan static call
        return back()->with('success', '✅ Semua data jadwal berhasil dihapus.');
    }




    public function export()
    {
        return Excel::download(new JadwalExport, 'jadwal.xlsx');
    }

    public function generateMingguan()
    {
        $shiftJam = [
            'Pagi' => ['07:50', '12:00'],
            'Siang' => ['13:00', '17:00'],
            'Sore' => ['17:00', '21:00'],
            'Malam' => ['21:00', '01:00'],
        ];

        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $today = Carbon::now()->startOfWeek(); // mulai dari hari Senin
        $pegawais = Pegawai::all();

        foreach ($pegawais as $pegawai) {
            $libur = $hariList[array_rand($hariList)];

            for ($i = 0; $i < 7; $i++) {
                $tanggal = $today->copy()->addDays($i);
                $hari = $tanggal->translatedFormat('l'); // Senin dst dalam bahasa lokal

                if ($hari === $libur) continue;

                // Ambil shift acak dari nama (key)
                $shiftNama = array_rand($shiftJam);
                $jamMulai = $shiftJam[$shiftNama][0];
                $jamSelesai = $shiftJam[$shiftNama][1];

                Jadwal::updateOrCreate([
                    'id_pegawai' => $pegawai->id_pegawai,
                    'tanggal' => $tanggal->toDateString(),
                ], [
                    'hari' => $hari,
                    'shift' => $shiftNama,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'keterangan' => 'Generated otomatis',
                ]);
            }
        }

        return redirect()->route('jadwal.index')->with('success', '✅ Jadwal minggu ini berhasil digenerate ulang.');
    }
}
