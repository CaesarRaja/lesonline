# Platform Les Online

**Sistem pembelajaran berbasis web yang menghubungkan siswa dengan mentor profesional untuk sesi belajar personal.**

## 🎯 Quick Start

### Prerequisites
- PHP 8.3+
- Composer
- Node.js 16+
- MySQL 8.0 / PostgreSQL 15 / SQLite

### Installation
```bash
# Clone & setup
git clone [repo-url]
cd lesonline
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Build & run
npm run build
php artisan serve
```

Visit: **http://localhost:8000**

---

## 📋 Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 13.8 (PHP 8.3) |
| **Frontend** | Tailwind CSS v3.4 + Vite |
| **Database** | MySQL 8 / PostgreSQL 15 / SQLite |
| **Real-time** | Laravel Reverb v1.10 + WebSocket |
| **Auth** | Laravel Breeze v2.4 (Multi-role) |
| **Payment** | Midtrans API v2.6 |
| **PDF** | barryvdh/laravel-dompdf v3.1 |

---

## ✨ Core Features

- **Multi-role Authentication** - Student, Mentor, Admin roles
- **Mentor Management** - Profile, keahlian, tarif per jam
- **Schedule Booking** - Jadwal fleksibel dengan real-time availability
- **Payment Integration** - Secure checkout dengan Midtrans Snap
- **Real-time Chat** - WebSocket-based messaging
- **Rating & Reviews** - Post-session feedback system
- **Withdrawal System** - Mentor earnings management
- **Responsive Design** - Mobile-first UI/UX

---

## 🚀 Development

### Run Full Dev Stack
```bash
npm run dev
```

**Includes**: Laravel server, Queue worker, Log monitoring, Vite HMR

### Individual Processes
```bash
# API Server
php artisan serve

# WebSocket Server
php artisan reverb:start

# Queue Worker
php artisan queue:listen

# Frontend Build
npm run dev
```

---

## 📚 Database

**10 Core Tables**: users, mentors, schedules, transactions, reviews, mentor_favorites, withdrawals, messages, broadcasts, platform_fees

See: [SPESIFIKASI_SISTEM_AKTUAL.md](./Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md) for full ERD & schema

---

## 🧪 Testing

```bash
npm run test
```

---

## 📖 Full Documentation

- **Installation Guide** → [README_LAPORAN.md](./README_LAPORAN.md)
- **Database Schema** → [Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md](./Dokumen/SPESIFIKASI_SISTEM_AKTUAL.md)
- **Product Spec** → [Dokumen/PRD.md](./Dokumen/PRD.md)
- **Design Guide** → [Dokumen/design.md](./Dokumen/design.md)

---

## 🛠️ Useful Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Reset DB
php artisan db:seed              # Seed data
php artisan tinker               # Interactive shell

# Cache & Config
php artisan cache:clear
php artisan config:cache

# Asset Build
npm run build                     # Production build
npm run dev                       # Development with HMR
```

---

## 📦 Project Structure

```
lesonline/
├── app/Models/                  # Eloquent models
├── app/Http/Controllers/        # Route handlers
├── database/migrations/         # DB schema
├── resources/views/             # Blade templates
├── resources/js/                # JavaScript
├── resources/css/               # Tailwind CSS
├── routes/                      # Route definitions
├── Dokumen/                     # Project documentation
└── tests/                       # Test suite
```

---

## 🔑 API Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/mentors` | GET | List mentors |
| `/api/schedules` | GET | Available schedules |
| `/api/transactions` | POST | Create booking |
| `/api/reviews` | POST | Submit review |
| `/api/messages` | WS | Real-time chat |

---

## 🚨 Troubleshooting

**Database connection error?**
```bash
php artisan config:clear
# Update .env with correct credentials
php artisan migrate
```

**Midtrans not working?**
- Verify MIDTRANS_SERVER_KEY & CLIENT_KEY in .env
- Check sandbox mode: `MIDTRANS_IS_PRODUCTION=false`

**WebSocket issues?**
```bash
php artisan reverb:start
```

---

## 📝 License

MIT License - See LICENSE file

---

## 👥 Contributors

Team: [List team members here]

---

**Last Updated**: 2026-06-23 | **Status**: Active Development

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
