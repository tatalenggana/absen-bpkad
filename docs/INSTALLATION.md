# Panduan Instalasi & Setup

## Prasyarat

Pastikan Anda memiliki:

-   PHP 8.2.12 atau lebih baru
-   Composer
-   Node.js & NPM
-   MySQL/MariaDB
-   Git

## Langkah-Langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/tatalenggana/absen-bpkad.git
cd absen-bpkad
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Setup Environment File

```bash
cp .env.example .env
```

Kemudian edit file `.env` dan sesuaikan konfigurasi database:

```
DB_DATABASE=absensi_bpkad
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Jalankan Database Migration

```bash
php artisan migrate
```

### 6. Seed Database (Opsional - untuk data testing)

```bash
php artisan migrate --seed
```

Ini akan membuat akun admin dan beberapa peserta testing.

### 7. Install Node Dependencies

```bash
npm install
```

### 8. Build Frontend Assets

```bash
npm run dev
```

### 9. Start Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Akun Testing (Setelah Seeding)

### Admin Account

-   Email: admin@bpkad.local
-   Password: admin123

### Peserta Account

-   Email: karyawan1@bpkad.local
-   Password: password123

## Konfigurasi Geofencing

Setelah login sebagai admin:

1. Buka halaman "Pengaturan" di sidebar
2. Scroll ke section "Pengaturan Lokasi Absensi"
3. Atur koordinat latitude, longitude, dan radius
4. Klik "Ambil Lokasi Saya" untuk auto-detect atau input manual
5. Klik "Simpan Lokasi"

Default location (SMKN 1 Garut):

-   Latitude: -7.202507
-   Longitude: 107.890626
-   Radius: 500 meter

## Troubleshooting

### Database Connection Error

Pastikan service MySQL/MariaDB sudah running dan konfigurasi `.env` benar.

### CORS Error

Pastikan browser mendukung Geolocation API dan user mengizinkan akses lokasi.

### Assets Not Loading

Jalankan kembali `npm run dev` dan clear browser cache.

### Port 8000 Already in Use

Gunakan port berbeda:

```bash
php artisan serve --port=8001
```

## Dokumentasi Lebih Lanjut

Lihat folder `docs/` untuk panduan-panduan lainnya.
