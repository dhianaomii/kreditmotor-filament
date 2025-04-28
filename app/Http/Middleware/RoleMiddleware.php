<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Periksa Siapa yang login dan rolenya sebagai apa
        if(Auth::check() && Auth::user()->role === $role){
            return $next($request);
        }

        // Kalo ga sesuai balik lagi ke halaman sebelumnya dengan pesan error
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
}
