<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\LogAktivitas; // pastikan model LogAktivitas sudah dibuat dan di-import

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // ✅ Cek status_akun sebelum login
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        // Cari user dengan email/username sesuai, dan status aktif
        $user = DB::table('akun_user')
            ->where('email', $credentials['email'])
            ->first();

        if ($user && $user->status_akun === 'nonaktif') {
            // Jika akun nonaktif, tolak login
            throw ValidationException::withMessages([
                $this->username() => ['Akun Anda dinonaktifkan. Silakan hubungi admin.'],
            ]);
        }

        return Auth::attempt($credentials, $request->filled('remember'));
    }

    // ✅ Setelah login sukses
    protected function authenticated(Request $request, $user)
    {
        // Update status user
        DB::table('akun_user')->where('id_user', $user->id_user)->update([
            'last_login' => Carbon::now(),
            'is_online' => true,
            'last_seen' => Carbon::now(),
        ]);

        // Simpan log aktivitas login
        LogAktivitas::create([
            'id_user' => $user->id_user, // pastikan cocok dengan nama kolom relasi
            'aktivitas' => 'Login',
            'tanggal_waktu' => Carbon::now(),
            'ip_address' => $request->ip(),
        ]);
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            DB::table('akun_user')->where('id_user', $user->id_user)->update([
                'last_login' => Carbon::now(),
                'is_online' => false,
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
