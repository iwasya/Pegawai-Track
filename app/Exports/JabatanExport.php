<?php
namespace App\Exports;

use App\Models\Jabatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JabatanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Jabatan::select('id_jabatan', 'nama_jabatan', 'gaji_pokok')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Jabatan', 'Gaji Pokok'];
    }
}
