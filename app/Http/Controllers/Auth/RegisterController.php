<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\akun_User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:50', 'unique:akun_user'],
            'email' => ['required', 'string', 'email', 'max:250', 'unique:akun_user'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nama_pengguna' => ['required', 'string', 'max:100'],
        ]);
    }

    protected function create(array $data)
    {
        return akun_User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nama_pengguna' => $data['nama_pengguna'],
            'role' => 'pegawai', // Default role, bisa diubah jika dibutuhkan
        ]);
    }
}
