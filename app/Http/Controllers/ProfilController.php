<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('jabatan')->where('id_user', $user->id_user)->first();

        if (!$pegawai) {
            abort(404, 'Data pegawai tidak ditemukan.');
        }

        return view('pegawai.profil', compact('pegawai'));
    }


public function getTanggalMerah($tahun = null)
{
    $tahun = $tahun ?: now()->year;
    $apiKey = env('CALENDARIFIC_API_KEY');

    $response = Http::get("https://calendarific.com/api/v2/holidays", [
        'api_key' => $apiKey,
        'country' => 'ID',
        'year' => $tahun,
        'type' => 'national',
    ]);

    if ($response->successful()) {
        return collect($response['response']['holidays'])
            ->mapWithKeys(function ($item) {
                return [$item['date']['iso'] => $item['name']];
            })
            ->toArray();
    }

    return [];
}



public function jadwalSaya(Request $request)
{
    $user = Auth::user();
    $pegawai = $user->pegawai ?? abort(403);
    $bulan = $request->bulan ?: now()->month;
    $tahun = $request->tahun ?: now()->year;

    $jadwals = Jadwal::where('id_pegawai', $pegawai->id_pegawai)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->get();

    $jadwalMap = $jadwals->groupBy->tanggal;
    $tanggalMerah = $this->getTanggalMerah($tahun);

    return view('pegawai.jadwal_kalender', compact(
        'jadwalMap','bulan','tahun','tanggalMerah'
    ));
}


}