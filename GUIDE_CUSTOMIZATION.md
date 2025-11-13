# 🎨 Panduan Customization Lengkap

> Panduan step-by-step untuk customize warna, font, dan tampilan sistem absensi BPKAD. Cocok buat yang mau presentasi pakai tema kustom! 😊

---

## 📍 Daftar Isi

1. [Customize Warna](#customize-warna)
2. [Customize Font](#customize-font)
3. [Customize Layout](#customize-layout)
4. [Customize Logo & Branding](#customize-logo--branding)
5. [Preset Tema Siap Pakai](#preset-tema-siap-pakai)

---

## 🎨 Customize Warna

### 📄 File yang Perlu Diubah
- **Lokasi:** `resources/views/layouts/app.blade.php` (baris 10-50)

### 🔍 Menemukan CSS Variables

Buka file `resources/views/layouts/app.blade.php` dan cari bagian ini:

```css
<style>
    :root {
        --primary: #2563eb;
        --success: #16a34a;
        --warning: #ca8a04;
        --danger: #dc2626;
        --info: #0284c7;
        --secondary: #6b7280;
    }
```

### ✏️ Cara Mengubahnya

**Step 1:** Buka file `resources/views/layouts/app.blade.php`

**Step 2:** Cari `<style>` bagian paling atas

**Step 3:** Ubah kode warna (format: `#RRGGBB`)

### 🌈 Contoh Perubahan

#### ❌ JANGAN (warna ini sudah ada):
```css
--primary: #2563eb;  /* Biru standar */
```

#### ✅ LAKUKAN (ubah ke warna baru):
```css
--primary: #8b5cf6;  /* Ubah jadi ungu */
```

### 🎨 Kode Warna Populer

| Nama | Kode | Preview |
|------|------|---------|
| Merah Cerah | `#ef4444` | 🔴 |
| Biru Tua | `#1e3a8a` | 🔵 |
| Ungu | `#8b5cf6` | 🟣 |
| Hijau Mint | `#10b981` | 🟢 |
| Orange | `#f97316` | 🟠 |
| Pink | `#ec4899` | 🩷 |
| Cyan | `#06b6d4` | 🔷 |
| Teal | `#14b8a6` | 🟦 |

### 💡 Mencari Warna Sendiri

Pakai website ini untuk ambil warna:
1. Kunjungi: https://www.colorpicker.com/
2. Pilih warna yang suka
3. Copy kode HEX (format: `#XXXXXX`)
4. Paste ke file `app.blade.php`

---

## ✍️ Customize Font

### 📄 File yang Perlu Diubah
- **Lokasi:** `resources/views/layouts/app.blade.php` (baris 8 - Google Fonts)

### 🔍 Menemukan Font Import

Cari baris seperti ini di bagian `<head>`:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
```

### ✏️ Cara Mengubah Font

**Step 1:** Cari link Google Fonts di atas

**Step 2:** Kunjungi https://fonts.google.com/

**Step 3:** Cari font yang suka (cth: "Roboto", "Open Sans", "Lato")

**Step 4:** Klik font → Klik "Select this style"

**Step 5:** Copy link import yang disediakan

**Step 6:** Ganti link di file `app.blade.php`

### 🔤 Font Yang Bagus Untuk Website

1. **Poppins** (sekarang dipakai) - Modern & Friendly
2. **Roboto** - Clean & Professional
3. **Open Sans** - Readable & Elegant
4. **Lato** - Balanced & Modern
5. **Montserrat** - Bold & Geometric
6. **Inter** - Geometric & Minimalist

### 📏 Ubah Ukuran Font (Font Size)

Cari di CSS dan ubah angka:

```css
/* Ubah Header */
h1 { font-size: 32px; }  /* dari 28px ke 32px */
h2 { font-size: 24px; }  /* dari 20px ke 24px */

/* Ubah Body Text */
body { font-size: 16px; } /* dari 14px ke 16px */

/* Ubah Small Text */
.small { font-size: 12px; } /* dari 10px ke 12px */
```

---

## 📐 Customize Layout

### 🔄 Ubah Container Width (Lebar Konten)

Cari di CSS:

```css
.container {
    max-width: 1200px;  /* Ubah angka ini */
    margin: 0 auto;
    padding: 0 20px;
}
```

**Opsi:**
- `max-width: 900px;` - Lebih narrow
- `max-width: 1000px;` - Standard
- `max-width: 1200px;` - Agak lebar (sekarang)
- `max-width: 1400px;` - Sangat lebar

### 📏 Ubah Spacing/Jarak

Ubah nilai di CSS:

```css
/* Margin (jarak dari luar) */
margin: 16px;     /* Ubah 16 jadi angka lain */

/* Padding (jarak dari dalam) */
padding: 20px;    /* Ubah 20 jadi angka lain */

/* Gap (jarak antar item) */
gap: 12px;        /* Ubah 12 jadi angka lain */
```

**Tips:**
- Semakin besar angka = semakin luas jaraknya
- Sebaiknya pakai kelipatan: 4, 8, 12, 16, 20, 24

### 🔲 Ubah Border Radius (Lengkungan Sudut)

```css
/* Sudut lancip */
border-radius: 0px;

/* Sudut sedikit rounded */
border-radius: 4px;

/* Sudut normal (sekarang) */
border-radius: 8px;

/* Sudut sangat rounded */
border-radius: 16px;

/* Sudut super rounded (bulat) */
border-radius: 50px;
```

---

## 🎯 Customize Logo & Branding

### 📍 Lokasi Logo

**File:** `resources/views/layouts/app.blade.php`

Cari baris:

```html
<img src="{{ asset('image/logonav.png') }}" alt="Logo" style="height: 40px;">
```

### ✏️ Cara Ganti Logo

**Step 1:** Siapkan file logo dengan format:
- `.png` (transparan) - TERBAIK
- `.jpg` atau `.jpeg`
- `.svg` - Scalable & crisp

**Step 2:** Simpan file di: `public/image/` (ganti nama jadi `logonav.png` atau nama lain)

**Step 3:** Edit file `app.blade.php`, ubah:

```html
<!-- DARI: -->
<img src="{{ asset('image/logonav.png') }}" alt="Logo" style="height: 40px;">

<!-- MENJADI: -->
<img src="{{ asset('image/logo-baru.png') }}" alt="Logo" style="height: 40px;">
```

### 🖼️ Ukuran Logo Optimal

- **Width:** 150-200px
- **Height:** 40-50px
- **Format:** PNG (transparan) - Paling bagus
- **Size:** Dibawah 100KB

### 📝 Ubah Nama Aplikasi

Cari baris yang ada teks nama aplikasi:

```html
<span style="font-size: 20px; font-weight: 700; color: white; margin-left: 12px;">
    Absensi BPKAD
</span>
```

Ubah "Absensi BPKAD" jadi nama yang suka, contoh:
```html
Sistem Absensi SMAN 1 Jakarta
```

### 🎭 Ubah Warna Header/Navbar

Cari di CSS:

```css
nav {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}
```

Ubah `#2563eb` dan `#1e40af` ke warna yang diinginkan.

---

## 🎨 Preset Tema Siap Pakai

Dibawah ini ada beberapa preset tema yang bisa langsung dipakai. Tinggal copy kode variabelnya ke file `app.blade.php`!

### 🔵 Tema: Professional Blue (Default)

```css
:root {
    --primary: #2563eb;
    --success: #16a34a;
    --warning: #ca8a04;
    --danger: #dc2626;
    --info: #0284c7;
    --secondary: #6b7280;
}
```

### 🎭 Tema: Dark Professional

```css
:root {
    --primary: #1e3a8a;      /* Biru gelap */
    --success: #15803d;      /* Hijau gelap */
    --warning: #b45309;      /* Kuning gelap */
    --danger: #b91c1c;       /* Merah gelap */
    --info: #0369a1;         /* Info gelap */
    --secondary: #4b5563;    /* Gray gelap */
}
```

### 🌟 Tema: Modern Purple

```css
:root {
    --primary: #8b5cf6;      /* Ungu */
    --success: #10b981;      /* Hijau terang */
    --warning: #f59e0b;      /* Amber */
    --danger: #ef4444;       /* Merah terang */
    --info: #06b6d4;         /* Cyan */
    --secondary: #9ca3af;    /* Gray terang */
}
```

### 🌊 Tema: Ocean Vibes

```css
:root {
    --primary: #06b6d4;      /* Cyan/Turquoise */
    --success: #06d6a0;      /* Turquoise hijau */
    --warning: #fbbf24;      /* Amber terang */
    --danger: #fb7185;       /* Pink-red */
    --info: #38bdf8;         /* Sky blue */
    --secondary: #78716c;    /* Warm gray */
}
```

### 🌿 Tema: Nature Green

```css
:root {
    --primary: #059669;      /* Green */
    --success: #10b981;      /* Emerald */
    --warning: #d97706;      /* Orange */
    --danger: #dc2626;       /* Red */
    --info: #0891b2;         /* Teal */
    --secondary: #7c3aed;    /* Indigo */
}
```

### 🎀 Tema: Soft Pastel

```css
:root {
    --primary: #a78bfa;      /* Ungu pastel */
    --success: #86efac;      /* Hijau pastel */
    --warning: #fcd34d;      /* Kuning pastel */
    --danger: #fca5a5;       /* Merah pastel */
    --info: #93c5fd;         /* Biru pastel */
    --secondary: #d1d5db;    /* Gray pastel */
}
```

### ⚡ Tema: Energy Orange

```css
:root {
    --primary: #f97316;      /* Orange */
    --success: #22c55e;      /* Lime green */
    --warning: #fbbf24;      /* Amber */
    --danger: #ef4444;       /* Red */
    --info: #3b82f6;         /* Blue */
    --secondary: #94a3b8;    /* Slate */
}
```

---

## 🧪 Testing Setelah Customize

Setelah mengubah warna/font/layout, jangan lupa:

1. **Clear Cache Browser:**
   - Tekan `Ctrl + F5` (Windows)
   - atau `Cmd + Shift + R` (Mac)

2. **Clear Laravel Cache:**
   ```bash
   php artisan config:cache
   ```

3. **Test di Multiple Pages:**
   - Homepage
   - Login page
   - Dashboard
   - Admin page
   - Mobile browser

4. **Check Responsiveness:**
   - Buka di mobile
   - Tablet
   - Desktop
   - Cek apakah layout tetap bagus

---

## 📸 Quick Reference: Dimana Apa

| Yang Mau Diubah | File | Lokasi |
|-----------------|------|--------|
| Warna utama | `app.blade.php` | CSS `:root` |
| Font | `app.blade.php` | `<head>` section |
| Font size | `app.blade.php` | CSS rules |
| Logo | `public/image/` | Upload file baru |
| Nama app | `app.blade.php` | Navbar text |
| Warna header | `app.blade.php` | CSS `nav` |
| Spacing | `app.blade.php` | CSS `margin/padding` |
| Border radius | `app.blade.php` | CSS `border-radius` |

---

## ⚠️ Tips & Gotchas

### ✅ Yang Boleh Diubah
- CSS variables di `:root {}`
- Font import di `<head>`
- Logo file
- Text content

### ❌ Yang JANGAN Diubah (Bisa Pecah!)
- HTML structure
- PHP code
- Class names
- Variable names
- Database queries

### 🐛 Jika Sesuatu Error

1. **Refresh browser:** Ctrl + F5
2. **Clear cache:** `php artisan config:cache`
3. **Check syntax:** Pastikan CSS valid
4. **Restart server:** Stop & jalankan `php artisan serve` lagi
5. **Revert perubahan:** Kembali ke nilai sebelumnya

---

## 🎯 Contoh Kasus: Ubah Tema Untuk Presentasi

### 📋 Skenario
Presentasi hari Jumat, mau pakai tema ungu + font besar

### 🔧 Solusi

**Step 1:** Edit `resources/views/layouts/app.blade.php`

**Step 2:** Ubah CSS variables:
```css
:root {
    --primary: #8b5cf6;      /* Ganti ke ungu */
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    --secondary: #9ca3af;
}
```

**Step 3:** Ubah font size untuk lebih besar:
```css
body { font-size: 18px; }    /* dari 16px */
h1 { font-size: 36px; }     /* dari 32px */
h2 { font-size: 28px; }     /* dari 24px */
```

**Step 4:** Simpan & refresh browser

**Step 5:** Test di projector sebelum presentasi!

---

## 📚 Referensi Tambahan

- **Color Picker:** https://www.colorpicker.com/
- **Google Fonts:** https://fonts.google.com/
- **Hex Color Codes:** https://htmlcolorcodes.com/
- **CSS Reference:** https://www.w3schools.com/css/

---

## ❓ FAQ Customization

**Q: Berapa banyak warna yang bisa diubah?**
A: 6 warna utama (primary, success, warning, danger, info, secondary). Kalau perlu lebih, tambah variabel baru di `:root {}`.

**Q: Warna sudah diubah tapi tidak berubah juga?**
A: Kemungkinan:
1. Browser belum refresh (tekan Ctrl+F5)
2. Ada hardcoded color di tempat lain
3. Syntax CSS salah (cek bracket & semicolon)

**Q: Font sering error/tidak load?**
A: Pastikan:
1. Link Google Fonts benar
2. Internet connection OK
3. Font name di CSS sesuai nama di Google Fonts

**Q: Bisa undo/revert ke tema default?**
A: Ya, tinggal copy kode tema "Professional Blue" dari section Preset Tema.

**Q: Mau pakai 2 warna sekaligus di satu elemen?**
A: Pakai gradient: `background: linear-gradient(135deg, #warna1 0%, #warna2 100%);`

---

## 🎉 Kesimpulan

Dengan panduan ini, semua orang bisa customize sistem absensi tanpa perlu buka code editor yang rumit! Tinggal copy-paste beberapa baris kode, dan presentasi siap dengan tema kustom! 🚀

Selamat mencoba! 😊

---

*Panduan ini dibuat dengan ❤️ agar customization jadi mudah dan menyenangkan!*
