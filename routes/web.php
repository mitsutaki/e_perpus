<?php

use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\FineController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnggotaController;

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');


// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/tour/complete', function (Request $request) {
    $user = $request->user();
    $user->has_seen_tour = true;
    $user->save();
    return response()->json(['status' => 'ok']);
    })->name('tour.complete');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin & Petugas bisa akses transaksi
    Route::resource('loans', LoanController::class);
    Route::resource('returns', ReturnController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::resource('fines', FineController::class)->except(['create', 'store', 'show', 'edit', 'destroy']);
    
   // Khusus Admin (Master Data & Anggota)
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('books', BookController::class);
        Route::resource('members', MemberController::class); // ← INI
        Route::resource('publishers', PublisherController::class);
        Route::resource('shelves', ShelfController::class);
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Khusus Super Admin (Manajemen User Admin & Petugas + Laporan)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

        Route::middleware('role:anggota')->group(function () {
        Route::get('/katalog', [AnggotaController::class, 'katalog'])->name('katalog.index');
        Route::get('/bookinganku', [AnggotaController::class, 'myBookings'])->name('anggota.bookings');
        Route::post('/booking/{book}', [AnggotaController::class, 'storeBooking'])->name('anggota.booking.store');
        Route::post('/booking/{booking}/cancel', [AnggotaController::class, 'cancelBooking'])->name('anggota.booking.cancel');
    });

        Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    });
});