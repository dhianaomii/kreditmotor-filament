<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientKreditController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientAngsuranController;
use App\Http\Controllers\ClientPengirimanController;
use Illuminate\Support\Facades\Route;

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

Route::get('/register', [PelangganController::class, 'ShowRegisterForm'])->name('register'); // menampilkan register form
Route::post('/register', [PelangganController::class, 'register'])->name('register'); // mengirim data register
Route::get('/login', [PelangganController::class, 'ShowLoginForm'])->name('login'); // menampilkan login form
Route::post('/login', [PelangganController::class, 'login'])->name('login'); // mengirim data login
Route::post('/log-out', [PelangganController::class, 'logout'])->name('log-out'); // eksekusi logout

Route::get('/profile', [PelangganController::class, 'getprofile'])->name('profile')->middleware([ 'auth:pelanggan']); // menampilkan index profile sesuai dengan user yang login
Route::get('/profile/edit', [PelangganController::class, 'edit'])->name('profile.edit')->middleware([ 'auth:pelanggan']); // menampilkan form edit profile
Route::put('/profile', [PelangganController::class, 'update'])->name('profile.update')->middleware([ 'auth:pelanggan']); // eksekusi update profile

Route::get('/', [HomeController::class, 'index'])->name('/'); // menampilkan halaman utama
Route::get('/about', [MainController::class, 'getabout'])->name('about'); // menampilkan halaman about
Route::get('/blog', [MainController::class, 'getblog'])->name('blog'); // menampilkan halaman blog
Route::get('/contact', [MainController::class, 'getcontact'])->name('contact'); // menampilkan halaman contact

Route::get('/product', [ProductController::class, 'index'])->name('product'); // menampilkan halaman product
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show'); // menampilkan halaman detail product
Route::get('/product/{id}/create', [ProductController::class, 'create'])->name('product.create')->middleware('auth:pelanggan'); // menampilkan halaman form tambah pengajuan kredit
Route::post('/product', [ProductController::class, 'store'])->name('product.store')->middleware('auth:pelanggan'); // eksekusi tambah pengajuan kredit

Route::get('/pengajuan', [ClientKreditController::class, 'index'])->name('pengajuan')->middleware('auth:pelanggan'); // menampilkan halaman pengajuan kredit sesuai dengan user yang login
Route::get('/pengajuan/create', [ClientKreditController::class, 'create'])->name('pengajuan.create')->middleware('auth:pelanggan'); // menampilkan halaman form tambah kredit
Route::post('/pengajuan', [ClientKreditController::class, 'store'])->name('pengajuan.store')->middleware('auth:pelanggan'); // eksekusi tambah kredit
Route::patch('/pengajuan/{id}/cancel', [ClientKreditController::class, 'updateStatus'])->name('pengajuan.cancel')->middleware('auth:pelanggan')
    ->defaults('action', 'cancel');

Route::get('/cicilan/{id}', [ClientAngsuranController::class, 'show'])->name('cicilan')->middleware('auth:pelanggan'); // menampilkan halaman angsuran sesuai dengan user yang login
Route::post('/cicilan/{pengajuanId}', [ClientAngsuranController::class, 'store'])->name('cicilan.store')->middleware('auth:pelanggan'); // eksekusi simpan angsuran

Route::get('/kirim/{id}/create', [ClientPengirimanController::class, 'create'])->name('kirim.create')->middleware('auth:pelanggan'); // menampilkan halaman form kirim angsuran
Route::post('/kirim', [ClientAngsuranController::class, 'store'])->name('kirim.store')->middleware('auth:pelanggan'); // eksekusi kirim angsuran

// ROUTE ADMIN PAGE

Route::get('/signin', [AuthController::class, 'ShowSigninForm'])->name('signin');
Route::post('/signin', [AuthController::class, 'login'])->name('signin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
});

Route::middleware(['auth', 'role:admin,ceo,marketing'])->group(function () {
    Route::get('pelanggan', [MainController::class, 'getpelanggan'])->name('pelanggan');
    Route::get('pengajuan-kredit', [MainController::class, 'getpengajuankredit'])->name('pengajuan-kredit');
    Route::get('kredit', [MainController::class, 'getkredit'])->name('kredit');
    Route::get('angsuran', [MainController::class, 'getangsuran'])->name('angsuran');
});

Route::middleware(['auth', 'role:admin,ceo,kurir'])->group(function () {
    Route::get('pengiriman', [MainController::class, 'getpengiriman'])->name('pengiriman');
});
