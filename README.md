# Sistem Absensi PKL BPKAD Garut

## Tentang Website ku

Website ini adalah **Sistem Absensi Digital** yang dirancang khusus untuk memantau dan mengelola kehadiran peserta Praktik Kerja Lapangan (PKL) atau magang di BPKAD Garut.

Konsep utamanya adalah menciptakan solusi modern, akurat, dan transparan untuk manajemen kehadiran dengan mengintegrasikan **Validasi Lokasi Berbasis GPS (Geofencing)** dan **Bukti Foto Digital**. Peserta PKL hanya dapat melakukan absensi jika berada dalam radius yang telah ditentukan dari lokasi kantor, dan setiap absensi wajib menyertakan foto sebagai bukti kehadiran.

## Fitur Utama

### Autentikasi & Akun

-   Login Admin
-   Login Peserta PKL
-   Register User Baru
-   Password hashing dengan bcrypt
-   Session management yang aman
-   CSRF protection

### Untuk Admin

-   Dashboard dengan statistik real-time (Total Peserta, Hadir Hari Ini, Terlambat)
-   Kelola data peserta PKL (CRUD - Create, Read, Update, Delete)
-   Laporan absensi lengkap dengan filter per bidang penempatan dan tanggal
-   Pengaturan sistem dinamis:
    -   Set jam batas check-in
    -   Set lokasi geofencing (latitude, longitude, radius)
    -   Update alamat lokasi absensi
    -   Konfigurasi radius geofencing (default 500m)
-   Generate laporan (Excel, PDF, CSV)
-   Kelola akun peserta PKL
-   Tampil riwayat absensi seluruh peserta
-   Export data absensi dalam berbagai format

### Untuk Peserta PKL (User)

-   Check-in dan check-out dengan validasi GPS real-time
-   Capture foto otomatis saat absensi sebagai bukti
-   Lihat riwayat kehadiran pribadi dengan detail lengkap
-   Edit profile (nama, email, sekolah, bidang penempatan)
-   Filter riwayat absensi per tanggal
-   Deteksi otomatis status kehadiran (Hadir, Terlambat, Absen)
-   Notifikasi jika berada di luar area geofencing
-   Laporan personal kehadiran

### Fitur Keamanan & Validasi

-   **Geofencing**: Absensi hanya berlaku dalam radius 500m dari lokasi kantor
-   Validasi GPS real-time dengan akurasi tinggi
-   Distance calculation otomatis (haversine formula)
-   Status detection cerdas (Hadir, Terlambat, Absen)
-   Password hashing dengan bcrypt
-   CSRF protection & Session management
-   Verifikasi lokasi sebelum absensi
-   Bukti foto digital untuk setiap absensi
-   Pencegahan multiple check-in/out dalam waktu singkat
-   Logging aktivitas untuk audit trail

### Desain & User Experience

-   Modern UI dengan tema biru profesional
-   Responsive design (Mobile, Tablet, Desktop)
-   Gradient stat cards dengan smooth animations
-   Dark sidebar navigation untuk kemudahan akses
-   FontAwesome icons yang intuitif
-   Dashboard modern dan interaktif
-   Loading indicators untuk feedback UX
-   Form validation real-time
-   Toast notifications untuk konfirmasi aksi
-   Adaptif dengan berbagai ukuran layar dan orientasi perangkat

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

### Entity Relationship Diagram (ERD)

![ERD Absensi BPKAD](./public/image/ErdAbseBPKAD.png)

#### Penjelasan Singkat ERD

Diagram ini menunjukkan tabel-tabel utama dan hubungan di antara mereka:

-   **`users`**: Menyimpan data dasar pengguna sistem
    -   **Kolom Penting:** `id` (PK), `nama`, `email`, `password`, `role` (admin/user)
-   **`user_profiles`**: Menyimpan informasi profil tambahan
    -   **Hubungan:** **One-to-One** dengan `users`
    -   **Kolom Penting:** `user_id` (FK), `school_name`, `division`, `notes`
-   **`attendances`**: Menyimpan catatan absensi pengguna
    -   **Hubungan:** **One-to-Many** dengan `users`
    -   **Kolom Penting:** `user_id` (FK), `date`, `check_in_time`, `check_out_time`, `location_latitude`, `location_longitude`, `status`, `photo_path`
    -   **Constraint:** Unique pada `(user_id, date)` - satu user hanya bisa absen 1x per hari

**Inti:** Satu **User** → satu **User Profile** + banyak **Attendances**

---

### UML Class Diagram

![UML Absensi BPKAD](./public/image/UmlAbsenBPKAD.png)

#### Penjelasan Singkat UML

Diagram kelas ini menggambarkan struktur sistem dari perspektif object-oriented (Laravel):

-   **`Users`**: Kelas utama untuk otentikasi

    -   Relasi **1-to-1** dengan `user_profiles`
    -   Relasi **1-to-many** dengan `attendances` dan `sessions`

-   **`User_profiles`**: Detail profil peserta PKL

    -   Unique constraint pada `user_id` (one-to-one relationship)
    -   Menyimpan sekolah, bidang penempatan, catatan

-   **`Attendances`**: Catatan absensi dengan GPS & Foto

    -   Status: `present`, `late`, `absent`
### Desain Responsive

Admin panel telah dioptimalkan untuk berbagai ukuran layar:

#### **Desktop (> 768px)**
- Sidebar penuh (250px) di sebelah kiri
- Konten utama dengan margin-left 250px
- Tabel lengkap dengan semua kolom
- Forms dalam layout horizontal (flex-row)

#### **Tablet (600px - 768px)**
- Sidebar diperkecil menjadi 200px
- Konten diatur dengan margin-left 200px
- Stats grid menjadi 1 kolom
- Tabel dengan font lebih kecil
- Forms masih horizontal

#### **Mobile (≤ 600px)**
- **Sidebar geser-geser (slide-in)** ← Fitur utama
  - Hidden secara default (`left: -200px`)
  - Tekan tombol hamburger (ikon ☰) untuk buka/tutup
  - Overlay backdrop untuk close otomatis
  - Smooth animation left/right

- **Hamburger Menu Button**
  - Floating button di bawah kanan (position: fixed)
  - Ikon bars untuk toggle sidebar
  - Hanya muncul di mobile

- **Content Optimization**
  - Main content full width ketika sidebar tertutup
  - Stats cards menjadi single column
  - Table dengan horizontal scroll (min-width: 600px)
  - Form controls full width
  - Padding/margin dikurangi untuk space efficiency
  - Font size dikecilkan (optimal untuk mobile)

- **Touch-Friendly**
  - Button & link dengan padding 44px+ (tap target size)
  - Spacing antar element untuk kemudahan tap
  - Close sidebar saat klik menu atau konten
  - Resize detection untuk auto-adjust layout

#### **Extra Small (≤ 480px)**
- Optimasi ekstrim untuk phone kecil
- Sidebar width: 180px
- Font size lebih kecil
- Padding minimal
- Table columns tetap navigable dengan scroll

#### **Implementasi JavaScript**
```javascript
// Toggle sidebar dengan hamburger button
sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active'); // Add/remove class 'active'
});

// Auto-close sidebar saat klik konten
mainContent.addEventListener('click', () => {
    if (window.innerWidth <= 600) {
        sidebar.classList.remove('active');
    }
});

// Responsive check saat resize window
window.addEventListener('resize', () => {
    if (window.innerWidth > 600) {
        sidebar.classList.remove('active');
        sidebarToggle.style.display = 'none';
    }
});
```

**Hasil:** Admin panel sekarang **mobile-friendly** dan optimal untuk Android/iOS devices! ✅

---



| Aspek      | ERD                                        | UML                                              |
| ---------- | ------------------------------------------ | ------------------------------------------------ |
| **Fokus**  | Struktur basis data logis                  | Struktur kode (object-oriented)                  |
| **Detail** | Tipe data kolom (`int`, `varchar`, `date`) | Constraints (`UNIQUE`, `ENUM`), relasi eksplisit |
| **Tujuan** | Desain database schema                     | Desain class & hubungan antar class              |

---

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
