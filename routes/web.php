<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\UserController;
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
// Static segments MUST come before wildcard routes to avoid slug collision
Route::get('/booking/konfirmasi', [BookingController::class, 'showKonfirmasi'])->name('booking.konfirmasi')->middleware('auth');
Route::get('/booking/sukses', [BookingController::class, 'showSukses'])->name('booking.sukses')->middleware('auth');
Route::get('/booking/{slug}', [BookingController::class, 'showCheckout'])->name('booking.checkout');
Route::post('/booking/{slug}', [BookingController::class, 'storeCheckout'])->name('booking.checkout.store')->middleware('auth');
Route::post('/booking/{booking}/upload-proof', [BookingController::class, 'uploadProof'])->name('booking.upload-proof')->middleware(['auth', 'throttle:10,1']);
Route::get('/bookings/{booking}/proof', [BookingController::class, 'showProof'])->name('booking.proof')->middleware(['auth', 'throttle:30,1']);

// === DASHBOARD USER ===
Route::middleware(['role:customer,operator,owner,super_admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/dashboard/cari-lapangan', [UserController::class, 'cariLapangan'])->name('user.cari-lapangan');
    Route::get('/dashboard/booking/{id}', [UserController::class, 'bookingDetail'])->name('user.booking.detail');
    Route::post('/dashboard/booking/{booking}/cancel', [BookingController::class, 'cancelPending'])->name('user.booking.cancel');
});

// === OPERATOR / KASIR VENUE ROUTES ===
Route::prefix('operator')->name('operator.')->middleware(['role:operator,super_admin'])->group(function () {
    Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard');

    Route::get('/booking', [OperatorController::class, 'bookingList'])->name('booking');
    Route::get('/booking/export', [OperatorController::class, 'exportCsv'])->name('booking.export');
    Route::post('/booking/{booking}/confirm', [OperatorController::class, 'confirm'])->name('booking.confirm');
    Route::post('/booking/{booking}/reject', [OperatorController::class, 'reject'])->name('booking.reject');
    Route::post('/booking/{booking}/cancel', [OperatorController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{booking}/settle', [OperatorController::class, 'settle'])->name('booking.settle');

    Route::get('/laporan', [OperatorController::class, 'laporan'])->name('laporan');

    Route::get('/jadwal', [OperatorController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal/block', [OperatorController::class, 'blockSlot'])->name('jadwal.block');
    Route::delete('/jadwal/unblock/{id}', [OperatorController::class, 'unblockSlot'])->name('jadwal.unblock');

    Route::get('/lapangan', [OperatorController::class, 'lapangan'])->name('lapangan');
    Route::post('/lapangan', [OperatorController::class, 'storeCourt'])->name('lapangan.store');
    Route::put('/lapangan/{court}', [OperatorController::class, 'updateCourt'])->name('lapangan.update');
    Route::patch('/lapangan/{court}/toggle-status', [OperatorController::class, 'toggleCourtStatus'])->name('lapangan.toggle-status');
});

// === OWNER / PEMILIK VENUE ROUTES ===
Route::prefix('owner')->name('owner.')->middleware(['role:owner,super_admin'])->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [OwnerController::class, 'laporan'])->name('laporan');
    Route::get('/booking', [OwnerController::class, 'bookingList'])->name('booking');
    Route::get('/booking/export', [OwnerController::class, 'exportCsv'])->name('booking.export');
    Route::get('/lapangan', [OwnerController::class, 'lapangan'])->name('lapangan');
});

// === ADMIN ROUTES ===
Route::prefix('admin')->name('admin.')->middleware(['role:super_admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');

    Route::get('/venues', [AdminController::class, 'venues'])->name('venues');
    Route::put('/venues/{venue}', [AdminController::class, 'updateVenue'])->name('venues.update');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
});
