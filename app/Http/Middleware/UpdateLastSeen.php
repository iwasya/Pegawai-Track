<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateLastSeen
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Ubah last_seen ke objek Carbon, atau null jika kosong
            $lastSeen = $user->last_seen ? Carbon::parse($user->last_seen) : null;

            // Threshold waktu dalam menit, untuk update last_seen
            $threshold = 1; // 1 menit, bisa diubah misal 5 menit

            // Jika last_seen belum ada atau sudah lewat threshold menit, update last_seen
            if (!$lastSeen || $lastSeen->diffInMinutes(now()) >= $threshold) {
                $user->last_seen = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
