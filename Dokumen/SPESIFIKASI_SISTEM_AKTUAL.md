# 2.2 Spesifikasi Perangkat

Tabel berikut merangkum spesifikasi perangkat lunak dan perangkat keras yang digunakan dalam proses pengembangan platform Les Online.

## Spesifikasi Perangkat Lunak

| Kategori | Komponen | Spesifikasi |
|----------|----------|------------|
| Perangkat Lunak | Framework Backend | Laravel 13.8 (PHP 8.3+) |
| | Framework CSS | Tailwind CSS v3.4+ |
| | WebSocket Server | Laravel Reverb (v1.10) |
| | Authentication | Laravel Breeze (v2.4 - Multi-role) |
| | Database | MySQL 8.0 / PostgreSQL 15 / SQLite |
| | Payment Gateway | Midtrans API (Sandbox) |
| | PDF Generator | laravel-dompdf (barryvdh/laravel-dompdf v3.1) |
| | Build Tool | Vite |
| Perangkat Keras | Processor | Intel Core i5 Gen 8 atau setara |
| | RAM | 8 GB (16 GB direkomendasikan) |
| | Storage | SSD 256 GB (minimum) |
| | Koneksi Internet | Min. 10 Mbps (untuk Reverb & Midtrans) |

---

# 2.3 Struktur Database

Platform Les Online menggunakan sepuluh (10) tabel utama dalam basis data relasional. Berikut adalah deskripsi setiap tabel beserta relasinya:

## Tabel 1: users

Tabel pengguna menyimpan informasi dasar semua pengguna platform (siswa, mentor, dan admin).

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key auto-increment | PK, AI |
| name | VARCHAR(255) | Nama lengkap pengguna | NOT NULL |
| email | VARCHAR(255) | Alamat email unik | NOT NULL, UNIQUE |
| email_verified_at | TIMESTAMP | Waktu verifikasi email | NULLABLE |
| password | VARCHAR(255) | Password terenkripsi (bcrypt) | NOT NULL |
| role | VARCHAR(255) | student, mentor, admin | NOT NULL, DEFAULT 'student' |
| verification_status | VARCHAR(255) | Verified, pending, rejected | NOT NULL, DEFAULT 'verified' |
| remember_token | VARCHAR(100) | Token untuk remember me | NULLABLE |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |
| deleted_at | TIMESTAMP | Soft delete timestamp | NULLABLE |

---

## Tabel 2: mentors

Tabel profil mentor menyimpan informasi spesifik mentor seperti keahlian, rating, dan tarif.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| user_id | BIGINT UNSIGNED | FK ke tabel users (One-to-One) | FK, UNIQUE, NOT NULL |
| bio | TEXT | Deskripsi singkat mentor | NULLABLE |
| tarif_per_jam | DECIMAL(12,2) | Tarif per jam sesi (Rupiah) | NOT NULL, DEFAULT 0 |
| link_meeting | VARCHAR(255) | URL meeting default mentor | NULLABLE |
| rating_rata_rata | DECIMAL(3,2) | Rating rata-rata dari ulasan | NOT NULL, DEFAULT 0 |
| keahlian | VARCHAR(255) | Mata pelajaran/bidang keahlian | NULLABLE |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 3: schedules

Tabel jadwal menyimpan slot-slot waktu tersedia yang ditawarkan oleh setiap mentor.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| mentor_id | BIGINT UNSIGNED | FK ke tabel mentors (One-to-Many) | FK, NOT NULL |
| waktu_mulai | DATETIME | Waktu mulai sesi | NOT NULL |
| waktu_selesai | DATETIME | Waktu selesai sesi | NOT NULL |
| status | ENUM | 'available' atau 'booked' | NOT NULL, DEFAULT 'available' |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 4: transactions

Tabel transaksi menyimpan data pembayaran dan pemesanan sesi dari siswa ke mentor.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| student_id | BIGINT UNSIGNED | FK ke tabel users (student) | FK, NOT NULL |
| mentor_id | BIGINT UNSIGNED | FK ke tabel mentors | FK, NOT NULL |
| schedule_id | BIGINT UNSIGNED | FK ke tabel schedules | FK, NOT NULL |
| total_harga | DECIMAL(12,2) | Total nominal transaksi (Rp) | NOT NULL |
| status_pembayaran | VARCHAR(255) | pending, paid, failed, cancelled | NOT NULL, DEFAULT 'pending' |
| midtrans_order_id | VARCHAR(255) | ID order unik dari Midtrans | NULLABLE |
| midtrans_transaction_id | VARCHAR(255) | ID transaksi dari Midtrans | NULLABLE |
| midtrans_response | JSON | Response lengkap dari Midtrans | NULLABLE |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 5: reviews

Tabel ulasan menyimpan rating dan komentar dari siswa terhadap mentor setelah selesai sesi.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| transaction_id | BIGINT UNSIGNED | FK ke tabel transactions (One-to-One) | FK, UNIQUE, NOT NULL |
| rating | TINYINT UNSIGNED | Nilai rating 1-5 bintang | NOT NULL |
| komentar | TEXT | Komentar/review dari siswa | NULLABLE |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 6: mentor_favorites

Tabel favorit mentor menyimpan data mentor-mentor yang ditandai favorit oleh siswa.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| student_id | BIGINT UNSIGNED | FK ke tabel users (student) | FK, NOT NULL |
| mentor_id | BIGINT UNSIGNED | FK ke tabel mentors | FK, NOT NULL |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |
| - | - | Composite unique key (student_id, mentor_id) | UNIQUE |

---

## Tabel 7: withdrawals

Tabel penarikan dana menyimpan data permintaan penarikan earnings dari mentor.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| mentor_id | BIGINT UNSIGNED | FK ke tabel mentors | FK, NOT NULL |
| jumlah | DECIMAL(12,2) | Nominal dana yang ditarik (Rp) | NOT NULL |
| bank | VARCHAR(255) | Nama bank tujuan | NOT NULL |
| no_rekening | VARCHAR(255) | Nomor rekening tujuan | NOT NULL |
| atas_nama | VARCHAR(255) | Nama pemilik rekening | NOT NULL |
| status | ENUM | pending, approved, rejected | NOT NULL, DEFAULT 'pending' |
| alasan_penolakan | TEXT | Alasan jika penarikan ditolak | NULLABLE |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 8: messages

Tabel pesan menyimpan data komunikasi/chat antara siswa dan mentor.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| sender_id | BIGINT UNSIGNED | FK ke tabel users | FK, NOT NULL |
| receiver_id | BIGINT UNSIGNED | FK ke tabel users | FK, NOT NULL |
| content | LONGTEXT | Isi pesan | NOT NULL |
| is_read | TINYINT(1) | Status pesan sudah dibaca | NOT NULL, DEFAULT 0 |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 9: broadcasts

Tabel broadcast menyimpan pengumuman/notifikasi yang dikirim ke users.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| title | VARCHAR(255) | Judul broadcast | NOT NULL |
| message | LONGTEXT | Isi pesan broadcast | NOT NULL |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Tabel 10: platform_fees

Tabel fee platform menyimpan konfigurasi biaya/komisi platform dari setiap transaksi.

| Kolom | Tipe Data | Keterangan | Constraint |
|-------|-----------|-----------|-----------|
| id | BIGINT UNSIGNED | Primary key | PK, AI |
| percentage | DECIMAL(5,2) | Persentase fee dari transaksi | NOT NULL |
| created_at | TIMESTAMP | Waktu pembuatan record | AUTO |
| updated_at | TIMESTAMP | Waktu update terakhir | AUTO |

---

## Relasi Antar Tabel

### One-to-One Relationships
- **users ↔ mentors**: Satu akun user dengan role 'mentor' memiliki tepat satu record profil di tabel mentors (dihubungkan melalui `mentors.user_id` dengan constraint UNIQUE)
- **schedules ↔ transactions**: Satu slot jadwal hanya dapat di-booking dalam satu transaksi (dihubungkan melalui `transactions.schedule_id`)
- **transactions ↔ reviews**: Satu transaksi yang telah selesai hanya dapat memiliki satu ulasan (dihubungkan melalui `reviews.transaction_id` dengan constraint UNIQUE)

### One-to-Many Relationships
- **mentors → schedules**: Satu mentor dapat memiliki banyak slot jadwal; setiap slot jadwal hanya milik satu mentor (dihubungkan melalui `schedules.mentor_id`)
- **users (student) → transactions**: Satu siswa dapat melakukan banyak transaksi; setiap transaksi memiliki satu siswa pemilik (dihubungkan melalui `transactions.student_id`)
- **mentors → transactions**: Satu mentor dapat memiliki banyak transaksi booking; setiap transaksi melibatkan satu mentor (dihubungkan melalui `transactions.mentor_id`)
- **users (student) → mentor_favorites**: Satu siswa dapat menyimpan banyak mentor favorit (dihubungkan melalui `mentor_favorites.student_id`)
- **mentors → mentor_favorites**: Satu mentor dapat disukai oleh banyak siswa (dihubungkan melalui `mentor_favorites.mentor_id`)
- **mentors → withdrawals**: Satu mentor dapat melakukan banyak penarikan dana (dihubungkan melalui `withdrawals.mentor_id`)
- **users → messages**: Satu user dapat mengirim/menerima banyak pesan (dihubungkan melalui `messages.sender_id` dan `messages.receiver_id`)

### Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│      users      │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (UNIQUE)  │
│ password        │
│ role            │◄─────────┐
│ verification... │          │ One-to-One
│ created_at      │          │
└─────────────────┘          │
        │                    │
        │ One-to-Many        │
        │                    │
        ├──────────┬─────────┤
        │          │         │
        │          │         │
        │          │    ┌──────────────┐
        │          │    │   mentors    │
        │          │    ├──────────────┤
        │          │    │ id (PK)      │
        │          │    │ user_id (FK) │
        │          │    │ bio          │
        │          │    │ tarif_...    │
        │          │    │ keahlian     │
        │          │    └──────────────┘
        │          │         │
        │          │         │ One-to-Many
        │          │         │
        │          │    ┌──────────────┐
        │          │    │  schedules   │
        │          │    ├──────────────┤
        │          │    │ id (PK)      │
        │          │    │ mentor_id(FK)│
        │          │    │ waktu_mulai  │
        │          │    │ status       │
        │          │    └──────────────┘
        │          │         │
        │          │         │ One-to-One
        │          │         │
        │    ┌─────────────────────────┐
        │    │   transactions          │
        │    ├─────────────────────────┤
        │    │ id (PK)                 │
        │    │ student_id (FK) ◄──────┼────┤
        │    │ mentor_id (FK)  ◄──────┼────┤
        │    │ schedule_id (FK)◄──────┘    │
        │    │ total_harga             │   │
        │    │ status_pembayaran       │   │
        │    │ midtrans_*              │   │
        │    └─────────────────────────┘   │
        │              │                    │
        │              │ One-to-One         │
        │              │                    │
        │         ┌─────────────┐           │
        │         │   reviews   │           │
        │         ├─────────────┤           │
        │         │ id (PK)     │           │
        │         │ transaction │           │
        │         │ rating      │           │
        │         │ komentar    │           │
        │         └─────────────┘           │
        │                                   │
        │ One-to-Many                       │
        │                                   │
        └──────────────────┬────────────────┘
                           │
                ┌──────────┴──────────┬─────────────┐
                │                     │             │
          ┌──────────────┐    ┌──────────────┐    ┌──────────────┐
          │ mentor_fav.. │    │  withdrawals │    │   messages   │
          ├──────────────┤    ├──────────────┤    ├──────────────┤
          │ student_id   │    │ mentor_id(FK)│    │ sender_id    │
          │ mentor_id    │    │ jumlah       │    │ receiver_id  │
          │              │    │ bank         │    │ content      │
          └──────────────┘    │ status       │    │ is_read      │
                              └──────────────┘    └──────────────┘
```

---

## Catatan Implementasi

1. **Role dalam users**: Menggunakan VARCHAR(255) dengan nilai default 'student' untuk fleksibilitas penambahan role baru di masa depan.

2. **Status Enum**: 
   - `schedules.status`: Menggunakan ENUM dengan nilai 'available' dan 'booked' untuk performa query yang lebih baik
   - `transactions.status_pembayaran`: VARCHAR untuk accommodate berbagai status dari Midtrans
   - `withdrawals.status`: ENUM untuk pembatasan nilai yang ketat

3. **Midtrans Integration**: Tabel transactions menyimpan `midtrans_order_id`, `midtrans_transaction_id`, dan `midtrans_response` (JSON) untuk tracking dan audit trail lengkap.

4. **Soft Delete**: Tabel users memiliki `deleted_at` column untuk soft delete functionality yang memungkinkan data recovery jika diperlukan.

5. **Timestamps**: Semua tabel memiliki `created_at` dan `updated_at` untuk audit trail dan sorting.

6. **Foreign Key Constraints**: Semua foreign key menggunakan `cascadeOnDelete()` untuk memastikan data integrity dan automatic cleanup saat record parent dihapus.
