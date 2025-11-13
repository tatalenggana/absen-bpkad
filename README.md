# 📋 Sistem Absensi BPKAD Garut - PKL/Magang Management

Sistem manajemen absensi modern untuk Program Pelatihan Kerja (PKL) BPKAD Garut dengan fitur-fitur lengkap seperti login/register, dashboard admin, dashboard peserta, foto selfie, dan GPS location tracking.

**Status**: ✅ Production Ready

## ✨ Fitur Utama

### 🔐 Autentikasi & Otorisasi

-   ✅ Sistem login dan register yang aman
-   ✅ Password terenkripsi dengan bcrypt (12 rounds)
-   ✅ Role-based access control (Admin & User/Peserta PKL)
-   ✅ Session management dengan database storage
-   ✅ Middleware protection untuk unauthorized access

### ⏰ Sistem Absensi dengan Deadline

-   ✅ **Deadline: Pukul 08:00 Pagi**
-   ✅ Absensi sebelum jam 08:00 → **✅ HADIR** (Hijau)
-   ✅ Absensi setelah jam 08:00 → **⏰ TERLAMBAT** (Kuning)
-   ✅ Check-in dan Check-out otomatis
-   ✅ Riwayat absensi per bulan
-   ✅ Status tracking real-time

### � Foto & Lokasi

-   ✅ Webcam capture untuk foto selfie saat check-in
-   ✅ GPS location tracking dengan geolocation API
-   ✅ Base64 image encoding & storage
-   ✅ Photo gallery di admin dashboard
-   ✅ Location coordinates (latitude, longitude)

### 👥 Profil Peserta PKL

-   ✅ Nama, Email, Password
-   ✅ Sekolah/Universitas
-   ✅ Bidang Penempatan (Akuntansi, Sekretaria, Anggaran, Keuangan, Perbendaharaan)
-   ✅ Profile management

### �📊 Dashboard Admin

-   ✅ Statistik real-time (Total Peserta, Absensi Hari Ini, Terlambat)
-   ✅ Filter absensi berdasarkan tanggal
-   ✅ Filter absensi berdasarkan bulan/tahun
-   ✅ Lihat detail riwayat per peserta
-   ✅ Laporan terperinci dengan tabel lengkap
-   ✅ Pagination untuk data besar
-   ✅ Status badges dengan color coding

### 👤 Dashboard Peserta PKL

-   ✅ Tombol Check-in/Check-out dengan modal
-   ✅ Status absensi hari ini dengan emoji indicators
-   ✅ Statistik bulan berjalan (Hadir/Terlambat/Absen)
-   ✅ Riwayat absensi lengkap
-   ✅ Profile info (Bidang & Sekolah)

### 🎨 UI/UX Modern

-   ✅ Responsive design dengan CSS modern
-   ✅ Gradient headers dan cards
-   ✅ Color-coded status badges
-   ✅ Emoji icons untuk better UX
-   ✅ Loading animations
-   ✅ Form hints & validation feedback
-   ✅ Professional error pages (401, 403, 404, 500)

## 🚀 Installation & Deployment

### Prerequisites

-   PHP 8.2+ (tested with PHP 8.2.12)
-   MySQL 5.7+
-   Composer
-   Node.js & NPM (optional, for frontend build)

### Local Development Setup

#### 1. Clone Repository

```bash
git clone <repository-url>
cd absensi-bpkad
```

#### 2. Install Dependencies

```bash
composer install
npm install
```

#### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Database Configuration

Edit `.env` dan set database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_bpkad
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Migrate Database

```bash
php artisan migrate --seed
```

#### 6. Storage Symlink

```bash
php artisan storage:link
```

#### 7. Cache Configuration

```bash
php artisan config:cache
php artisan route:cache
```

#### 8. Run Development Server

```bash
php artisan serve
```

Akses di: **http://localhost:8000**

### Production Deployment

#### 1. Environment Setup

Update `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
SESSION_DRIVER=database
CACHE_STORE=database
```

#### 2. Database Migration

```bash
php artisan migrate --force
php artisan db:seed
```

#### 3. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

#### 4. Setup File Permissions

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/storage
```

#### 5. Web Server Configuration

**Apache (.htaccess)**:

-   File `.htaccess` sudah dikonfigurasi di `public/` folder
-   Enable mod_rewrite: `a2enmod rewrite`
-   Restart Apache: `systemctl restart apache2`

**Nginx** (sample config):

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/absensi-bpkad/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 📝 Default Credentials

**⚠️ PENTING**: Ganti credentials ini setelah deployment pertama!

```
Admin Account:
- Email: admin@bpkad.local
- Password: admin123

Demo User Account:
- Email: karyawan1@bpkad.local
- Password: password123
```

### 📧 Email Configuration (Optional)

Untuk fitur email (jika ditambahkan):

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bpkad.local
MAIL_FROM_NAME="Absensi BPKAD"
```

## 📁 Struktur Project

```
absensi-bpkad/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Auth logic
│   │   │   └── AttendanceController.php    # Absensi & Admin
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php         # Admin validation
│   │       └── UserMiddleware.php          # User validation
│   └── Models/
│       ├── User.php                        # User model
│       ├── UserProfile.php                 # Profile PKL
│       └── Attendance.php                  # Absensi records
│
├── bootstrap/
│   └── app.php                             # App configuration
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── filesystems.php
│
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_user_profiles_table.php
│   │   └── create_attendances_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── public/
│   ├── index.php                           # Entry point
│   ├── .htaccess                           # Apache config
│   └── storage → ../storage/app/public     # Symlink
│
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                   # Master layout
│   ├── errors/
│   │   ├── 401.blade.php                   # Unauthorized
│   │   ├── 403.blade.php                   # Forbidden
│   │   ├── 404.blade.php                   # Not found
│   │   └── 500.blade.php                   # Server error
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── user/
│   │   ├── dashboard.blade.php
│   │   └── profile.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   └── user-history.blade.php
│   └── welcome.blade.php
│
├── routes/
│   └── web.php                             # Web routes
│
├── storage/
│   └── app/
│       ├── public/attendances/             # Photos storage
│       └── sessions/                       # Session storage
│
├── .env                                    # Environment config
├── .htaccess                               # Laravel rewrite
├── composer.json
├── package.json
├── phpunit.xml
└── README.md
```

## 🔑 Routes & Endpoints

### Public Routes

```
GET  /                  → Home page
GET  /login             → Login form
POST /login             → Submit login
GET  /register          → Register form
POST /register          → Submit register
```

### User Routes (Authenticated + Role: user)

```
GET  /dashboard                   → User dashboard (check-in/check-out)
GET  /profile                     → User profile
POST /attendance/check-in         → Submit check-in (photo + location)
POST /attendance/check-out        → Submit check-out
```

### Admin Routes (Authenticated + Role: admin)

```
GET  /admin/dashboard                  → Admin dashboard (statistics)
GET  /admin/attendance/filter-date     → Filter attendance by date
GET  /admin/attendance/filter-month    → Filter attendance by month/year
GET  /admin/user/{userId}/history     → View user attendance history
```

### Authentication Routes

```
POST /logout                       → Logout user
```

## 📊 Database Schema

### users table

```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role ENUM('user', 'admin'),
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### user_profiles table

```sql
CREATE TABLE user_profiles (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNIQUE,
  school_name VARCHAR(255),
  division ENUM('akuntansi','sekretaria','anggaran','keuangan','perbendaharaan'),
  notes TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### attendances table

```sql
CREATE TABLE attendances (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT,
  date DATE,
  check_in_time TIME,
  check_out_time TIME,
  status ENUM('present', 'late', 'absent'),
  notes TEXT,
  photo_path VARCHAR(255),
  location_latitude VARCHAR(50),
  location_longitude VARCHAR(50),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE KEY (user_id, date),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## 🔐 Security Features

-   ✅ CSRF Protection (Laravel CSRF tokens)
-   ✅ Password Hashing (bcrypt with 12 rounds)
-   ✅ SQL Injection Prevention (Eloquent ORM)
-   ✅ XSS Protection (Blade template escaping)
-   ✅ Session Security (database storage, encryption)
-   ✅ Role-Based Access Control (middleware)
-   ✅ Authorization Headers (HTTP Bearer)
-   ✅ File Upload Validation

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000]: General error"

```bash
php artisan migrate:reset
php artisan migrate --seed
```

### Error: "Storage symlink not found"

```bash
php artisan storage:link
```

### Error: "Class not found"

```bash
composer dump-autoload
```

### Session not persisting

Check `.env`:

```env
SESSION_DRIVER=database
CACHE_STORE=database
```

Run: `php artisan migrate`

### Photos not displaying

```bash
php artisan storage:link
chmod -R 755 storage/app/public
```

## 📞 Support & Contact

Untuk support, hubungi:

-   Email: admin@bpkad.local
-   Departemen IT: [contact-info]

## 📄 License

Licensed under MIT License. Copyright © 2025 BPKAD Garut.
