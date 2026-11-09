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

---

## 3. Fase 2: Katalog & Ketersediaan Real-Time
- **Service ExpireStaleBookings**:
  - Menghapus kunci slot dan menandai `Booking` serta `Payment` yang pending sebagai `expired` bila `expires_at <= now()`.
  - Dipanggil secara lazy saat endpoint atau service ketersediaan slot diakses dengan filter `$courtId` dan `$date`, serta dijadwalkan per menit pada `routes/console.php` untuk pembersihan global otomatis.
- **Perhitungan Ketersediaan Slot**:
  - Dihitung murni di server dalam `CourtAvailabilityService`.
  - Menghasilkan slot per jam tepat dari `venue.open_time` hingga `venue.close_time`.
  - Status slot diidentifikasi sebagai token:
    - `maintenance`: Bila lapangan berstatus `CourtStatus::Maintenance`.
    - `past`: Bila waktu mulai slot telah lewat dari `Carbon::now()`.
    - `booked`: Bila slot terisi oleh booking aktif (`locked_until` NULL atau masih berlaku).
    - `blocked`: Bila slot diblokir manual oleh operator.
    - `available`: Slot bebas dan dapat dipesan.
- **Harga Reguler & Prime Time**:
  - Slot dengan `start_time` di antara `venue.prime_start_time` dan `venue.prime_end_time` dikenakan tarif `court.price_prime`.
  - Slot di luar jam prime time dikenakan tarif `court.price_regular`.
- **API Endpoint JSON**:
  - `GET /api/lapangan/{slug}/slots?date=YYYY-MM-DD` untuk memperbarui slot Alpine.js saat user berganti tanggal di halaman detail.
  - `GET /api/quick-slots` untuk widget pemesanan cepat pada landing page.

---

## 4. Fase 3: Booking & Pembayaran
- **Pembuatan Booking (`BookingService::createPending`)**:
  - Validasi ketersediaan jam slot dilakukan sebelum reservasi.
  - Penyisipan baris ke `court_slots` menggunakan transaksi database `DB::transaction(..., 3)`. Jika terjadi `UniqueConstraintViolationException`, ditangkap dan diterjemahkan menjadi pesan kegagalan user-friendly: *"Beberapa slot yang dipilih sudah diambil oleh penyewa lain. Silakan pilih jam lain."*
  - Format kode booking unik: `LPN-{tahun}-{4 digit acak}` dengan retry otomatis hingga 5 kali bila terjadi collision.
  - Snapshot harga per jam ke `booking_items.price_per_hour` (reguler atau prime time berdasarkan jam slot masing-masing).
  - Nilai token check-in di-generate acak sepanjang 64 karakter alphanumeric via `Str::random(64)`.
- **Pengunggahan Bukti Pembayaran (`BookingService::uploadProof`)**:
  - File bukti (jpg, jpeg, png, webp, pdf maks 5MB) disimpan di disk privat (`local`, direktori `storage/app/private/payment-proofs`).
  - File tidak dapat diakses langsung oleh publik. Pengunduhan/preview hanya dapat dilakukan melalui rute terproteksi `GET /bookings/{booking}/proof` yang diawasi oleh `BookingPolicy::view` dan rate limiting `30,1`.
  - Setelah bukti diunggah tepat waktu, status `Payment` berubah menjadi `pending_verification`, `booking.expires_at = null`, dan seluruh `court_slots.locked_until = null` (kunci slot dibekukan sehingga tidak dihapus oleh `ExpireStaleBookings`).
  - Bila bukti diunggah pada booking yang sudah terlanjur `expired`, sistem menolak dengan `ValidationException` yang mengarahkan user menghubungi admin.
- **Otorisasi (`BookingPolicy`)**:
  - `view`: Pemilik booking (customer), operator dari venue terkait (`venue_id` sama), atau super_admin.
  - `update` (upload bukti): Hanya pemilik booking yang berhak mengunggah bukti pada booking miliknya.
  - `cancel`: Customer hanya dapat membatalkan booking berstatus `pending`. Operator dari venue terkait dan super_admin dapat membatalkan booking confirmed/pending.
- **Dukungan Jam Non-Berurutan**:
  - Booking mengizinkan pemilihan jam tidak berurutan dalam satu hari (misal jam 09:00 dan 14:00).
  - Rincian checkout dan e-tiket menampilkan daftar jam individual alih-alih rentang tunggal.
- **Pembatalan oleh Penyewa**:
  - Hanya diperbolehkan untuk booking berstatus `pending`.
  - Menghapus baris `court_slots` terkait seketika, mengubah status booking menjadi `cancelled`, dan payment tetap `unpaid`.
  - Pembatalan booking `confirmed` hanya dapat dilakukan oleh operator atau admin.
