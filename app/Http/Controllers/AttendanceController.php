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
        'monthlyAttendance' => $monthAttendances,  // ← View pakai ini
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
            return back()->with('error', "❌ Lokasi Anda terlalu jauh dari kantor! Jarak: " . round($distance) . "m (Max: {$radiusMeters}m)");
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
                    return back()->with('error', '❌ Format foto tidak valid!');
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
                return back()->with('error', '❌ Gagal menyimpan foto. Silakan coba lagi.');
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
            return back()->with('error', '❌ Gagal menyimpan absensi. Silakan coba lagi.');
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
    public function adminDashboard()
    {
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->paginate(50);

        // Get statistics
        $totalUsers = \App\Models\User::where('role', 'user')->count();
        $todayAttendances = Attendance::whereDate('date', now()->toDateString())->count();
        $todayLate = Attendance::whereDate('date', now()->toDateString())
            ->where('status', 'late')
            ->count();

        return view('admin.dashboard', [
            'attendances' => $attendances,
            'stats' => [
                'totalUsers' => $totalUsers,
                'todayAttendances' => $todayAttendances,
                'todayLate' => $todayLate,
            ],
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
}
