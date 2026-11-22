<?php

namespace App\Http\Middleware;

use App\Models\Pengguna;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login, atau saat ini login sebagai super admin (id_entitas null),
        // otomatis login sebagai akun Admin Desa (Manajer Desa)
        if (! Auth::check() || Auth::user()->id_entitas === null) {
            $user = Pengguna::whereNotNull('id_entitas')->where('is_active', true)->first()
                ?? Pengguna::whereNotNull('id_entitas')->first();

            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}
