<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisMotor;
use App\Models\Angsuran;
use App\Models\Asuransi;
use App\Models\JenisCicilan;
use App\Models\Motor;
use App\Models\MetodePembayaran;
use App\Models\Kredit;
use App\Models\Pelanggan;
use App\Models\PengajuanKredit;
use App\Models\Pengirimans;
use App\Models\User;
use App\Models\Blog;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function getabout()
    {
        return view('about.index',
        [
            'title' => 'About',
        ]);
    }

    public function getblog()
    {
        $blog = Blog::where('status', 'published')->get();
        // $relatedblog = Blog::where('id', '!=', $id)->limit(4)->get();

        return view('c-blog.index' ,
        [
            'title' => 'Blog',
            'blog' => $blog
        ]);
    }

    public function blogdetail($id)
    {
        // Get the current blog post
        $blog = Blog::with('user')->findOrFail($id);
        
        // Get recent blog posts (excluding current one)
        $recentBlogs = Blog::with('user')
                        ->where('id', '!=', $id)
                        ->orderBy('publish_at', 'desc')
                        ->take(3)
                        ->get();
        
        // Get related blog posts (excluding current one)
        // You can customize this query based on your needs (e.g., same category, tags, etc.)
        $relatedBlogs = Blog::with('user')
                        ->where('id', '!=', $id)
                        ->inRandomOrder()
                        ->take(3)
                        ->get();
        
        return view('c-blog.show', compact('blog', 'recentBlogs', 'relatedBlogs'),
        [
            'title' => 'Blog',
        ]);
    }

    public function getblogadmin()
    {
        return view('blog.index',
        [
            'title' => 'Blog',
        ]);
    }

    public function getcontact()
    {
        return view('contact.index',
        [
            'title' => 'Contact',
        ]);
    }
    public function getjenismotor()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('jenis-motor.index',[
            'title' => $title . ' - Jenis Motor',
            'menu' => 'Jenis Motor',
            'datas' => JenisMotor::all()
        ]);
    }

    public function getangsuran()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('angsuran.index',[
            'title' => $title . ' - Angsuran',
            'menu' => 'Angsuran',
            'data' => Angsuran::all()
        ]);
    }

    public function getasuransi()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('asuransi.index', [
            'title' => $title . ' - Asuransi',
            'menu' => 'Asuransi',
            'data' => Asuransi::all()
        ]);
    }

    public function getjeniscicilan()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('jenis-cicilan.index',[
            'title' => $title . ' - Jenis Cicilan',
            'menu' => 'Jenis Cicilan',
            'data' => JenisCicilan::all()
        ]);
    }

    public function getkredit()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('kredit.index',[
            'title' => $title . ' - Kredit',
            'menu' => 'Kredit',
            'data' => Kredit::all()
        ]);
    }

    public function getmetodepembayaran()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('metode-pembayaran.index',[
            'title' => $title . ' - Metode Pembayaran',
            'menu' => 'Metode Pembayaran',
            'data' => MetodePembayaran::all()
        ]);
    }

    public function getmotor()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('motor.index',[
            'title' => $title . ' - Motor',
            'menu' => 'Motor',
            'data' => Motor::all()
        ]);
    }

    public function getpelanggan()
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

    public function getpengajuankredit()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('pengajuan-kredit.index',[
            'title' => $title . ' - Pengajuan Kredit',
            'menu' => 'Pengajuan Kredit',
            'data' => PengajuanKredit::all()
        ]);
    }

    public function getpengiriman()
    {   
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('pengiriman.index',[
            'title' => $title . ' - Pengiriman',
            'menu' => 'Pengiriman',
            'data' => Pengirimans::all()
        ]);
    }

    public function getuser()
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
