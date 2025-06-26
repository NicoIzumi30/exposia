<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin' && Auth::user()->is_active && !Auth::user()->is_suspended) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        if (Auth::check() && !Auth::user()->is_active) {
            return redirect()->route('verification.notice')->with('error', 'Akun Anda belum diverifikasi.');
        }

        if (Auth::check() && Auth::user()->is_suspended) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Silahkan hubungi administrator.');
        }

        return redirect()->route('login');
    }
}