<?php

namespace App\Exports;

use App\Models\Cuti;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CutiExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
{
    private $cutis;

    public function collection()
    {
        // Ambil dan simpan data ke properti untuk digunakan di map() dan drawings()
        return $this->cutis = Cuti::with('pegawai')->get();
    }

    public function map($cuti): array
    {
        return [
            $cuti->id_cuti,
            $cuti->pegawai->nama_lengkap ?? '-',
            $cuti->id_pegawai,
            $cuti->tanggal_mulai,
            $cuti->tanggal_selesai,
            $cuti->jenis_cuti,
            $cuti->status,
            $cuti->keterangan,
            $cuti->aksi,
            $cuti->waktu_log,
            $cuti->created_at,
            $cuti->updated_at,
            '', // Kolom ini untuk gambar nanti (dimasukkan oleh WithDrawings)
        ];
    }

    public function headings(): array
    {
        return [
            'ID Cuti',
            'Nama Pegawai',
            'ID Pegawai',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Jenis Cuti',
            'Status',
            'Keterangan',
            'Aksi',
            'Waktu Log',
            'Created At',
            'Updated At',
            'Foto',
        ];
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->cutis as $index => $cuti) {
            if ($cuti->foto && file_exists(public_path('storage/' . $cuti->foto))) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription('Foto Cuti');
                $drawing->setPath(public_path('storage/' . $cuti->foto));
                $drawing->setHeight(50); // Sesuaikan tinggi
                $drawing->setCoordinates('M' . ($index + 2)); // M untuk kolom ke-13, +2 karena baris pertama adalah heading

                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }
}
