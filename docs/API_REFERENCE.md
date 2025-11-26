# Dokumentasi API & Routes

## Authentication Routes

### Register User

```
POST /register
```

Buat akun baru sebagai peserta PKL.

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "school_name": "SMK Negeri 1 Garut",
    "division": "akuntansi"
}
```

**Division Options:** akuntansi, sekretariat, anggaran, keuangan, perbendaharaan

### Login

```
POST /login
```

**Request Body:**

```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

### Logout

```
POST /logout
```

---

## User Routes (Peserta PKL)

**Middleware:** auth, user

### Dashboard

```
GET /dashboard
```

Lihat dashboard peserta dengan riwayat absensi harian.

### Check-in

```
POST /attendance/check-in
```

**Request Body:**

```json
{
    "latitude": -7.202507,
    "longitude": 107.890626
}
```

Validasi geofencing dilakukan server-side. Foto dikaptur dari browser.

### Check-out

```
POST /attendance/check-out
```

**Request Body:** (sama seperti check-in)

### Profile

```
GET /profile
```

Lihat dan edit profile pribadi.

---

## Admin Routes

**Middleware:** auth, admin

### Dashboard

```
GET /admin/dashboard
```

Lihat statistik dan tabel absensi terkini.

**Response includes:**

-   Total peserta
-   Peserta hadir hari ini
-   Peserta terlambat hari ini
-   Tabel absensi dengan pagination

### Laporan Absensi

```
GET /admin/attendance-report
```

**Query Parameters:**

-   `division` - Filter per bidang (optional)
-   `date` - Filter per tanggal (optional)

### Pengaturan

```
GET /admin/settings
```

#### Update Jam Absensi

```
PUT /admin/settings/deadline
```

**Request Body:**

```json
{
    "check_in_deadline": "09:00"
}
```

#### Update Lokasi Geofencing

```
PUT /admin/settings/location
```

**Request Body:**

```json
{
    "office_latitude": -7.202507,
    "office_longitude": 107.890626,
    "office_radius": 500,
    "office_address": "SMKN 1 Garut"
}
```

---

## Response Format

### Success Response

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {}
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error message",
    "errors": {}
}
```

---

## Status Codes

-   `200 OK` - Request successful
-   `201 Created` - Resource created
-   `400 Bad Request` - Invalid input
-   `401 Unauthorized` - Not authenticated
-   `403 Forbidden` - Insufficient permission
-   `404 Not Found` - Resource not found
-   `422 Unprocessable Entity` - Validation failed
-   `500 Internal Server Error` - Server error

---

## Rate Limiting

Tidak ada rate limiting pada versi saat ini.

Untuk deployment production, pastikan untuk menambahkan rate limiting via middleware.
