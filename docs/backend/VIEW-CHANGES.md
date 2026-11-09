# Log Perubahan View (Blade Frontend)

Dokumen ini mencatat setiap perubahan pada file view di `resources/views/` agar rekan frontend (Ibrahim) dapat mereview dengan mudah.

| File | Baris | Sebelum | Sesudah | Alasan Perubahan |
|---|---|---|---|---|
| `resources/views/auth/login.blade.php` | 91, 103, 107-111, 140-144 | `action="#"`, tanpa `value="{{ old('email') }}"`, tanpa pesan error | `action="{{ route('login') }}"`, `value="{{ old('email') }}"`, `@error('email')`, `@error('password')` | Mengaktifkan form submit login, retensi input email, dan pesan validasi error Indonesia |
| `resources/views/auth/register.blade.php` | 85, 93, 103, 114, 147 | `action="#"`, input tanpa value old(), terms tanpa checked | `action="{{ route('register') }}"`, `old()` untuk name/phone/email/terms, `@error` untuk name, phone, email, password, terms | Mengaktifkan form registrasi customer baru dengan pesan error dan retensi input |
| `resources/views/components/navbar.blade.php` | 49-74, 131-155 | Hardcoded tombol Masuk dan Daftar pada desktop & mobile | Dibungkus `@guest` dan `@auth`: menampilkan nama user, link dashboard sesuai role (Customer/Operator/Admin), dan tombol Keluar (POST logout) | Menyesuaikan navigasi autentikasi dinamis sesuai status sesi dan peran pengguna |
