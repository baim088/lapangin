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
