<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// === PUBLIC ROUTES ===
Route::get('/', [HomeController::class, 'index'])->name('beranda');
Route::get('/lapangan', [CourtController::class, 'index'])->name('lapangan');
Route::get('/lapangan/{slug}', [CourtController::class, 'show'])->name('lapangan.detail');

Route::get('/api/lapangan/{slug}/slots', [CourtController::class, 'slots'])->name('api.lapangan.slots');
Route::get('/api/quick-slots', [HomeController::class, 'quickSlots'])->name('api.quick-slots');

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// === AUTH ROUTES ===
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === BOOKING ROUTES (User) ===
Route::get('/booking/{slug}', function (string $slug) {
    return view('booking.checkout', ['slug' => $slug]);
})->name('booking.checkout');

Route::get('/booking/konfirmasi', function () {
    return view('booking.konfirmasi');
})->name('booking.konfirmasi');

Route::get('/booking/sukses', function () {
    return view('booking.sukses');
})->name('booking.sukses');

// === DASHBOARD USER ===
Route::middleware(['role:customer,operator,super_admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/dashboard/booking/{id}', function (string $id) {
        return view('user.booking-detail', ['id' => $id]);
    })->name('user.booking.detail');
});

// === OPERATOR ROUTES ===
Route::prefix('operator')->name('operator.')->middleware(['role:operator,super_admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('operator.dashboard');
    })->name('dashboard');

    Route::get('/jadwal', function () {
        return view('operator.jadwal');
    })->name('jadwal');

    Route::get('/booking', function () {
        return view('operator.booking');
    })->name('booking');

    Route::get('/lapangan', function () {
        return view('operator.lapangan');
    })->name('lapangan');
});

// === ADMIN ROUTES ===
Route::prefix('admin')->name('admin.')->middleware(['role:super_admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/venues', function () {
        return view('admin.venues');
    })->name('venues');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
});
