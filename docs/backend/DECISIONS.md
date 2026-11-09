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

---

## 5. Fase 4: Panel Operator (Venue Milik Sendiri)
- **Otorisasi & Lingkup Venue**:
  - Operator hanya dapat melihat, mengelola booking, jadwal, dan lapangan dari `venue_id` miliknya (`$user->venue_id`).
  - Bila operator mencoba mengakses atau memanipulasi booking/lapangan milik venue lain, sistem mengembalikan HTTP `403 Forbidden`.
  - Super admin yang mengakses panel operator memiliki akses fallback ke venue aktif.
- **Formula & Metrik Dashboard**:
  - **Booking Hari Ini**: Jumlah booking yang memiliki setidaknya satu item main pada hari ini (`booking_date = today`).
  - **Pendapatan Hari Ini**: Total omzet (`sum(total_amount)`) dari booking berstatus `confirmed` atau `done` yang jadwal mainnya hari ini.
  - **Antrean Menunggu Konfirmasi**: Jumlah booking dengan status `pending` atau pembayaran berstatus `pending_verification`.
  - **Pembatalan Hari Ini**: Jumlah booking yang dibatalkan (`cancelled` atau `expired`) dengan jadwal main hari ini.
  - **Rumus Okupansi Lapangan (%)**:
    $$\text{Okupansi (\%)} = \min\left(100, \text{round}\left(\frac{\text{Jumlah Jam Slot Terisi / Terkunci Hari Ini}}{\text{Total Jam Operasional Venue}} \times 100\right)\right)$$
    - Total Jam Operasional Venue dihitung dari selisih `venue.close_time - venue.open_time` dalam satuan jam.
    - Lapangan berstatus `maintenance` dikecualikan dari okupansi aktif dan ditandai secara visual.
- **Aksi Antrean Booking (State Machine Enforcement)**:
  - **Konfirmasi**: `POST /operator/booking/{booking}/confirm` memicu `BookingService::confirmByOperator`, mengubah status booking menjadi `confirmed`, payment menjadi `paid`, mencatat `verified_by` dan `verified_at`, serta mempertahankan slot permanen.
  - **Tolak**: `POST /operator/booking/{booking}/reject` mewajibkan pengisian `reason`, memicu `BookingService::rejectByOperator`, mengubah status booking menjadi `cancelled`, payment menjadi `rejected`, mencatat `cancelled_reason`, dan menghapus slot di database.
  - **Batalkan Booking Confirmed**: `POST /operator/booking/{booking}/cancel` mewajibkan pengisian `reason`, memicu `BookingService::cancelConfirmedByOperator`, mengubah status booking menjadi `cancelled`, payment menjadi `refund_pending`, dan menghapus baris slot agar lapangan dapat dipesan kembali.
- **Kelola Jadwal & Pemblokiran Manual**:
  - Hanya slot bebas (tidak ada baris `court_slots` aktif atau terkunci) yang dapat diblokir manual via `POST /operator/jadwal/block`.
  - Catatan/alasan pemblokiran wajib diisi (`note`). Tipe pemblokiran: `manual_block`, `maintenance`, atau `event`.
  - Pembukaan blokir via `DELETE /operator/jadwal/unblock/{id}` hanya diizinkan untuk slot non-booking (`block_type !== 'booking'` dan `booking_id === null`). Slot reservasi penyewa tidak dapat dihapus lewat rute ini guna menjaga integritas data.
- **Daftar Booking & Ekspor CSV**:
  - Menyediakan filter status (`all`, `confirmed`, `pending`, `done`, `cancelled`), pencarian teks bebas (kode booking, nama, telepon), dan pagination 10 per halaman.
  - Ekspor CSV sederhana via `GET /operator/booking/export` menghasilkan file CSV terunduh secara streaming (`StreamedResponse`) sesuai filter pencarian yang sedang aktif.
- **Manajemen Lapangan (CRUD)**:
  - Penambahan lapangan baru via `POST /operator/lapangan` otomatis men-generate slug unik berformat `{base-slug}-{counter}`.
  - Pengubahan informasi teknis, jenis permukaan, harga reguler, dan harga prime time via `PUT /operator/lapangan/{court}`.
  - Penonaktifan lapangan via `PATCH /operator/lapangan/{court}/toggle-status` mengubah status antara `active` dan `maintenance` (aturan: dinonaktifkan, tidak pernah di-hard delete).

---

## 6. Fase 5: Panel Super Admin
- **Otorisasi & Keamanan Akses**:
  - Semua rute admin (`/admin/*`) berada di bawah grup middleware `role:super_admin`.
  - Akses oleh pengguna non-autentikasi (guest) otomatis dialihkan ke halaman login (`/login`).
  - Akses oleh pengguna selain role `super_admin` (baik customer maupun operator) ditolak dengan HTTP `403 Forbidden`.
- **Dashboard Global KPI & Kesehatan Platform**:
  - **Total Venue**: Jumlah seluruh entitas venue terdaftar di sistem (`Venue::count()`).
  - **Total Pengguna**: Jumlah seluruh akun pengguna (`User::count()`).
  - **Total Reservasi**: Akumulasi seluruh reservasi di sistem (`Booking::count()`).
  - **Total GMV (Gross Merchandise Value)**: Total omzet (`sum(total_amount)`) dari seluruh booking dengan status `confirmed` atau `done`.
  - **Daftar Ringkasan Venue**: Menampilkan statistik spesifik per venue (jumlah lapangan terpasang, total booking, dan GMV masing-masing venue).
  - **Pengguna Baru**: Menampilkan 5 pengguna terdaftar terakhir beserta status dan perannya.
- **Pengelolaan Konfigurasi Venue**:
  - Pembaruan informasi venue melalui `PUT /admin/venues/{venue}` via `UpdateVenueRequest`.
  - **Validasi Jam Operasional**:
    - `open_time` dan `close_time` harus berformat `H:i`.
    - `close_time` wajib setelah (`after:open_time`).
  - **Validasi Jam Prime Time**:
    - `prime_start_time` dan `prime_end_time` harus berformat `H:i`.
    - `prime_end_time` wajib setelah (`after:prime_start_time`).
  - **Validasi Durasi Kunci Slot**:
    - `slot_lock_duration_minutes` dibatasi antara 5 menit sampai 60 menit (`integer`, `min:5`, `max:60`).
- **Administrasi Pengguna**:
  - Halaman `GET /admin/users` menyediakan fitur pencarian nama/email/telepon, filter peran pengguna (`customer`, `operator`, `super_admin`), serta pagination.
  - **Toggle Status Akun (Aktif / Suspend)**:
    - Rute `PATCH /admin/users/{user}/toggle-status` membalik status antara `active` dan `suspended`.
    - Dilakukan via `forceFill(['status' => ...])` untuk kepatuhan proteksi mass-assignment.
    - **Self-Protection Guard**: Super admin dilarang men-suspend atau menonaktifkan akunnya sendiri; jika dicoba, sistem mengembalikan error validasi sesi.
  - **Pembaruan Peran (Role) & Penugasan Venue**:
    - Rute `PUT /admin/users/{user}/role` via `UpdateUserRoleRequest`.
    - Pengisian role dan `venue_id` dilakukan eksplisit melalui `$user->forceFill([...])->save()`, tidak melalui mass-assignment.
    - **Aturan Penugasan Operator**: Jika role yang dipilih adalah `operator`, maka `venue_id` bersifat wajib (`Rule::requiredIf(...)`) dan harus valid terdaftar di tabel `venues`.
    - Jika role diubah menjadi non-operator (`customer` atau `super_admin`), `venue_id` otomatis di-set ke `null`.
    - **Self-Protection Guard**: Super admin dilarang menurunkan peran (demote) akunnya sendiri menjadi role lain; jika dicoba, sistem mengembalikan error validasi sesi.

---

## 7. Fase 6: Hardening, DemoSeeder & Audit Keamanan
- **DemoSeeder (Khusus Local Environment & Idempotent)**:
  - Dipanggil dari `DatabaseSeeder` secara kondisional hanya ketika `app()->environment('local')`.
  - Idempotent: memeriksa `Booking::where('notes', 'like', '%[DEMO]%')->exists()`; jika sudah ada data demo, seeder langsung keluar tanpa duplikasi atau error.
  - Menggunakan `BookingService` riil untuk membuat siklus transaksi realistis:
    - 1 booking `pending` (unpaid, slot terkunci).
    - 1 booking `pending` dengan bukti transfer terunggah (`pending_verification`, slot lock beku).
    - 1 booking `confirmed` prime time oleh operator.
    - 1 booking `confirmed` untuk hari ini agar dashboard operator langsung terisi metrik riil saat dev.
    - 1 booking `cancelled` karena pembayaran ditolak operator beserta alasan penolakan.
    - 1 booking `cancelled` atas pembatalan inisiatif penyewa sendiri.
- **Audit Mandiri Keamanan & Rute**:
  - Seluruh rute non-GET (POST, PUT, PATCH, DELETE) memiliki proteksi `@csrf` di setiap form Blade dan verifikasi CSRF middleware Laravel.
  - Semua rute administratif dan operasional (`/admin/*`, `/operator/*`, `/dashboard/*`) diproteksi middleware `role` spesifik dan otorisasi `BookingPolicy`.
  - File bukti transfer disimpan di disk privat (`storage/app/payment-proofs`) dan tidak dapat diakses publik. Akses download/streaming melalui rute `GET /bookings/{booking}/proof` diatur ketat oleh `BookingPolicy` (hanya penyewa pemilik booking, operator venue terkait, dan super_admin yang diizinkan).
  - Tidak ada fungsi debugging (`dd()`, `dump()`, `var_dump()`) yang tertinggal di basis kode aplikasi.
  - Review testimoni pada `resources/views/lapangan-detail.blade.php:288` yang mengandung nama statis "Ahmad Fauzi" merupakan komponen visual mockup review lapangan dari frontend Ibrahim dan sengaja dipertahankan tanpa mengganggu data riil user.
