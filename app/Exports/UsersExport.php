<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $users = User::select('username', 'nama_pengguna', 'password', 'email', 'created_at', 'last_login', 'role', 'status_akun')->get();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                $user->username,
                $user->nama_pengguna,
                'Terenkripsi', // Jangan tampilkan hash password asli
                $user->email,
                $user->created_at,
                $user->last_login,
                $user->role,
                $user->status_akun,
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Username', 'Nama Pengguna', 'Password', 'Email', 'Dibuat Pada', 'Terakhir Login', 'Role', 'Status Akun'];
    }
}
