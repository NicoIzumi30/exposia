<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda belum aktif. Silakan verifikasi email Anda terlebih dahulu.'
                ]);
            }
            
            // Check if user is suspended
            if ($user->is_suspended) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda telah disuspend. Silakan hubungi administrator untuk informasi lebih lanjut.'
                ]);
            }
        }
        
        return $next($request);
    }
}