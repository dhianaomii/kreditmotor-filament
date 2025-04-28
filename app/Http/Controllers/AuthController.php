<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('user.index', [
            'title' => $title . ' - User',
            'menu' => 'Pelanggan',
            'data' => User::all()
        ]);
    }

    /**
     * Display a Form Login
     */
    public function ShowSigninForm()
    {
        return view('signin.index', [
            'title' => 'Sign In'
        ]);
    }

    /**
     * Process Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // if(Auth::attempt($request->only('email', 'password'))){
        //     $user = Auth::user();
        

        //     if ($user->role === 'admin') {
        //         return redirect()->route('admin')->with('success', 'Login berhasil sebagai Admin!');
        //     } elseif ($user->role === 'marketing') {
        //         return redirect()->route('marketing')->with('success', 'Login berhasil sebagai marketing!');
        //     }elseif ($user->role === 'ceo') {
        //         return redirect()->route('ceo')->with('success', 'Login berhasil sebagai ceo!');
        //     } elseif ($user->role === 'kurir') {
        //         return redirect()->route('kurir')->with('success', 'Login berhasil sebagai Kurir!');
        //     }
        // }
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            // Arahkan semua pengguna ke dashboard, terlepas dari role
            return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai ' . ucfirst($user->role) . '!');
        }

        return back()->with(['error' => 'Email or Password doesnt match.'])->withInput();
    }

    /**
     * Logout Process.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/signin')->with('success', 'Logout berhasil!');
    }

    /**
     * Display the Admin Dashboard
     */
    public function AdminDashboard()
    {
        $this->authorize('admin'); 
        return view('admin.index');
    }

    /**
     * Display the Marketing Dashboard
     */
    public function MarketingDashboard()
    {
        $this->authorize('marketing'); 
        return view('marketing.index');
    }

    /**
     * Display the Ceo Dashboard
     */
    public function CeoDashboard()
    {
        $this->authorize('ceo'); 
        return view('ceo.index');
    }

    /**
     * Display the Kurir Dashboard
     */
    public function KurirDashboard()
    {
        $this->authorize('kurir'); 
        return view('kurir.index');
    }

}