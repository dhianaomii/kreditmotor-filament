<?php
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientKreditController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientAngsuranController;
use App\Http\Controllers\ClientPengirimanController;
use App\Http\Controllers\WebcamController;
use Illuminate\Support\Facades\Route;

use App\Livewire\CameraCapture;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// ROUTE CLIENT PAGE (sumpah ini ngasih notes sendiri <3)

Route::get('register', [PelangganController::class, 'ShowRegisterForm'])->name('register.form'); // menampilkan register form
Route::post('register', [PelangganController::class, 'register'])->name('register'); // mengirim data register
Route::get('login', [PelangganController::class, 'ShowLoginForm'])->name('login.form'); // menampilkan login form
Route::post('login', [PelangganController::class, 'login'])->name('login'); // mengirim data login
Route::post('log-out', [PelangganController::class, 'logout'])->name('log-out'); // eksekusi logout
Route::post('{id}/block', [PelangganController::class, 'block'])->name('pelanggan.block');
Route::post('{id}/unblock', [PelangganController::class, 'unblock'])->name('pelanggan.unblock');

Route::get('profile', [PelangganController::class, 'getprofile'])->name('profile')->middleware([ 'auth:pelanggan']); // menampilkan index profile sesuai dengan user yang login
Route::get('profile/edit', [PelangganController::class, 'edit'])->name('profile.edit')->middleware([ 'auth:pelanggan']); // menampilkan form edit profile
Route::put('profile', [PelangganController::class, 'update'])->name('profile.update')->middleware([ 'auth:pelanggan']); // eksekusi update profile

Route::get('/', [HomeController::class, 'index'])->name('/'); // menampilkan halaman utama
Route::get('about', [MainController::class, 'getabout'])->name('about'); // menampilkan halaman about
Route::get('blog', [MainController::class, 'getblog'])->name('blog'); // menampilkan halaman blog
Route::get('blog/{id}', [MainController::class, 'blogdetail'])->name('blog.show'); // menampilkan halaman detail blog
Route::get('contact', [MainController::class, 'getcontact'])->name('contact'); // menampilkan halaman contact

Route::get('product', [ProductController::class, 'index'])->name('product'); // menampilkan halaman product
Route::get('product/{id}', [ProductController::class, 'show'])->name('product.show'); // menampilkan halaman detail product
Route::get('product/{id}/create', [ProductController::class, 'create'])->name('product.create')->middleware('auth:pelanggan'); // menampilkan halaman form tambah pengajuan kredit
Route::post('product', [ProductController::class, 'store'])->name('product.store')->middleware('auth:pelanggan'); // eksekusi tambah pengajuan kredit

Route::get('pengajuan', [ClientKreditController::class, 'index'])->name('pengajuan')->middleware('auth:pelanggan');
Route::get('pengajuan/{pengajuanId}/create', [ClientKreditController::class, 'create'])->name('pengajuan.create')->middleware('auth:pelanggan');
Route::patch('pengajuan/{id}/cancel', [ClientKreditController::class, 'updateStatus'])->name('pengajuan.cancel')->middleware('auth:pelanggan')
    ->defaults('action', 'cancel');

// MIDTRANS DP
Route::get('/pengajuan/snap-token/{pengajuanId}', [ClientKreditController::class, 'getSnapToken'])->name('pengajuan.snap-token')->middleware('auth:pelanggan');
Route::post('/pengajuan/update-status/{id}/{action}', [ClientKreditController::class, 'updateStatus'])->name('pengajuan.update-status')->middleware('auth:pelanggan');
Route::post('/pengajuan/callback', [ClientKreditController::class, 'handleCallback'])->name('pengajuan.callback');

Route::get('cicilan/{id}', [ClientAngsuranController::class, 'show'])->name('cicilan')->middleware('auth:pelanggan'); // menampilkan halaman angsuran sesuai dengan user yang login
Route::post('cicilan/{pengajuanId}', [ClientAngsuranController::class, 'store'])->name('cicilan.store')->middleware('auth:pelanggan'); // eksekusi simpan angsuran

Route::get('kirim/{pengajuanId}/create', [ClientPengirimanController::class, 'create'])->name('kirim.create')->middleware('auth:pelanggan'); // menampilkan halaman form kirim angsuran
Route::post('kirim/{pengajuanId}', [ClientPengirimanController::class, 'store'])->name('kirim.store')->middleware('auth:pelanggan'); // eksekusi kirim angsuran

// ROUTE ADMIN PAGE

Route::get('signin', [AuthController::class, 'ShowSigninForm'])->name('signin.form');
Route::post('signin', [AuthController::class, 'login'])->name('signin');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Semua role diarahkan ke dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Tambahan: redirect root ke dashboard

});
    
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::resource('jenis-motor', JenisMotorController::class);
    Route::get('jenis-motor', [MainController::class, 'getjenismotor'])->name('jenis-motor');
    Route::get('motor', [MainController::class, 'getmotor'])->name('motor');
    Route::get('metode-pembayaran', [MainController::class, 'getmetodepembayaran'])->name('metode-pembayaran');
    Route::get('user', [MainController::class, 'getuser'])->name('user');
    Route::get('asuransi', [MainController::class, 'getasuransi'])->name('asuransi');
    Route::get('jenis-cicilan', [MainController::class, 'getjeniscicilan'])->name('jenis-cicilan');
    Route::get('arsip', [ArsipController::class, 'index'])->name('arsip');
    Route::get('/export/pelanggan/pdf', [ArsipController::class, 'exportPelangganPdf'])->name('export.pelanggan.pdf');
    Route::get('/export/kredit/pdf', [ArsipController::class, 'exportKreditPdf'])->name('export.kredit.pdf');
    Route::get('/export/pengajuan/pdf', [ArsipController::class, 'exportPengajuanPdf'])->name('export.pengajuan.pdf');
    Route::get('/export/angsuran/pdf', [ArsipController::class, 'exportAngsuranPdf'])->name('export.angsuran.pdf');
    Route::get('/export/pengiriman/pdf', [ArsipController::class, 'exportPengirimanPdf'])->name('export.pengiriman.pdf');
});

Route::middleware(['auth', 'role:admin,ceo,marketing'])->group(function () {
    Route::get('pelanggan', [MainController::class, 'getpelanggan'])->name('pelanggan');
    Route::get('pengajuan-kredit', [MainController::class, 'getpengajuankredit'])->name('pengajuan-kredit');
    Route::get('kredit', [MainController::class, 'getkredit'])->name('kredit');
    Route::get('angsuran', [MainController::class, 'getangsuran'])->name('angsuran');
    Route::get('blog-admin', [MainController::class, 'getblogadmin'])->name('blog-admin');
});

Route::middleware(['auth', 'role:admin,ceo,kurir'])->group(function () {
    Route::get('pengiriman', [MainController::class, 'getpengiriman'])->name('pengiriman');
});





Route::get('/camera-capture', [WebcamController::class, 'index']);