<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ValidateNavigation
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $currentRoute = $request->route()->getName();
        
        $allowedRoutes = [
            'admin' => ['dashboard', 'jenis-motor', 'motor', 'pelanggan', 'asuransi', 'jenis-cicilan', 'metode-pembayaran', 'pengajuan-kredit', 'kredit', 'angsuran', 'pengiriman'],
            'ceo' => ['dashboard', 'pelanggan', 'pengajuan-kredit', 'kredit', 'angsuran', 'pengiriman'],
            'marketing' => ['dashboard', 'pelanggan', 'asuransi', 'jenis-cicilan', 'pengajuan-kredit','kredit', 'angsuran'],
            'kurir' => ['dashboard', 'pengiriman']
            // Tambahkan role lainnya
        ];

        if (!in_array($currentRoute, $allowedRoutes[$user->role] ?? [])) {
            abort(403, 'Unauthorized access');
        }

    }
}