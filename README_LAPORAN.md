# Platform Les Online - Sistem Pembelajaran Berbasis Web

## Identitas Kelompok

**Mata Kuliah** : Kewirausahaan (E-Business)  
**Dosen Pengampu** : Abdul Yamin, S.Pd., M.Kom  
**Kelas** : IF 4A  

### Anggota Kelompok

| No | Nama | NIM | Peran |
|----|----|-----|-------|
| 1  | ... | ... | Fullstack Developer |
| 2  | ... | ... | Backend Developer |
| 3  | ... | ... | Frontend & UI/UX |
| 4  | ... | ... | Dokumentasi & Testing |

---

## Deskripsi Proyek

**Platform Les Online** adalah sebuah platform digital yang memfasilitasi pembelajaran satu-ke-satu antara siswa dan mentor secara online. Platform ini dirancang untuk memudahkan siswa menemukan dan memesan sesi belajar dengan mentor profesional, serta memungkinkan mentor mengatur jadwal dan mengelola earnings mereka.

### Tujuan Proyek
- Menyediakan marketplace pembelajaran online yang aman dan terpercaya
- Memudahkan siswa mengakses pendidikan dari mentor berpengalaman
- Memberikan peluang bagi mentor untuk berbagi pengetahuan dan mendapatkan penghasilan tambahan
- Implementasi sistem pembayaran yang terintegrasi dengan payment gateway profesional

### Target Pengguna
- **Siswa**: Mencari bimbingan belajar, les privat, atau kursus online
- **Mentor**: Profesional pendidikan atau expert yang ingin berbagi pengetahuan
- **Admin**: Mengelola platform, user verification, dan transaksi

---

## Teknologi yang Digunakan

### Frontend
- **Framework CSS**: Tailwind CSS v3.4+ (Utility-first CSS framework)
- **Build Tool**: Vite (Next Generation Frontend Tooling)
- **JavaScript Client**: Laravel Echo + Pusher JS Client (Real-time features)

### Backend
- **Framework**: Laravel 13.8 (PHP 8.3+)
- **WebSocket Server**: Laravel Reverb v1.10 (Real-time messaging)
- **Authentication**: Laravel Breeze v2.4 (Multi-role authentication)
- **Testing**: PHPUnit 12.5.12

### Database
- **Primary**: MySQL 8.0 / PostgreSQL 15
- **Development**: SQLite
- **ORM**: Laravel Eloquent

### Layanan Pihak Ketiga
- **Payment Gateway**: Midtrans API (Sandbox Mode)
  - Midtrans PHP SDK v2.6
  - Snap Integration untuk payment UI
  - Transaction status tracking
  
### Utility Packages
- **PDF Generation**: laravel-dompdf v3.1 (Untuk generate laporan & invoice)
- **Faker**: FakerPHP v1.23 (Database seeding)
- **Tinker**: Laravel Tinker v3.0 (Interactive shell)
- **Logging**: Laravel Pail v1.2.5 (Log monitoring)

### Development Tools
- **Code Quality**: Laravel Pint v1.27 (Code formatting)
- **Testing**: Mockery v1.6 (Mocking library)
- **Error Handling**: Nunomaduro Collision v8.6

---

## Fitur yang Diimplementasikan

### Fitur Wajib
- [x] **Login & Register (Multi Role)** - Sistem autentikasi dengan role student, mentor, admin
- [x] **Manajemen Data (CRUD)** - Multi-tabel operations (users, mentors, schedules, transactions, reviews, dll)
- [x] **Transaksi Pembayaran** - Booking sesi, checkout, riwayat transaksi dengan Midtrans
- [x] **Dashboard Informatif** - Dashboard untuk setiap role dengan statistik & grafik
- [x] **Laporan** - Export laporan ke PDF (menggunakan dompdf)

### Fitur Bonus
- [x] **Payment Gateway Integration** - Midtrans Snap untuk pembayaran yang aman
- [x] **Real-time Messaging** - Chat antara siswa & mentor menggunakan WebSocket (Laravel Reverb)
- [x] **Notification System** - Broadcast notifications menggunakan Laravel events
- [x] **Rating & Review System** - Siswa dapat memberikan rating & review ke mentor
- [x] **Schedule Management** - Mentor dapat mengelola jadwal ketersediaan
- [x] **Withdrawal System** - Mentor dapat menarik earnings ke rekening bank
- [x] **Mentor Favorites** - Siswa dapat menyimpan mentor favorit
- [x] **Responsive UI Design** - Full responsive design dengan Tailwind CSS
- [x] **Multi-database Support** - Support MySQL, PostgreSQL, dan SQLite

---

## Struktur Database

Platform ini menggunakan **10 tabel utama** dalam basis data relasional:

1. **users** - Data pengguna platform (siswa, mentor, admin)
2. **mentors** - Profil mentor dengan keahlian dan rating
3. **schedules** - Jadwal ketersediaan sesi mentor
4. **transactions** - Riwayat pembayaran & booking sesi
5. **reviews** - Rating dan komentar dari siswa ke mentor
6. **mentor_favorites** - Data mentor favorit siswa
7. **withdrawals** - Permintaan penarikan dana mentor
8. **messages** - Chat/messaging antara user
9. **broadcasts** - Pengumuman/notifikasi untuk user
10. **platform_fees** - Konfigurasi biaya platform

Untuk detail lengkap struktur database, lihat: **[Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md](Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md)**

---

## Panduan Instalasi

### Prasyarat

Sebelum melakukan instalasi, pastikan sistem Anda telah memiliki:

- **PHP** >= 8.3
- **Composer** (latest version)
- **Node.js** >= 16.0 dan **npm** >= 8.0
- **MySQL** 8.0+ / **PostgreSQL** 15+ / **SQLite** (built-in)
- **Git**
- **XAMPP** atau **Laravel Valet** (untuk development server)

### Langkah Instalasi

#### 1. Clone Repositori
```bash
git clone [url-repo]
cd lesonline
```

#### 2. Install Dependencies

**Backend (PHP Dependencies)**
```bash
composer install
```

**Frontend (Node Dependencies)**
```bash
npm install
```

#### 3. Setup Environment File
```bash
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan koneksi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=les_online
DB_USERNAME=root
DB_PASSWORD=
```

Atau untuk PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=les_online
DB_USERNAME=postgres
DB_PASSWORD=
```

#### 5. Create Database
```bash
# Untuk MySQL - buat database terlebih dahulu
CREATE DATABASE les_online;

# Atau gunakan command (jika sudah punya akses)
php artisan migrate:fresh
```

#### 6. Run Database Migrations
```bash
php artisan migrate
```

#### 7. Seed Database (Optional - untuk testing data)
```bash
php artisan db:seed
```

#### 8. Setup Midtrans Configuration

Edit `.env` dan tambahkan konfigurasi Midtrans:
```env
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false
```

Dapatkan credentials dari: https://dashboard.sandbox.midtrans.com/

#### 9. Build Assets
```bash
npm run build
```

#### 10. Generate Storage Link (untuk file uploads)
```bash
php artisan storage:link
```

---

## Cara Menjalankan Aplikasi

### Development Mode

Untuk menjalankan semua proses (server, queue, logs, dan Vite) sekaligus:

```bash
npm run dev
```

Atau jalankan secara terpisah:

**Terminal 1 - Laravel Development Server:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker (untuk processing background jobs):**
```bash
php artisan queue:listen --tries=1 --timeout=0
```

**Terminal 3 - Log Monitor:**
```bash
php artisan pail --timeout=0
```

**Terminal 4 - Vite Development Server:**
```bash
npm run dev
```

Aplikasi akan tersedia di: **http://localhost:8000**

### Production Build
```bash
npm run build
php artisan config:cache
```

---

## Testing

Jalankan test suite untuk memastikan semua fitur berfungsi dengan baik:

```bash
npm run test
```

---

## Struktur Project

```
lesonline/
├── app/
│   ├── Events/              # Event classes (broadcasting, messages)
│   ├── Http/
│   │   ├── Controllers/     # Route controllers
│   │   ├── Middleware/      # HTTP middlewares
│   │   └── Requests/        # Form requests validation
│   ├── Models/              # Eloquent models
│   │   ├── User.php
│   │   ├── Mentor.php
│   │   ├── Schedule.php
│   │   ├── Transaction.php
│   │   ├── Review.php
│   │   └── ...
│   ├── Providers/           # Service providers
│   └── View/                # View components
├── bootstrap/               # Bootstrap application
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   ├── factories/           # Model factories
│   └── seeders/             # Database seeders
├── public/                  # Public assets
├── resources/
│   ├── css/                 # CSS/Tailwind files
│   ├── js/                  # JavaScript files
│   └── views/               # Blade templates
├── routes/
│   ├── web.php              # Web routes
│   ├── auth.php             # Auth routes
│   ├── channels.php         # Broadcasting channels
│   └── console.php          # Console commands
├── storage/                 # Storage for uploads, logs
├── tests/                   # Test files
├── .env.example             # Environment template
├── composer.json            # PHP dependencies
├── package.json             # Node dependencies
├── vite.config.js           # Vite configuration
├── tailwind.config.js       # Tailwind CSS configuration
└── README.md                # Project documentation
```

---

## API Endpoints (Overview)

### Authentication
- `POST /register` - Register user baru
- `POST /login` - Login user
- `POST /logout` - Logout user
- `GET /user` - Get current user info

### Mentor Management
- `GET /mentors` - List semua mentor
- `GET /mentors/{id}` - Detail mentor
- `POST /mentors` - Create mentor profile (jika user adalah mentor)
- `PUT /mentors/{id}` - Update mentor profile
- `GET /mentors/{id}/schedules` - Get mentor schedules

### Schedule Management
- `GET /schedules` - List available schedules
- `POST /schedules` - Create schedule (mentor only)
- `PUT /schedules/{id}` - Update schedule
- `DELETE /schedules/{id}` - Delete schedule

### Transactions
- `POST /transactions` - Create booking transaction
- `GET /transactions` - Get user transactions
- `GET /transactions/{id}` - Get transaction detail
- `POST /transactions/{id}/payment` - Process payment via Midtrans

### Reviews
- `POST /reviews` - Submit review (setelah transaksi selesai)
- `GET /reviews/mentor/{mentorId}` - Get mentor reviews

### Messages (Real-time)
- WebSocket connections untuk real-time chat
- `GET /messages/{userId}` - Get chat history
- `POST /messages` - Send message

---

## Konfigurasi Port & URL

| Service | Default URL | Port |
|---------|------------|------|
| Laravel App | http://localhost:8000 | 8000 |
| Vite Dev Server | http://localhost:5173 | 5173 |
| WebSocket (Reverb) | ws://localhost:8080 | 8080 |
| MySQL | localhost | 3306 |
| PostgreSQL | localhost | 5432 |

---

## Troubleshooting

### Issue: "Composer not found"
```bash
# Install Composer globally
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

### Issue: "Node modules not installed"
```bash
npm install
npm run dev
```

### Issue: "Database connection error"
- Pastikan database server sudah running
- Check `.env` file - DB credentials benar
- Run: `php artisan migrate` untuk create tables

### Issue: "Midtrans payment not working"
- Verify MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di `.env`
- Pastikan menggunakan Sandbox mode untuk development
- Check Midtrans dashboard untuk transaction logs

### Issue: "WebSocket/Real-time features tidak berfungsi"
```bash
# Pastikan Laravel Reverb berjalan
php artisan reverb:start

# Atau gunakan npm run dev yang sudah include Reverb
```

---

## Deployment

### Persiapan Production

1. **Update .env untuk production**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   MIDTRANS_IS_PRODUCTION=true
   ```

2. **Optimize untuk production**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. **Setup storage permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

### Deploy ke Hosting

Hosting yang kompatibel:
- **Shared Hosting**: Require PHP 8.3+
- **VPS**: Ubuntu/CentOS dengan PHP 8.3
- **Cloud**: AWS, Google Cloud, DigitalOcean, Heroku

Untuk detail deployment, lihat dokumentasi resmi: [Laravel Deployment](https://laravel.com/docs/deployment)

---

## File Penting untuk Laporan

📄 **[Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md](Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md)** - Spesifikasi perangkat & database lengkap  
📄 **[Dokumen/PRD.md](Dokumen/PRD.md)** - Product Requirements Document  
📄 **[Dokumen/design.md](Dokumen/design.md)** - Design specification  
📄 **[Dokumen/fiturtambahan.md](Dokumen/fiturtambahan.md)** - Fitur tambahan  

---

## Kontribusi

Untuk kontribusi ke project ini:

1. Fork repository
2. Create feature branch: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add some AmazingFeature'`
4. Push ke branch: `git push origin feature/AmazingFeature`
5. Open Pull Request

---

## License

Distributed under the MIT License. Lihat `LICENSE` file untuk detail lebih lanjut.

---

## Support & Contact

Untuk pertanyaan atau support:
- **Email**: [contact email]
- **Issues**: Create issue di GitHub repository
- **Documentation**: Lihat folder `/Dokumen` untuk dokumentasi lengkap

---

## Changelog

### Version 1.0.0 (2026-06-23)
- ✨ Initial release
- ✨ Core features (auth, mentor, schedule, transaction)
- ✨ Payment integration dengan Midtrans
- ✨ Real-time messaging dengan WebSocket
- ✨ Rating & review system
- ✨ Withdrawal system untuk mentor

---

**Last Updated**: 2026-06-23  
**Project Status**: Active Development
