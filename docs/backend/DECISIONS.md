# Log Keputusan Teknis & Asumsi Backend Lapangin

Dokumen ini mencatat keputusan arsitektur, asumsi implementasi, dan default yang digunakan dalam pengembangan backend Lapangin.

---

## 1. Konvensi Umum & Batasan Sistem
- **Timezone**: `Asia/Jakarta` (WIB) di seluruh aplikasi dan query Carbon.
- **Mata Uang & Angka**: Semua nilai uang menggunakan integer Rupiah. Perhitungan ketersediaan, jam prime, dan harga murni di sisi server.
- **Keamanan Slot**: `UNIQUE(court_id, booking_date, start_time)` pada tabel `court_slots` sebagai satu-satunya penjamin keandalan anti double-booking. Penanganan race condition menggunakan retry dan penangkapan `UniqueConstraintViolationException` dalam transaksi `DB::transaction($callback, 3)`.
- **State Machine**:
  - `pending` (expires_at = now + venue.slot_lock_duration_minutes) -> slot terkunci (`locked_until` = `expires_at`).
  - Upload bukti on-time -> payment `pending_verification`, `booking.expires_at = NULL`, `court_slots.locked_until = NULL`.
  - Expired tanpa bukti -> booking & payment `expired`, slot dihapus.
  - Operator konfirmasi -> booking `confirmed`, payment `paid`.
  - Operator tolak -> booking `cancelled` (dengan alasan), payment `rejected`, slot dihapus.
  - Customer batal (pending) -> booking `cancelled`, payment `unpaid`, slot dihapus.
  - Operator batal (confirmed) -> booking `cancelled`, payment `refund_pending`, slot dihapus (refund manual di luar sistem).
  - Upload bukti pada booking expired ditolak.

---

## 2. Fase 1: Autentikasi & Otorisasi
- **Registrasi**:
  - `role` pengguna baru selalu dipaksa `customer` dan `status` aktif. Kolom `role` dan `status` tidak terdapat di `$fillable`, sehingga injeksi payload `role=super_admin` otomatis diabaikan.
  - Sanitasi nomor HP: Karakter pemisah seperti strip dan spasi dibersihkan pada `prepareForValidation()` sebelum dicocokkan dengan pola regex telepon seluler Indonesia `^(?:\+62|62|0)8[1-9][0-9]{6,11}$`.
  - Auto-login langsung dilakukan setelah user berhasil didaftarkan dan diarahkan ke `user.dashboard`.
- **Login & Throttle**:
  - Rate limiting menggunakan `RateLimiter` dengan kuota 5 percobaan per menit per kombinasi lowercase email dan IP address pengguna.
  - Sesi di-regenerate via `$request->session()->regenerate()` untuk mencegah session fixation.
  - Akun bersatus `suspended` langsung ditolak, sesi di-invalidate, dan diberikan pesan berbahasa Indonesia.
  - Pengalihan login:
    - `super_admin` -> `admin.dashboard`
    - `operator` -> `operator.dashboard`
    - `customer` -> `redirect()->intended(route('user.dashboard'))`
- **Middleware Role**:
  - Didaftarkan sebagai alias `role` di `bootstrap/app.php`.
  - Menerima multi-role variadic (contoh: `role:operator,super_admin`).
  - Bila belum login (guest), diarahkan ke rute `login` dengan `redirect()->guest(route('login'))`.
  - Bila login namun role tidak cocok, melempar exception `abort(403, 'Akses ditolak.')`.
- **Navbar Dinamis**:
  - Menggunakan direktif `@guest` untuk tombol Masuk dan Daftar.
  - Menggunakan direktif `@auth` untuk menampilkan nama pengguna, tautan dashboard dinamis sesuai peran aktif, dan tombol Keluar (form POST dengan `@csrf`).
