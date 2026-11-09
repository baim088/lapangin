# Ringkasan Skema Database & State Machine Lapangin

Dokumen ini berisi dokumentasi skema database final hasil migrasi Laravel serta spesifikasi state machine untuk booking, payment, dan court slot.

---

## 1. Daftar Enum

| Enum | Class | Values | Deskripsi |
|---|---|---|---|
| UserRole | `App\Enums\UserRole` | `customer`, `operator`, `super_admin` | Peran pengguna |
| UserStatus | `App\Enums\UserStatus` | `active`, `suspended` | Status akun |
| SportCategory | `App\Enums\SportCategory` | `futsal`, `badminton`, `mini_soccer`, `padel` | Kategori cabang olahraga |
| CourtStatus | `App\Enums\CourtStatus` | `active`, `maintenance`, `inactive` | Status operasional lapangan |
| SlotBlockType | `App\Enums\SlotBlockType` | `booking`, `maintenance`, `event`, `manual_block` | Tipe pemblokiran slot |
| BookingStatus | `App\Enums\BookingStatus` | `pending`, `confirmed`, `cancelled`, `expired`, `done` | Status booking |
| PaymentMethod | `App\Enums\PaymentMethod` | `qris`, `bank_transfer`, `va`, `ewallet`, `cash` | Metode pembayaran |
| PaymentStatus | `App\Enums\PaymentStatus` | `unpaid`, `pending_verification`, `paid`, `refund_pending`, `refunded`, `expired`, `rejected` | Status pembayaran |

---

## 2. Struktur Tabel & Kolom

### 2.1 Tabel `venues`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `name` | varchar(255) | No | - | Nama venue |
| `slug` | varchar(255) | No | - | UNIQUE |
| `address` | text | No | - | Alamat lengkap venue |
| `phone` | varchar(255) | No | - | Nomor telepon venue |
| `email` | varchar(255) | Yes | NULL | Email kontak venue |
| `open_time` | time | No | `08:00:00` | Jam buka operasional |
| `close_time` | time | No | `23:00:00` | Jam tutup operasional |
| `prime_start_time`| time | No | `17:00:00` | Awal jam prime time |
| `prime_end_time` | time | No | `23:00:00` | Akhir jam prime time |
| `slot_lock_duration_minutes` | int unsigned | No | `10` | Durasi kunci slot saat booking pending |
| `status` | varchar(255) | No | `'active'` | Status venue |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps |

### 2.2 Tabel `users`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `venue_id` | bigint unsigned | Yes | NULL | FK -> `venues.id` (`nullOnDelete`) |
| `name` | varchar(255) | No | - | Nama pengguna |
| `email` | varchar(255) | No | - | UNIQUE |
| `phone` | varchar(255) | No | - | Nomor telepon (Index) |
| `email_verified_at` | timestamp | Yes | NULL | Waktu verifikasi email |
| `password` | varchar(255) | No | - | Hashed password |
| `role` | varchar(255) | No | `'customer'` | Enum `UserRole` (Index) |
| `status` | varchar(255) | No | `'active'` | Enum `UserStatus` (Index) |
| `remember_token` | varchar(100) | Yes | NULL | Token sesi |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps |

### 2.3 Tabel `courts`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `venue_id` | bigint unsigned | No | - | FK -> `venues.id` (`restrictOnDelete`, Index) |
| `name` | varchar(255) | No | - | Nama lapangan (misal "Lapangan 1 - Vinyl") |
| `slug` | varchar(255) | No | - | UNIQUE |
| `sport_category` | varchar(255) | No | - | Enum `SportCategory` (Index) |
| `surface_type` | varchar(255) | No | - | Jenis lantai (Vinyl, Rumput Sintetis, dll.) |
| `specs` | json | Yes | NULL | Detail spesifikasi tambahan |
| `price_regular` | bigint unsigned | No | - | Harga reguler per jam (Rupiah integer) |
| `price_prime` | bigint unsigned | No | - | Harga prime time per jam (Rupiah integer) |
| `status` | varchar(255) | No | `'active'` | Enum `CourtStatus` (Index) |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps |

### 2.4 Tabel `bookings`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `venue_id` | bigint unsigned | No | - | FK -> `venues.id` (`restrictOnDelete`) |
| `user_id` | bigint unsigned | No | - | FK -> `users.id` (`restrictOnDelete`, Index) |
| `booking_code` | varchar(255) | No | - | UNIQUE (`LPN-{tahun}-{4 digit acak}`) |
| `customer_name` | varchar(255) | No | - | Nama penyewa |
| `customer_phone` | varchar(255) | No | - | No HP penyewa |
| `customer_email` | varchar(255) | No | - | Email penyewa |
| `notes` | text | Yes | NULL | Catatan tambahan |
| `total_hours` | int unsigned | No | - | Total jam yang dipesan |
| `subtotal` | bigint unsigned | No | - | Total harga sebelum diskon/biaya |
| `total_amount` | bigint unsigned | No | - | Total tagihan akhir |
| `status` | varchar(255) | No | `'pending'` | Enum `BookingStatus` (Index) |
| `expires_at` | timestamp | Yes | NULL | Batas waktu bayar (Index) |
| `checkin_token` | varchar(64) | No | - | Token check-in QR / teks UNIQUE |
| `checked_in_at` | timestamp | Yes | NULL | Waktu check-in |
| `checked_in_by` | bigint unsigned | Yes | NULL | FK -> `users.id` (`nullOnDelete`) |
| `cancelled_at` | timestamp | Yes | NULL | Waktu pembatalan |
| `cancelled_reason` | text | Yes | NULL | Alasan pembatalan |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps (Index `created_at`) |

### 2.5 Tabel `booking_items`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `booking_id` | bigint unsigned | No | - | FK -> `bookings.id` (`restrictOnDelete`, Index) |
| `court_id` | bigint unsigned | No | - | FK -> `courts.id` (`restrictOnDelete`, Index) |
| `booking_date` | date | No | - | Tanggal sewa |
| `start_time` | time | No | - | Jam mulai |
| `end_time` | time | No | - | Jam selesai |
| `is_prime` | boolean | No | `false` | Status jam prime time |
| `price_per_hour` | bigint unsigned | No | - | Snapshot harga per jam saat booking dibuat |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps |

*Index gabungan*: `['booking_date', 'start_time']`.

### 2.6 Tabel `court_slots`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `court_id` | bigint unsigned | No | - | FK -> `courts.id` (`restrictOnDelete`) |
| `booking_id` | bigint unsigned | Yes | NULL | FK -> `bookings.id` (`restrictOnDelete`, Index) |
| `booking_date` | date | No | - | Tanggal slot |
| `start_time` | time | No | - | Jam mulai (misal `08:00:00`) |
| `end_time` | time | No | - | Jam selesai (misal `09:00:00`) |
| `block_type` | varchar(255) | No | `'booking'` | Enum `SlotBlockType` |
| `note` | varchar(255) | Yes | NULL | Catatan blokir (wajib untuk operator block) |
| `locked_until` | timestamp | Yes | NULL | Batas waktu kunci slot (Index) |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps |

*Unique Constraint*: `UNIQUE('court_id', 'booking_date', 'start_time')`
*Index gabungan*: `['booking_date', 'court_id']`.

#### Invarian Model `CourtSlot`:
1. `block_type === 'booking'` <=> `booking_id !== null`
2. `block_type !== 'booking'` <=> `booking_id === null`
Invarian ditegakkan pada hook `CourtSlot::saving`. Wajib buat slot via Eloquent (`create`/`save`).

### 2.7 Tabel `payments`
| Kolom | Tipe | Nullable | Default | Keterangan / Constraint |
|---|---|---|---|---|
| `id` | bigint unsigned | No | AUTO_INCREMENT | Primary Key |
| `booking_id` | bigint unsigned | No | - | FK -> `bookings.id` (`restrictOnDelete`, Index) |
| `payment_method` | varchar(255) | No | - | Enum `PaymentMethod` (Index) |
| `status` | varchar(255) | No | `'unpaid'` | Enum `PaymentStatus` (Index) |
| `amount` | bigint unsigned | No | - | Jumlah nominal pembayaran (Rupiah integer) |
| `proof_path` | varchar(255) | Yes | NULL | Path file bukti transfer di storage private |
| `proof_uploaded_at` | timestamp | Yes | NULL | Waktu upload bukti |
| `provider` | varchar(255) | Yes | NULL | Provider pembayaran |
| `external_reference` | varchar(255) | Yes | NULL | Referensi eksternal (Index) |
| `verified_by` | bigint unsigned | Yes | NULL | FK -> `users.id` (`nullOnDelete`) |
| `verified_at` | timestamp | Yes | NULL | Waktu verifikasi oleh operator |
| `created_at` / `updated_at` | timestamp | Yes | NULL | Timestamps |

---

## 3. State Machine (Bookings / Payments / Court Slots)

```
                    ┌────────────────────────┐
                    │ (a) Buat Booking       │
                    │ Booking: pending       │
                    │ Payment: unpaid        │
                    │ Slot: locked_until=exp │
                    └───────────┬────────────┘
                                │
        ┌───────────────────────┴───────────────────────┐
        │ Tepat waktu upload bukti                      │ Lewat expires_at tanpa bukti
        ▼                                               ▼
┌──────────────────────────────┐                ┌──────────────────────────────┐
│ (b) Upload Bukti             │                │ (c) Expired                  │
│ Booking: pending (exp=NULL)  │                │ Booking: expired             │
│ Payment: pending_verification│                │ Payment: expired             │
│ Slot: locked_until=NULL      │                │ Slot: DIHAPUS                │
└──────────────┬───────────────┘                └──────────────────────────────┘
               │
       ┌───────┴────────────────────────┐
       │ Operator konfirmasi            │ Operator tolak
       ▼                                ▼
┌──────────────────────────────┐ ┌──────────────────────────────┐
│ (d) Konfirmasi               │ │ (e) Tolak                    │
│ Booking: confirmed           │ │ Booking: cancelled (reason)  │
│ Payment: paid                │ │ Payment: rejected            │
│ Slot: locked_until=NULL      │ │ Slot: DIHAPUS                │
└──────────────┬───────────────┘ └──────────────────────────────┘
               │
               │ Operator batalkan confirmed
               ▼
┌──────────────────────────────┐
│ (g) Batal Confirmed          │
│ Booking: cancelled           │
│ Payment: refund_pending      │
│ Slot: DIHAPUS                │
│ (Refund manual di luar)      │
└──────────────────────────────┘

Alur Pembatalan Lainnya:
- (f) Penyewa batalkan pending: Booking: cancelled (cancelled_at), Payment: unpaid, Slot: DIHAPUS.
- (h) Upload bukti pada booking expired: Ditolak dengan pesan menghubungi admin (re-lock ditunda).
```
