<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\PengajuanKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('pelanggan.index', [
            'title' => $title . ' - Pelanggan',
            'menu' => 'Pelanggan',
            'data' => Pelanggan::all()
        ]);
    }

    public function ShowRegisterForm(){
        return view('c-signup.index', [
            'title' => 'Sign Up'
        ]);
    }

    public function ShowLoginForm()
    {
        return view('c-signin.index', [
            'title' => 'Sign In'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'email' => 'required|email|unique:pelanggans,email',
            'no_hp' => 'required|numeric',
            'password' => 'required|min:6',
        ]);
    
        try {
            $user = Pelanggan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'password' => $request->password // otomatis hashed karena ada cast di model
            ]);
    
            if ($user) {
                Auth::guard('pelanggan')->login($user);
                return redirect()->route('/')->with('success', 'Register berhasil!');
            } else {
                return redirect()->route('register')->with('error', 'Register gagal, silakan coba lagi.');
            }
        } catch (\Exception $e) {
            // Tambahkan log jika perlu
            return redirect()->route('register')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        
        if (Auth::guard('pelanggan')->attempt($request->only('email', 'password'))) {
            return redirect()->route('/')->with('success', 'Login berhasil!');
        }
        
        return back()->with(['error' => 'Email or Password doesnt match.'])->withInput();
    }

    public function logout()
    {
        Auth::guard('pelanggan')->logout();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }


    public function getprofile()
    {
        // Kirim data ke view
        return view('c-profile.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
   /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('c-profile.edit', compact('pelanggan'));
    }

    /**
     * Menangani update profil
     */
    public function update(Request $request)
    {
        try {
            $pelanggan = Auth::guard('pelanggan')->user();

            // Validasi input
            $validated = $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:pelanggans,email,' . $pelanggan->id,
                'no_hp' => 'required|string|max:15',
                'alamat1' => 'nullable|string|max:255',
                'kota1' => 'nullable|string|max:100',
                'provinsi1' => 'nullable|string|max:100',
                'kode_pos1' => 'nullable|string|max:10',
                'alamat2' => 'nullable|string|max:255',
                'kota2' => 'nullable|string|max:100',
                'provinsi2' => 'nullable|string|max:100',
                'kode_pos2' => 'nullable|string|max:10',
                'alamat3' => 'nullable|string|max:255',
                'kota3' => 'nullable|string|max:100',
                'provinsi3' => 'nullable|string|max:100',
                'kode_pos3' => 'nullable|string|max:10',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Log untuk debugging
            // \Log::info('Validated Profile Update', $validated);

            // Handle upload foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
                    Storage::disk('public')->delete($pelanggan->foto);
                }
                // Simpan foto baru
                $validated['foto'] = $request->file('foto')->store('profile', 'public');
            } else {
                // Pertahankan foto lama
                $validated['foto'] = $pelanggan->foto;
            }

            // Update data pelanggan
            $pelanggan->update($validated);

            // \Log::info('Profile Updated', $pelanggan->toArray());

            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // \Log::error('Validation Error', ['errors' => $e->errors()]);
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . implode(', ', \Illuminate\Support\Arr::flatten($e->errors())))->withInput();
        } catch (\Exception $e) {
            // \Log::error('Error Update Profile', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
// 