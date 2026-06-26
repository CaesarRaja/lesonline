# Les Online — Real-Time Mentor Discovery, Booking & Admin Control Center

## Identitas Kelompok
- **Mata Kuliah** : Kewirausahaan (E-Business)
- **Dosen Pengampu** : Abdul Yamin, S.Pd., M.Kom
- **Kelas** : IF 4A

### Anggota Kelompok
| No | Nama | NIM | Peran |
|----|------|-----|-------|
| 1  | M. Yawad Arrahman | 2924007 | Project Manager |
| 2  | Mart Kellin | 2924017 | Analisis Sistem |
| 3  | Caesar Raja Yusri | 2924034 | Programmer |

---

## Deskripsi Proyek

**BimbelEdu** adalah platform e-business berbasis web yang mempertemukan siswa dan mentor/tutor secara daring. Platform ini hadir untuk menjawab permasalahan keterbatasan akses pendidikan berkualitas, ketidakfleksibelan jadwal, dan sulitnya verifikasi kredibilitas tutor pada sistem les konvensional.

Inovasi utama platform ini adalah **Real-Time Slot Synchronization** menggunakan Laravel Reverb (WebSocket), di mana saat seorang siswa berhasil melakukan booking dan menyelesaikan pembayaran, slot jadwal mentor secara otomatis ditandai penuh (*disabled*) di layar seluruh pengguna aktif secara instan — tanpa perlu *page refresh*.

**Target Pengguna:** Siswa yang ingin mencari mentor berkualitas, Mentor/Tutor yang ingin mengelola jadwal dan mendapatkan siswa lebih luas, serta Admin platform.

---

## Teknologi yang Digunakan

### Frontend
- Blade Templating Engine (Laravel)
- Tailwind CSS v3.4+ (Google Stitch Style Design)
- Laravel Echo + Pusher JS Client (real-time UI)

### Backend
- Laravel 13 (arsitektur MVC)
- Laravel Reverb v1.x (WebSocket Server)
- Laravel Breeze (Autentikasi Multi-Role)
- laravel-dompdf `barryvdh/laravel-dompdf` (Generator PDF)

### Database
- MySQL 8.0 / PostgreSQL 15

### CMS (jika digunakan)
- Tidak menggunakan CMS

### Layanan Pihak Ketiga
- **Payment Gateway** : Midtrans API (mode Sandbox)
- **Cek Ongkir** : -
- **Hosting** : Belum dideploy (lingkungan lokal/localhost)

---

## Fitur yang Diimplementasikan

### Fitur Wajib
- [x] Login & Register (Multi Role: Student, Mentor, Admin)
- [x] Manajemen Data (CRUD multi-tabel: Mentor, Jadwal, Materi, Ulasan, Penarikan Saldo)
- [x] Transaksi (keranjang, checkout via Midtrans Snap, riwayat transaksi)
- [x] Dashboard Informatif (grafik & statistik per role)
- [x] Laporan (PDF — laporan transaksi)

### Fitur Bonus
- [x] Payment Gateway (Midtrans Snap — mode Sandbox)
- [ ] Cek Ongkos Kirim
- [x] Desain UI/UX Responsif
- [ ] Deploy Online
- [x] Real-Time Slot Availability via WebSocket (Laravel Reverb)
- [x] Live Chat antar pengguna
- [x] Sistem Ulasan & Rating Mentor
- [x] Manajemen Penarikan Saldo (Withdrawal) untuk Mentor

---

## Panduan Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 8.0 atau PostgreSQL 15
- XAMPP / Laragon (opsional, untuk lingkungan lokal)

### Langkah Instalasi

1. **Clone repositori**
   ```bash
   git clone https://github.com/CaesarRaja/lesonline.git
   cd lesonline
   ```

2. **Install dependencies PHP**
   ```bash
   composer install
   ```

3. **Install dependencies JavaScript**
   ```bash
   npm install
   ```

4. **Konfigurasi environment**
   - Salin `.env.example` menjadi `.env`
   ```bash
   cp .env.example .env
   ```
   - Sesuaikan konfigurasi database dan kunci Midtrans di file `.env`:
   ```env
   DB_DATABASE=lesonline
   DB_USERNAME=root
   DB_PASSWORD=

   MIDTRANS_SERVER_KEY=your_server_key
   MIDTRANS_CLIENT_KEY=your_client_key
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi & Seed database**
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan server WebSocket (Laravel Reverb)**
   ```bash
   php artisan reverb:start
   ```

8. **Jalankan asset bundler (Vite)**
   ```bash
   npm run dev
   ```

9. **Jalankan server Laravel**
   ```bash
   php artisan serve
   ```

10. Buka di browser: `http://localhost:8000`

---

## Akun Demo

| Role    | Email                      | Password   |
|---------|----------------------------|------------|
| Admin   | admin@bimbeledu.com        | password   |
| Student | student@bimbeledu.com      | password   |
| Mentor  | mentor@bimbeledu.com       | password   |
| Mentor  | mentor2@bimbeledu.com      | password   |
| Mentor  | mentor3@bimbeledu.com      | password   |

---

## Tampilan Aplikasi (Screenshot)

*Screenshot akan ditambahkan.*

---

## Video Demo

*Link video demonstrasi akan ditambahkan.*

---

## Link Repository

- GitHub: [https://github.com/CaesarRaja/lesonline](https://github.com/CaesarRaja/lesonline)

---

## Catatan Tambahan

- Integrasi Midtrans menggunakan **mode Sandbox** (simulasi), belum menggunakan mode Production.
- Sesi mentoring dilaksanakan melalui platform video conference eksternal (Google Meet / Zoom). Tautan sesi dibagikan oleh mentor melalui fitur booking/informasi jadwal.
- Laporan PDF dibangkitkan menggunakan library `barryvdh/laravel-dompdf`.
- Platform dikembangkan dan diuji dalam lingkungan lokal menggunakan server development Laravel; belum dideploy ke lingkungan produksi cloud.
- Untuk menjalankan fitur real-time, pastikan server **Laravel Reverb** (`php artisan reverb:start`) berjalan bersamaan dengan `php artisan serve` dan `npm run dev`.
