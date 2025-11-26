<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Deadline untuk check-in (jam 8 pagi)
     */
    const CHECK_IN_DEADLINE = '08:00:00';

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in meters
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters
        
        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);
        
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);
        
        $c = 2 * asin(sqrt($a));
        
        return $earthRadius * $c;
    }

    /**
     * User Dashboard - Attendance
     */
    public function userDashboard()
{
    $user = Auth::user();
    
    // Check apakah user profile sudah dibuat
    if (!$user->profile) {
        \Log::warning("User {$user->id} ({$user->email}) tidak memiliki profile");
        
        // SOLUSI 1: Tampilkan view error khusus (RECOMMENDED)
        return view('errors.incomplete-profile', [
            'message' => 'Profil Anda belum lengkap. Silakan hubungi admin untuk melengkapi profil.'
        ]);
    }
    
    $today = now()->toDateString();

    // Get today's attendance
    $todayAttendance = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->first();

    // Get this month's attendance summary
    $monthAttendances = Attendance::where('user_id', $user->id)
        ->whereMonth('date', now()->month)
        ->whereYear('date', now()->year)
        ->orderBy('date', 'desc')
        ->get();

    $stats = [
        'present' => $monthAttendances->where('status', 'present')->count(),
        'late' => $monthAttendances->where('status', 'late')->count(),
        'absent' => $monthAttendances->where('status', 'absent')->count(),
    ];

    return view('user.dashboard', [
        'todayAttendance' => $todayAttendance,
        'stats' => $stats,
        'monthlyAttendance' => $monthAttendances,  // View pakai ini
    ]);
}

    /**
     * Check-in absensi
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Check apakah sudah absen hari ini
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Anda sudah absen hari ini!');
        }

        // Validasi input
        $validated = $request->validate([
            'photo_data' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check geofencing - apakah user dalam radius kantor
        $officeLatitude = (float) env('OFFICE_LATITUDE', -7.2239);
        $officeLongitude = (float) env('OFFICE_LONGITUDE', 107.6597);
        $radiusMeters = (int) env('OFFICE_RADIUS_METERS', 500);
        
        $userLatitude = (float) $validated['latitude'];
        $userLongitude = (float) $validated['longitude'];
        
        $distance = $this->calculateDistance($officeLatitude, $officeLongitude, $userLatitude, $userLongitude);
        
        if ($distance > $radiusMeters) {
            return back()->with('error', "[ERROR] Lokasi Anda terlalu jauh dari kantor! Jarak: " . round($distance) . "m (Max: {$radiusMeters}m)");
        }

        // Check apakah masih dalam jam absensi (sebelum jam 8 pagi)
        $checkInTime = now('Asia/Jakarta')->toTimeString();
        $deadline = self::CHECK_IN_DEADLINE;
        $isLate = $checkInTime > $deadline;

        // Simpan foto jika ada
        $photoPath = null;
        if (!empty($validated['photo_data'])) {
            try {
                $base64 = $validated['photo_data'];
                
                // Handle different base64 formats
                if (strpos($base64, 'data:image') === 0) {
                    $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
                }
                
                $photoData = base64_decode($base64, true);
                
                if ($photoData === false) {
                    return back()->with('error', '[ERROR] Format foto tidak valid!');
                }
                
                // Create directory if not exists
                $storagePath = storage_path('app/public/attendances');
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }
                
                $photoName = 'attendance_' . $user->id . '_' . now()->timestamp . '.jpg';
                $photoPath = 'attendances/' . $photoName;
                
                file_put_contents(storage_path('app/public/' . $photoPath), $photoData);
            } catch (\Exception $e) {
                \Log::error('Foto simpan error: ' . $e->getMessage());
                return back()->with('error', '[ERROR] Gagal menyimpan foto. Silakan coba lagi.');
            }
        }

        try {
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in_time' => $checkInTime,
                'status' => $isLate ? 'late' : 'present',
                'photo_path' => $photoPath,
                'location_latitude' => $validated['latitude'] ?? null,
                'location_longitude' => $validated['longitude'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $message = $isLate
                ? 'Absensi tercatat sebagai TERLAMBAT! Jam masuk: ' . Carbon::parse($checkInTime)->format('H:i:s')
                : 'Absensi tercatat sebagai HADIR! Jam masuk: ' . Carbon::parse($checkInTime)->format('H:i:s');

            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Attendance create error: ' . $e->getMessage());
            return back()->with('error', '[ERROR] Gagal menyimpan absensi. Silakan coba lagi.');
        }
    }

    /**
     * Check-out absensi
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan check-in!');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan check-out!');
        }

        $attendance->update([
            'check_out_time' => now('Asia/Jakarta')->toTimeString(),
        ]);

        return back()->with('success', 'Check-out berhasil! Jam keluar: ' . now('Asia/Jakarta')->format('H:i:s'));
    }

    /**
     * Admin Dashboard - View all attendances
     */
    public function adminDashboard(Request $request)
    {
        $division = $request->get('division', '');
        
        // Get all users query
        $usersQuery = \App\Models\User::where('role', 'user');
        
        // Get today's attendances query
        $todayQuery = Attendance::whereDate('date', now()->toDateString());
        
        // Get today's late attendances query
        $lateQuery = Attendance::whereDate('date', now()->toDateString())->where('status', 'late');
        
        // Apply division filter if selected
        if ($division) {
            $usersQuery->whereHas('profile', function ($q) use ($division) {
                $q->where('division', $division);
            });
            
            $todayQuery->whereHas('user.profile', function ($q) use ($division) {
                $q->where('division', $division);
            });
            
            $lateQuery->whereHas('user.profile', function ($q) use ($division) {
                $q->where('division', $division);
            });
        }
        
        $stats = [
            'totalUsers' => $usersQuery->count(),
            'todayAttendances' => $todayQuery->count(),
            'todayLate' => $lateQuery->count(),
        ];
        
        // Get all attendances with user data for table
        $attendancesQuery = Attendance::with('user.profile')
            ->orderBy('date', 'desc')
            ->orderBy('check_in_time', 'desc');
        
        if ($division) {
            $attendancesQuery->whereHas('user.profile', function ($q) use ($division) {
                $q->where('division', $division);
            });
        }
        
        $attendances = $attendancesQuery->paginate(15);
        
        return view('admin.dashboard-new', [
            'stats' => $stats,
            'attendances' => $attendances,
            'selectedDivision' => $division,
        ]);
    }

    /**
     * Admin - Filter by date
     */
    public function filterByDate(Request $request)
    {
        $date = $request->validate([
            'date' => 'required|date',
        ])['date'];

        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->orderBy('check_in_time', 'asc')
            ->get();

        return view('admin.attendance-report', [
            'attendances' => $attendances,
            'selectedDate' => $date,
        ]);
    }

    /**
     * Admin - Filter by month
     */
    public function filterByMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $attendances = Attendance::with('user')
            ->whereMonth('date', $validated['month'])
            ->whereYear('date', $validated['year'])
            ->orderBy('date', 'desc')
            ->paginate(100);

        return view('admin.attendance-report', [
            'attendances' => $attendances,
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);
    }

    /**
     * Admin - User attendance history
     */
    public function userHistory($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $attendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->paginate(30);

        return view('admin.user-history', [
            'user' => $user,
            'attendances' => $attendances,
        ]);
    }

    /**
     * User Profile Page
     */
    public function showProfile()
    {
        $user = Auth::user();
        
        // Get attendance statistics
        $stats = [
            'total' => Attendance::where('user_id', $user->id)->count(),
            'present' => Attendance::where('user_id', $user->id)->where('status', 'present')->count(),
            'late' => Attendance::where('user_id', $user->id)->where('status', 'late')->count(),
            'absent' => Attendance::where('user_id', $user->id)->where('status', 'absent')->count(),
        ];

        return view('user.profile', ['stats' => $stats]);
    }

    /**
     * Settings Page
     */
    public function showSettings()
    {
        return view('admin.settings-new', [
            'deadline' => env('CHECK_IN_DEADLINE', '09:00'),
            'latitude' => env('OFFICE_LATITUDE', '-7.202507'),
            'longitude' => env('OFFICE_LONGITUDE', '107.890626'),
            'radius' => env('OFFICE_RADIUS_METERS', '500'),
            'address' => env('OFFICE_ADDRESS', 'SMKN 1 Garut'),
        ]);
    }

    /**
     * Attendance Report Page
     */
    public function attendanceReport(Request $request)
    {
        $division = $request->get('division', '');
        $date = $request->get('date', '');
        
        $query = Attendance::with('user.profile')
            ->orderBy('date', 'desc');
        
        if ($division) {
            $query->whereHas('user.profile', function ($q) use ($division) {
                $q->where('division', $division);
            });
        }
        
        if ($date) {
            $query->whereDate('date', $date);
        }
        
        $attendances = $query->paginate(20);

        return view('admin.attendance-report-new', [
            'attendances' => $attendances,
        ]);
    }
    /**
     * Update Deadline
     */
    public function updateDeadline(Request $request)
    {
        $validated = $request->validate([
            'check_in_deadline' => 'required|date_format:H:i',
        ]);
        
        try {
            // Update .env file
            $envPath = base_path('.env');
            $envContent = file_get_contents($envPath);
            
            // Replace or add CHECK_IN_DEADLINE
            if (preg_match('/^CHECK_IN_DEADLINE=/m', $envContent)) {
                $envContent = preg_replace(
                    '/^CHECK_IN_DEADLINE=.*/m',
                    'CHECK_IN_DEADLINE=' . $validated['check_in_deadline'],
                    $envContent
                );
            } else {
                $envContent .= "\nCHECK_IN_DEADLINE=" . $validated['check_in_deadline'];
            }
            
            file_put_contents($envPath, $envContent);
            
            // Clear config cache
            \Artisan::call('config:clear');
            
            return back()->with('success_deadline', 'Jam absensi berhasil diperbarui menjadi ' . $validated['check_in_deadline']);
        } catch (\Exception $e) {
            \Log::error('Update deadline error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupdate jam absensi: ' . $e->getMessage());
        }
    }

    /**
     * Update Location
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'office_latitude' => 'required|numeric|between:-90,90',
            'office_longitude' => 'required|numeric|between:-180,180',
            'office_radius' => 'required|numeric|min:1',
            'office_address' => 'required|string|max:255',
        ]);
        
        try {
            $envPath = base_path('.env');
            $envContent = file_get_contents($envPath);
            
            // Update each env variable
            $replacements = [
                'OFFICE_LATITUDE' => $validated['office_latitude'],
                'OFFICE_LONGITUDE' => $validated['office_longitude'],
                'OFFICE_RADIUS_METERS' => $validated['office_radius'],
                'OFFICE_ADDRESS' => '"' . $validated['office_address'] . '"',
            ];
            
            foreach ($replacements as $key => $value) {
                if (preg_match('/^' . $key . '=/m', $envContent)) {
                    $envContent = preg_replace(
                        '/^' . $key . '=.*/m',
                        $key . '=' . $value,
                        $envContent
                    );
                } else {
                    $envContent .= "\n" . $key . "=" . $value;
                }
            }
            
            file_put_contents($envPath, $envContent);
            
            // Clear config cache
            \Artisan::call('config:clear');
            
            return back()->with('success_location', 'Lokasi absensi berhasil diperbarui! Alamat: ' . $validated['office_address']);
        } catch (\Exception $e) {
            \Log::error('Update location error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengupdate lokasi: ' . $e->getMessage());
        }
    }
}
