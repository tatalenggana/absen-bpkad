# Panduan Pengembang

## Struktur Project

```
absen-bpkad/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   └── AttendanceController.php
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
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── auth/           # Halaman login & register
│   │   ├── user/           # Dashboard peserta
│   │   ├── admin/          # Dashboard admin
│   │   └── components/     # Reusable components
│   ├── css/
│   └── js/
├── routes/
│   └── web.php            # Semua routes defined di sini
├── docs/                  # Dokumentasi
└── storage/               # File uploads (foto)
```

## Key Components

### Models

#### User

```php
// app/Models/User.php
- name: string
- email: string
- password: bcrypted
- role: 'admin' | 'user'
- profile: relation to UserProfile
- attendances: relation to Attendance
```

#### UserProfile

```php
// app/Models/UserProfile.php
- school_name: string
- division: enum (akuntansi, sekretariat, anggaran, keuangan, perbendaharaan)
- getDivisionLabel(): string
```

#### Attendance

```php
// app/Models/Attendance.php
- user_id: foreign key
- date: date
- check_in_time: time
- check_out_time: time
- status: enum (present, late, absent)
- photo_path: string (foto proof)
- location_latitude: decimal
- location_longitude: decimal
- notes: text
```

### Controllers

#### AttendanceController

Handles semua logic untuk user dan admin attendance management.

**Key Methods:**

-   `userDashboard()` - Render user dashboard
-   `checkIn(Request $request)` - Handle check-in dengan GPS validation
-   `checkOut(Request $request)` - Handle check-out
-   `adminDashboard()` - Render admin dashboard
-   `attendanceReport()` - Render attendance report dengan filter
-   `showSettings()` - Render settings page
-   `updateDeadline()` - Update jam check-in
-   `updateLocation()` - Update lokasi geofencing

**Helper Methods:**

-   `calculateDistance($lat1, $lon1, $lat2, $lon2)` - Haversine formula

#### AuthController

Handles authentication.

**Key Methods:**

-   `showLogin()` - Render login page
-   `login(Request $request)` - Process login
-   `showRegister()` - Render register page
-   `register(Request $request)` - Process registration
-   `logout(Request $request)` - Process logout

### Views

#### User Views

-   `resources/views/user/dashboard.blade.php` - Main dashboard
-   `resources/views/user/profile.blade.php` - Profile edit

#### Admin Views

-   `resources/views/admin/layout.blade.php` - Master layout
-   `resources/views/admin/dashboard-new.blade.php` - Dashboard
-   `resources/views/admin/attendance-report-new.blade.php` - Laporan
-   `resources/views/admin/settings-new.blade.php` - Pengaturan

#### Auth Views

-   `resources/views/auth/login.blade.php`
-   `resources/views/auth/register.blade.php`

## Database Schema

### users table

```sql
- id (primary key)
- name
- email (unique)
- email_verified_at
- password
- role (admin|user)
- remember_token
- created_at
- updated_at
```

### user_profiles table

```sql
- id (primary key)
- user_id (foreign key to users)
- school_name
- division (enum)
- notes
- created_at
- updated_at
```

### attendances table

```sql
- id (primary key)
- user_id (foreign key to users)
- date
- check_in_time
- check_out_time
- status (present|late|absent)
- photo_path
- location_latitude
- location_longitude
- notes
- created_at
- updated_at
```

## Geofencing Algorithm

Menggunakan **Haversine Formula** untuk menghitung jarak lurus antara dua koordinat GPS.

```php
private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // meters
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return $distance;
}
```

## Styling & Themes

### CSS Architecture

-   Main CSS di `resources/css/app.css`
-   Component-specific CSS di blade templates
-   CSS Variables untuk theming:
    ```css
    --primary: #0066cc
    --success: #00cc66
    --warning: #ff9900
    --danger: #ff3333
    --sidebar-bg: #003d7a
    ```

### Responsive Breakpoints

-   Mobile: < 480px
-   Tablet: 481px - 768px
-   Desktop: > 768px

## Environment Variables

**.env Configuration:**

```
APP_NAME=Absensi BPKAD
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_DATABASE=absensi_bpkad
DB_USERNAME=root
DB_PASSWORD=

OFFICE_LATITUDE=-7.202507
OFFICE_LONGITUDE=107.890626
OFFICE_RADIUS_METERS=500
OFFICE_ADDRESS="SMKN 1 Garut"

CHECK_IN_DEADLINE=09:00
```

## Development Commands

### Database

```bash
# Run migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Seed database
php artisan migrate --seed

# Fresh migration (danger!)
php artisan migrate:refresh --seed
```

### Cache

```bash
# Clear application cache
php artisan cache:clear

# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:clear
```

### Assets

```bash
# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

## Best Practices

1. **Validation** - Selalu validate input di controller
2. **Error Handling** - Log errors untuk debugging
3. **Security** - Use CSRF tokens, hash passwords
4. **Performance** - Optimize database queries dengan eager loading
5. **Testing** - Test critical paths (auth, absensi, geofencing)

## Troubleshooting Development

### Routes not working

```bash
php artisan route:clear
php artisan cache:clear
```

### Database errors

```bash
php artisan migrate:refresh --seed
```

### Asset errors

```bash
npm run dev
# atau
npm run build
```

## Contributing

Untuk berkontribusi:

1. Create feature branch
2. Make changes
3. Test thoroughly
4. Submit pull request

## License

Proprietary - BPKAD Garut
