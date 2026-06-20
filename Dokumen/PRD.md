Berikut adalah dokumen **Product Requirement Document (PRD) Lengkap (v2.0)** yang telah diintegrasikan dengan modul **Admin** dan distandarisasi menggunakan format arsitektur komponen modular (**Google Stitch Style**). Dokumen ini sudah mencakup seluruh aspek teknis, data relasional, dan desain antarmuka.

---

# 📄 Product Requirement Document (PRD) — FULL VERSION

## Proyek: Platform Les Online (Real-Time Mentor Discovery, Booking, & Admin Control Center)

---

## 1. Ringkasan Proyek (Project Overview)

Platform Les Online ini adalah aplikasi berbasis web mandiri yang dirancang untuk mempertemukan **Student** (siswa) dan **Mentor** (pengajar) secara langsung. Sistem ini memfasilitasi pencarian mentor berbasis kategori, penjadwalan kelas, transaksi pembayaran aman (berbasis *payment gateway*), akses langsung ke ruang kelas virtual eksternal, sistem ulasan performa, serta panel kendali **Admin** untuk manajemen data global dan validasi platform.

### Spesifikasi Stack Teknologi

* **Backend Framework:** Laravel 11+ (terintegrasi dengan **Laravel Reverb** untuk *native server* WebSocket)
* **Frontend Framework:** Tailwind CSS v3+ & Blade Templating (menggunakan fondasi **Laravel Breeze**)
* **Database:** MySQL / PostgreSQL (Arsitektur Relasional Multi-Tabel)
* **Payment Gateway:** Midtrans API (Sandbox Mode)

---

## 2. Arsitektur Pengguna & Hak Akses (User Roles)

Sistem menggunakan satu pintu registrasi (*single registration entry*), namun pengguna wajib menentukan peran (*role*) mereka sejak awal, kecuali peran Admin yang dibuat melalui *seeder* database atau panel khusus:

1. **Student:** Pengguna yang berhak mencari mentor, memesan slot waktu, melakukan pembayaran, masuk ke link kelas, dan memberikan rating/ulasan pasca-sesi.
2. **Mentor:** Pengguna yang mengelola profil keahlian, mengatur ketersediaan slot waktu mengajar, menerima pesanan, dan memantau laporan pendapatan harian/bulanan.
3. **Admin:** Pengguna dengan hak akses tertinggi yang mengelola data master pengguna (student & mentor), memantau seluruh transaksi platform, dan melihat laporan finansial global.

---

## 3. Spesifikasi Kebutuhan Fungsional (Functional Requirements)

### 3.1. Autentikasi & Manajemen Data (Fitur Wajib 1 & 2)

* **Multi-Role Auth:** Fitur Login dan Register menggunakan komponen bawaan Laravel Breeze dengan styling Tailwind CSS. Form registrasi menyediakan opsi untuk *Student* atau *Mentor*.
* **Manajemen Profil Mentor:** Halaman khusus di mana Mentor wajib mengelola data berupa biodata diri, keahlian khusus (contoh: *Python, Kalkulus, TOEFL*), tarif per jam, serta tautan ruang meeting eksternal (*Google Meet* atau *Zoom* pribadi).

### 3.2. Fitur Pencarian & Filter Mentor (Discovery)

* **Pencarian Berbasis Tag/Kategori:** Student dapat menyaring daftar mentor berdasarkan kata kunci mata pelajaran atau kategori keahlian tertentu melalui komponen *search bar*.
* **Mentor Card (UI/UX Menarik - Nilai Plus 3):** Tampilan daftar mentor dalam bentuk *grid block* responsif yang menampilkan foto profil, rating rata-rata, harga per jam, serta tombol aksi cepat untuk melihat detail jadwal.

### 3.3. Sistem Booking & Transaksi (Fitur Wajib 3 & Nilai Plus 1)

* **Slot Waktu Statis:** Mentor menentukan pilihan slot waktu kosong di dasbor mereka (misal: Senin 19:00, Rabu 20:00) yang disimpan ke dalam tabel jadwal.
* **Reservasi & Payment Gateway:**
1. Student memilih salah satu slot yang tersedia dan menekan tombol **"Book"**.
2. Sistem membuat baris transaksi baru dengan status `pending` dan memicu modul pembayaran **Midtrans Snap**.
3. Setelah pembayaran diverifikasi oleh Midtrans, status transaksi otomatis berubah menjadi `success`.


* **Sinkronisasi Real-Time (WebSocket):** Begitu transaksi sukses, server WebSocket (Laravel Reverb) akan memancarkan (*broadcast*) *event* ke seluruh student lain yang sedang membuka halaman mentor tersebut untuk mengubah status slot menjadi `booked` (dinonaktifkan) secara instan tanpa perlu memuat ulang (*refresh*) halaman web.

### 3.4. Dasbor Aktivitas Informatif (Fitur Wajib 4)

* **Dasbor Student:** Menampilkan ringkasan jam belajar, riwayat pembayaran, serta daftar kelas terdekat yang dilengkapi dengan **Tombol "Masuk Kelas"** (langsung mengarah ke link Google Meet/Zoom milik mentor).
* **Dasbor Mentor:** Menampilkan visualisasi data pendapatan, daftar pesanan masuk dari student, status jadwal mengajar, serta menerima notifikasi *pop-up* secara *real-time* via WebSocket jika ada kelas baru yang dipesan.
* **Dasbor Admin:** Menampilkan statistik global berupa: total pengguna terdaftar (student & mentor), total transaksi sukses, persentase pertumbuhan platform, dan grafik pendapatan kumulatif.

### 3.5. Panel Manajemen Admin & Modul Laporan (Fitur Wajib 2 & 5)

* **Manajemen Data Master (Admin):** Halaman khusus bagi Admin untuk mengelola, menyunting, atau memblokir akun *Student* dan *Mentor* yang melanggar ketentuan. Admin juga memiliki hak akses untuk memantau data seluruh *course* dan jadwal yang aktif di sistem.
* **Modul Laporan Finansial & Cetak PDF:**
* *Mentor:* Dapat mengunduh laporan riwayat transaksi pendapatan mengajar mereka sendiri.
* *Admin:* Dapat menyaring seluruh transaksi platform berdasarkan rentang tanggal dan mengekspornya menjadi dokumen PDF laporan keuangan platform formal menggunakan package `laravel-dompdf`.



### 3.6. Sistem Ulasan Sederhana

* **Ulasan Pasca-Kelas:** Setelah waktu sesi kelas selesai, Student dapat mengirimkan ulasan teks pendek dan rating bintang (1-5) yang langsung dihitung ulang secara otomatis untuk memperbarui akumulasi rating di profil mentor.

---

## 4. Skema Hubungan Database Berelasi (Entity-Relationship)

```
                       [schedules]
                            │
                            ▼
  [users] (role) ───► [transactions] ◄─── [reviews]
   (id, role:               ▲
   student/mentor/admin)    │
     │                      │
     ├─── 1:1 ───► [mentors]

```

* **`users`**: `id`, `name`, `email`, `password`, `role` (`'student'`, `'mentor'`, `'admin'`), `timestamps`
* **`mentors`**: `id`, `user_id` (FK), `bio`, `tarif_per_jam`, `link_meeting`, `rating_rata_rata`
* **`schedules`**: `id`, `mentor_id` (FK), `hari_jam`, `status` (*available*, *booked*)
* **`transactions`**: `id`, `student_id` (FK), `mentor_id` (FK), `schedule_id` (FK), `total_harga`, `status_pembayaran` (*pending*, *success*, *failed*)
* **`reviews`**: `id`, `transaction_id` (FK), `rating`, `komentar`

---

## 5. System Design UI Specification (Google Stitch Style)

### 5.1. Design Tokens (Global Foundations)

* **`color-primary`**: `bg-blue-600` / `text-blue-600` $\rightarrow$ Navigasi aktif, tombol aksi utama, dan tautan penting.
* **`color-accent`**: `bg-amber-400` / `text-amber-400` $\rightarrow$ Bintang rating, tombol cari, dan komponen penarik perhatian.
* **`color-bg-base`**: `bg-gray-50` $\rightarrow$ Latar belakang dasar aplikasi (*canvas*).
* **`color-bg-surface`**: `bg-white` $\rightarrow$ Latar belakang komponen kartu (*card*), navbar, dan container form/tabel.
* **`shape-radius`**: `rounded-xl` (12px) untuk tombol & input / `rounded-2xl` (16px) untuk container kartu utama.

### 5.2. Component Layout Library (Stitch Blocks)

* **`Stitch-Nav` (Global Header):** `bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center`
* **`Stitch-Dashboard-Shell` (Shell Layout):** `flex flex-row min-h-screen bg-gray-50` dengan *Sidebar* tetap berukuran `w-64 bg-white border-r border-gray-100 h-screen fixed`.
* *Admin State:* Menu di sidebar admin disesuaikan berisi tautan ke: *Dashboard*, *Manajemen User*, *Data Transaksi*, dan *Laporan Keuangan*.



### 5.3. Component States & Contextual Badges (`Stitch-Badges`)

Sistem indikator menggunakan pendekatan *soft-color* agar informasi status tetap kontras tanpa merusak dominasi warna utama:

* 🟢 **Success State (Pembayaran Sukses / Kelas Selesai):** `bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-medium`
* 🟡 **Pending State (Menunggu Pembayaran / Slot Tersedia):** `bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-medium`
* 🔵⚪ **Disabled State (Slot Terpesan via WebSocket / Transaksi Gagal):** `bg-gray-100 text-gray-500 border border-gray-200 px-3 py-1 rounded-full text-xs font-medium`

---

## 6. Skenario Pengujian Validasi (Demo Penilaian)

* **Skenario Multi-Role Routing:** Membuktikan bahwa login dengan akun Admin akan mengarah ke halaman panel kontrol admin (`/admin/dashboard`), terpisah dari halaman student dan mentor.
* **Skenario Real-Time Sync:** Menunjukkan siklus pembayaran Midtrans sukses mengubah status invoice, mengirimkan data komisi platform ke dashboard admin, dan memicu penutupan slot jadwal mengajar secara otomatis di browser student lain lewat WebSocket tanpa adanya *reload* manual.