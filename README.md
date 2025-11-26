# Sistem Absensi PKL BPKAD Garut

## Tentang Aplikasi

Aplikasi ini adalah **Sistem Absensi Digital** yang dirancang khusus untuk memantau dan mengelola kehadiran peserta Praktik Kerja Lapangan (PKL) atau magang di BPKAD Garut.

Konsep utamanya adalah menciptakan solusi modern, akurat, dan transparan untuk manajemen kehadiran dengan mengintegrasikan **Validasi Lokasi Berbasis GPS (Geofencing)** dan **Bukti Foto Digital**. Peserta PKL hanya dapat melakukan absensi jika berada dalam radius yang telah ditentukan dari lokasi kantor, dan setiap absensi wajib menyertakan foto sebagai bukti kehadiran.

## Fitur Utama

### Untuk Admin

-   Dashboard dengan statistik real-time (Total Peserta, Hadir Hari Ini, Terlambat)
-   Kelola data peserta PKL (CRUD)
-   Laporan absensi lengkap dengan filter per bidang penempatan dan tanggal
-   Pengaturan sistem dinamis:
    -   Set jam batas check-in
    -   Set lokasi geofencing (latitude, longitude, radius)
    -   Update alamat lokasi absensi
-   Generate laporan (Excel, PDF, CSV)

### Untuk Peserta PKL (User)

-   Check-in dan check-out dengan validasi GPS
-   Capture foto otomatis saat absensi
-   Lihat riwayat kehadiran pribadi
-   Edit profile (nama, email, sekolah, bidang penempatan)
-   Filter riwayat per tanggal

### Fitur Keamanan & Validasi

-   Geofencing: Absensi hanya berlaku dalam radius 500m dari lokasi kantor
-   Validasi GPS real-time
-   Distance calculation otomatis
-   Status detection (Hadir, Terlambat, Absen)
-   Password hashing dengan bcrypt
-   CSRF protection & Session management

### Desain & User Experience

-   Modern UI dengan tema biru profesional
-   Responsive design (Mobile, Tablet, Desktop)
-   Gradient stat cards dengan smooth animations
-   Dark sidebar navigation
-   FontAwesome icons
-   Dashboard modern dan intuitif

---

## Akun Default untuk Testing

### Admin

-   Email: admin@bpkad.local
-   Password: admin123

### Peserta PKL

-   Email: karyawan1@bpkad.local
-   Password: password123

---

## Prasyarat & Teknologi

-   PHP 8.2.12 atau lebih baru
-   Laravel 12.37.0
-   MySQL/MariaDB
-   Composer
-   Node.js & NPM
-   Browser modern dengan dukungan Geolocation API
-   Smartphone dengan GPS aktif (untuk pengujian di lapangan)

## Instalasi

### 1. Clone Repositori

```bash
git clone https://github.com/tatalenggana/absen-bpkad.git
cd absen-bpkad
```

### 2. Install Dependensi

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```
DB_DATABASE=absensi_bpkad
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migration & Seeding

```bash
php artisan migrate --seed
```

### 6. Build Assets

```bash
npm run dev
```

### 7. Jalankan Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

Catatan: Pastikan koordinat lokasi geofencing sudah diatur di halaman Admin Settings sebelum melakukan testing absensi.

---

## Struktur Database

### Tabel Utama

-   **users** - Data pengguna (admin & peserta PKL)
-   **user_profiles** - Detail profile peserta (sekolah, bidang penempatan)
-   **attendances** - Riwayat absensi (check-in/out, foto, GPS, status)
-   **sessions** - Session management

## Struktur Folder

```
absen-bpkad/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AttendanceController.php
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── UserMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── UserProfile.php
│       └── Attendance.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── user/
│   │   ├── admin/
│   │   └── components/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── public/
├── storage/
├── .env.example
└── README.md
```

## Pembuat

**Gista Nuru Arifah Putri Lenggana**

Sistem Absensi PKL BPKAD Garut - 2025
