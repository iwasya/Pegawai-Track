<?php

namespace App\Exports;

use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PegawaiExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithEvents
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function collection()
    {
        return Pegawai::with('jabatan')->get();
    }

    public function headings(): array
    {
        return [
            ['Data Pegawai'], // Row 1: Judul
            ['Nama Akun: ' . $this->user->nama_pengguna], // Row 2
            ['Role: ' . $this->user->role],               // Row 3
            [
                // A–K kosong
                'Tanggal Export: ' . now()->format('d-m-Y H:i') // L1
            ], // Row 4
            [], // Row 5 kosong
            [   // Row 6: Header tabel
                'NIP',
                'Nama Lengkap',
                'Jabatan',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Tanggal Masuk',
                'No Telepon',
                'Email',
                'Alamat',
                'Status Kerja',
                'Foto'
            ]
        ];
    }

    public function map($pegawai): array
    {
        return [
            $pegawai->nip,
            $pegawai->nama_lengkap,
            $pegawai->jabatan ? $pegawai->jabatan->nama_jabatan : '-',
            $pegawai->jenis_kelamin,
            $pegawai->tanggal_lahir,
            $pegawai->tanggal_masuk,
            $pegawai->no_telepon,
            $pegawai->email,
            $pegawai->alamat,
            $pegawai->status_kerja,
            '', // untuk foto
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $pegawaiList = $this->collection();
        $startRow = 7; // Data dimulai dari baris ke-7

        foreach ($pegawaiList as $index => $pegawai) {
            if ($pegawai->foto) {
                $path = public_path('storage/' . $pegawai->foto);

                if (file_exists($path)) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto Pegawai');
                    $drawing->setDescription('Foto Pegawai');
                    $drawing->setPath($path);
                    $drawing->setHeight(60); // Sesuaikan tinggi
                    $drawing->setCoordinates('K' . ($startRow + $index));
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawings[] = $drawing;
                }
            }
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge judul
                $sheet->mergeCells('A1:K1');
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Bold untuk info akun
                $sheet->getStyle('A2:A3')->getFont()->setBold(true);

                // Bold header tabel
                $sheet->getStyle('A6:K6')->getFont()->setBold(true);

                // Auto-size semua kolom A–K
                foreach (range('A', 'K') as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                // Tinggi baris agar cukup untuk gambar
                $totalData = Pegawai::count();
                $startDataRow = 7;
                $endDataRow = $startDataRow + $totalData - 1;

                for ($row = $startDataRow; $row <= $endDataRow; $row++) {
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(60);
                }
            }
        ];
    }
}
