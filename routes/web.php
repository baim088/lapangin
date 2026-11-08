<?php

use Illuminate\Support\Facades\Route;

// === PUBLIC ROUTES ===
Route::get('/', function () {
    return view('welcome');
})->name('beranda');

Route::get('/lapangan', function () {
    return view('lapangan');
})->name('lapangan');

Route::get('/lapangan/{slug}', function (string $slug) {
    return view('lapangan-detail', ['slug' => $slug]);
})->name('lapangan.detail');

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// === AUTH ROUTES ===
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

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
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');

Route::get('/dashboard/booking/{id}', function (string $id) {
    return view('user.booking-detail', ['id' => $id]);
})->name('user.booking.detail');

// === OPERATOR ROUTES ===
Route::prefix('operator')->name('operator.')->group(function () {
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
Route::prefix('admin')->name('admin.')->group(function () {
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
