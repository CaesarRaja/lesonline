Berdasarkan dokumen Product Requirement Document (PRD) v2.0 yang telah Anda sediakan, berikut adalah **Peningkatan Fitur Komprehensif (Fitur Tambahan Lengkap)** untuk masing-masing peran (*Student, Mentor, dan Admin*).

Penambahan fitur ini dirancang agar tetap selaras dengan arsitektur teknologi yang Anda gunakan, yaitu **Laravel 11+**, **Tailwind CSS**, **Laravel Reverb (WebSocket)**, dan **Midtrans Payment Gateway**.

---

# 📄 Suplemen Tambahan Fitur Komprehensif (PRD Eksplisit v2.5)

## 1. Perluasan Fitur Terintegrasi: Per Peran (User Roles Expansion)

### 1.1. Peran: Student (Siswa)

* **Sistem Chat Real-Time Konsultasi Pra-Booking:**
* **Deskripsi:** Student dapat mengirimkan pesan instan kepada Mentor sebelum memutuskan untuk menekan tombol **"Book"**. Fitur ini berfungsi untuk konsultasi awal mengenai materi yang akan diajarkan.


* **Teknis:** Menggunakan `PrivateChannel` pada Laravel Reverb untuk memastikan pesan tersinkronisasi secara langsung tanpa *reload* halaman.




* **Fitur Fleksibilitas Jadwal (Reschedule & Cancellation Request):**
* **Deskripsi:** Student dapat mengajukan pembatalan atau perubahan jadwal slot waktu yang telah dipesan minimal 24 jam sebelum kelas dimulai.


* **Teknis:** Jika pembatalan disetujui, status slot pada tabel *schedules* akan otomatis kembali menjadi `available` dan memicu WebSocket untuk memperbarui tampilan student lain secara *real-time*.




* **Material & Assignment Hub (Pusat Unduhan Materi):**
* **Deskripsi:** Pada Dasbor Student, disediakan tab khusus untuk mengunduh dokumen pembelajaran (PDF, slide presentasi, atau instruksi tugas) yang diunggah oleh Mentor khusus untuk sesi kelas tersebut.




* **Sistem Manajemen Kupon & Promo:**
* **Deskripsi:** Komponen form pada halaman reservasi sebelum memicu Midtrans Snap, di mana Student dapat memasukkan kode promo untuk mendapatkan potongan harga.




* **Fitur Bookmark / Mentor Favorit:**
* **Deskripsi:** Student dapat menandai (*bookmark*) profil Mentor tertentu agar masuk ke dalam daftar favorit di dasbor mereka. Langkah ini mempermudah pencarian berulang tanpa perlu menggunakan *search bar* kembali.





### 1.2. Peran: Mentor (Pengajar)

* **Sistem Pengajuan Penarikan Dana (Earning Withdrawal System):**
* **Deskripsi:** Melengkapi fitur laporan finansial dan visualisasi pendapatan pada dasbor, Mentor dapat mengajukan penarikan saldo (*payout request*) atas akumulasi transaksi yang berstatus `success`.


* **Teknis:** Pengajuan ini akan masuk ke antrean validasi panel kendali Admin sebelum ditransfer ke rekening bank Mentor.






* **Kalender Pengecualian Dinamis (Exception Calendar):**
* **Deskripsi:** Mentor dapat mengatur hari libur atau jam sibuk tak terduga pada kalender dasbor mereka, yang secara otomatis menonaktifkan slot waktu statis tertentu agar tidak bisa dipilih oleh Student.




* **Upload Center Dokumen Pembelajaran:**
* **Deskripsi:** Halaman khusus bagi Mentor untuk mengunggah berkas materi belajar yang dikhususkan bagi Student yang telah menyelesaikan proses verifikasi pembayaran pembayaran Midtrans.




* **Modul Analitik Ulasan & Performa Pengajaran:**
* **Deskripsi:** Grafik dan metrik di Dasbor Mentor yang merinci tren rating bintang serta statistik umpan balik teks pendek dari Student pasca-kelas selesai.





### 1.3. Peran: Admin (Control Center)

* **Gerbang Verifikasi Profil Mentor Baru (KYC - Know Your Customer Workflow):**
* **Deskripsi:** Akun Mentor yang baru mendaftar melalui sistem registrasi tidak langsung aktif secara publik. Admin wajib memvalidasi biodata, sertifikat keahlian, dan tautan ruang meeting eksternal terlebih dahulu.


* **Teknis:** Menu khusus manajemen data master Admin untuk mengubah status verifikasi profil mentor (`pending_verification`, `verified`, `rejected`).




* **Konfigurator Komisi Platform (Platform Fee Management):**
* **Deskripsi:** Admin dapat menentukan persentase atau nominal potongan tetap biaya layanan platform (misal: 10% per transaksi sukses) secara dinamis melalui panel kontrol admin.




* **Pusat Resolusi Sengketa & Refund Pembayaran (Dispute Resolution Center):**
* **Deskripsi:** Panel khusus untuk menangani klaim apabila terjadi kendala teknis (seperti Mentor tidak hadir di tautan kelas eksternal). Admin dapat mengubah status transaksi secara manual menjadi `failed` atau `refunded`.




* **Moderasi Konten & Ulasan Publik:**
* **Deskripsi:** Hak akses penuh bagi Admin untuk meninjau, menyensor, atau menghapus ulasan teks pendek dari Student yang mengandung unsur pelanggaran ketentuan platform.




* **Manajemen Broadcast Pengumuman Instan:**
* **Deskripsi:** Admin dapat mengirimkan notifikasi global berupa pengumuman penting atau kode diskon baru yang akan muncul sebagai *pop-up* atau spanduk *real-time* di dasbor Student maupun Mentor menggunakan Laravel Reverb.