\<h1 align="center"\>Sistem Absensi PKL BPKAD Garut 📍\</h1\>

## 

\<h2 id="tentang"\>🤔 Apa Konsep web yang saya buat?\</h2\>

Aplikasi ini adalah **Sistem Absensi Digital** yang dirancang khusus untuk memantau dan mengelola kehadiran peserta Praktik Kerja Lapangan (PKL) atau magang di BPKAD Garut.

Konsep utamanya adalah menciptakan solusi modern, akurat, dan transparan untuk manajemen kehadiran dengan mengintegrasikan **Validasi Lokasi Berbasis GPS (Geofencing)** dan **Bukti Foto**.

\<h2 id="fitur"\>🤨 Fitur apa aja sih yg ada di proyek ini?\</h2\>

### Fitur Kunci Aplikasi

  * **Validasi Geofencing (GPS)**
      * Absensi (*Check-in* dan *Check-out*) hanya dapat dilakukan jika peserta berada dalam radius yang telah ditentukan dari lokasi kantor.
  * **Bukti Kehadiran**
      * Setiap absensi wajib menyertakan foto yang diambil secara *real-time* sebagai bukti kehadiran di lokasi.
  * **Dashboard Modern**
      * Antarmuka yang responsif dan mudah digunakan untuk Admin dan Peserta PKL.

### Multi User

#### 👨‍💼 Admin

  * **Pemantauan Real-Time:** Melihat seluruh aktivitas absensi saat ini.
  * **Pengelolaan Peserta:** Mengelola dan memperbarui data peserta PKL.
  * **Generate Laporan:** Menghasilkan laporan kehadiran yang akurat dan periodik (misalnya, harian, mingguan, bulanan).
  * **Pengaturan Sistem Dinamis:**
      * Mengubah batas waktu absensi (*check-in / check-out*).
      * Mengatur ulang titik lokasi GPS (*Geofencing radius*) **tanpa perlu mengubah kode program**.

#### 🧑‍💻 Peserta PKL (User)

  * **Absensi Cepat:** Melakukan *check-in* dan *check-out* harian dengan mudah melalui *smartphone*.
  * **Riwayat Kehadiran:** Melihat dan memantau riwayat kehadiran mereka sendiri.
  * **Integrasi Smartphone:** Dirancang untuk akses yang lancar melalui perangkat seluler.

-----

\<h2 id="testing-account"\>👤 Akun Default untuk Pengujian\</h2\>

*(Silakan sesuaikan kredensial ini jika proyek Anda sudah memiliki data awal)*

### 👨‍💼 Admin

  - Nama Pengguna: adminbpkad
  - Kata Sandi: 123456

### 🧑‍💻 Peserta PKL

  - Nama Pengguna: pkl001
  - Kata Sandi: 123456

-----

\<h2 id="pre-requisite"\>💾 Prasyarat\</h2\>

*(Sesuaikan versi teknologi ini dengan yang Anda gunakan)*

  * PHP (Misalnya, v8.1 atau yang lebih baru) & Web Server (Apache/Nginx)
  * Database (Misalnya, MySQL/MariaDB)
  * Framework: **[Sebutkan Framework Anda di sini, cth: Laravel/CodeIgniter]**
  * Untuk penggunaan di lapangan, diperlukan **Smartphone** dengan fitur **GPS aktif**.

\<h2 id="installation"\>💻 Instalasi\</h2\>

\<h3 id="develop-yourself"\>🏃‍♂️ Mengembangkan Sendiri\</h3\>

1.  Klona repositori:

<!-- end list -->

```bash
git clone [Alamat Repo Anda]
cd [Nama Folder Proyek]
```

2.  Instal dependensi dan konfigurasi environment:

<!-- end list -->

```bash
composer install
npm install
cp .env.example .env
# Ubah koneksi DB dan API Key GPS/Layanan Lokasi di file .env
```

3.  Setup Database:

<!-- end list -->

```bash
php artisan key:generate
php artisan migrate --seed
```

4.  Jalankan aplikasi:

<!-- end list -->

```bash
npm run dev
# Di terminal berbeda
php artisan serve
```

> **Catatan:** Pastikan Anda telah memasukkan koordinat kantor BPKAD Garut (atau lokasi yang digunakan untuk testing) ke dalam tabel pengaturan database **sebelum** testing Geofencing.

-----

\<h2 id="pembuat"\>🧍 Pembuat\</h2\>

\<p\>Sistem Absensi PKL BPKAD Garut dikembangkan oleh **[Nama/Tim Anda]**.\</p\>