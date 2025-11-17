@extends('layouts.app')

@section('title', 'Dashboard - Absensi BPKAD Garut')

@section('content')

<!-- Diary Popup Component -->
@include('components.bolehdihapuskaloudahbaca')

<!-- Header Card -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2>Dashboard Absensi</h2>
        <p>Selamat datang, {{ Auth::user()->name }}! 👋</p>
    </div>
    <div class="card-body">
        @php $profile = Auth::user()->profile ?? null; @endphp
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <p style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Bidang Penempatan</p>
                <p style="font-size: 16px; font-weight: 700; color: var(--primary);">
                    {{ $profile?->getDivisionLabel() ?? 'Belum diatur' }}
                </p>
            </div>
            <div>
                <p style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Sekolah/Universitas</p>
                <p style="font-size: 16px; font-weight: 700;">{{ $profile?->school_name ?? 'Belum diatur' }}</p>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">
        <div style="font-size: 20px;">✅</div>
        <div>
            <strong>Berhasil!</strong>
            <p style="font-size: 14px; margin-top: 4px;">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        <div style="font-size: 20px;">⚠️</div>
        <div>
            <strong>Terjadi Kesalahan!</strong>
            <p style="font-size: 14px; margin-top: 4px;">{{ session('error') }}</p>
        </div>
    </div>
@endif

<!-- Check-In Card -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2 style="font-size: 20px;">📋 Status Absensi Hari Ini</h2>
    </div>
    <div class="card-body">
        @if ($todayAttendance)
            <!-- Already Checked In -->
            <div style="margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="font-size: 32px;">
                        @if ($todayAttendance->status == 'present')
                            ✅
                        @elseif ($todayAttendance->status == 'late')
                            ⏰
                        @else
                            ❌
                        @endif
                    </div>
                    <div>
                        <p style="font-size: 12px; color: #6b7280;">Status Absensi</p>
                        <p style="font-size: 18px; font-weight: 700;">
                            @if ($todayAttendance->status == 'present')
                                ✅ HADIR
                            @elseif ($todayAttendance->status == 'late')
                                ⏰ TERLAMBAT
                            @else
                                ❌ ABSEN
                            @endif
                        </p>
                    </div>
                </div>

                <div style="background: #f3f4f6; padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                    @if ($todayAttendance->check_in_time)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #6b7280; font-size: 14px;">⏱️ Jam Masuk:</span>
                            <strong style="color: var(--primary);">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i:s') }}</strong>
                        </div>
                    @endif

                    @if ($todayAttendance->check_out_time)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280; font-size: 14px;">🚪 Jam Keluar:</span>
                            <strong style="color: var(--primary);">{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i:s') }}</strong>
                        </div>
                    @endif
                </div>

                @if ($todayAttendance->photo_path)
                    <div style="margin-bottom: 12px;">
                        <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">📸 Foto Selfie:</p>
                        <img src="{{ asset('storage/' . $todayAttendance->photo_path) }}" style="width: 100%; max-height: 300px; border-radius: 8px; object-fit: cover;">
                    </div>
                @endif

                @if ($todayAttendance->location_latitude && $todayAttendance->location_longitude)
                    <div style="margin-bottom: 12px;">
                        <p style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">📍 Lokasi:</p>
                        <div style="background: #f0fdf4; border: 1px solid var(--success); padding: 10px; border-radius: 6px; font-size: 13px;">
                            Lat: {{ $todayAttendance->location_latitude }}<br>
                            Lng: {{ $todayAttendance->location_longitude }}
                        </div>
                    </div>
                @endif

                @if (!$todayAttendance->check_out_time)
                    <form method="POST" action="{{ route('attendance.checkOut') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 12px;">
                            🚪 Check Out
                        </button>
                    </form>
                @endif
            </div>
        @else
            <!-- Not Yet Checked In -->
            <div style="text-align: center; padding: 20px 0;">
                <div style="font-size: 48px; margin-bottom: 12px;">📋</div>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 12px;">Anda belum melakukan absensi hari ini</p>
                <p style="font-size: 12px; color: var(--warning); background: #fef3c7; padding: 12px; border-radius: 8px; margin-bottom: 16px; border-left: 3px solid var(--warning);">
                    <strong>⏰ Perhatian:</strong> Absensi hanya bisa dilakukan hingga pukul 08:00. Setelah itu, Anda akan tercatat sebagai TERLAMBAT.
                </p>
                <button type="button" onclick="openCheckInModal()" class="btn btn-primary btn-block">
                    ✅ Mulai Check-In
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Statistics Card -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2 style="font-size: 20px;">📊 Statistik Bulan Ini</h2>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
            <div style="text-align: center; padding: 12px; background: #dcfce7; border-radius: 8px;">
                <p style="font-size: 12px; color: #15803d; margin-bottom: 4px;">Hadir</p>
                <p style="font-size: 28px; font-weight: 700; color: var(--success);">{{ $stats['present'] ?? 0 }}</p>
            </div>
            <div style="text-align: center; padding: 12px; background: #fef3c7; border-radius: 8px;">
                <p style="font-size: 12px; color: #92400e; margin-bottom: 4px;">Terlambat</p>
                <p style="font-size: 28px; font-weight: 700; color: var(--warning);">{{ $stats['late'] ?? 0 }}</p>
            </div>
            <div style="text-align: center; padding: 12px; background: #fee2e2; border-radius: 8px;">
                <p style="font-size: 12px; color: #991b1b; margin-bottom: 4px;">Absen</p>
                <p style="font-size: 28px; font-weight: 700; color: var(--danger);">{{ $stats['absent'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Absensi -->
<div class="card">
    <div class="card-header">
        <h2 style="font-size: 20px;">📅 Riwayat Absensi Bulan Ini</h2>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Tanggal</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Masuk</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Keluar</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #6b7280;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($monthlyAttendance as $attendance)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                        <td style="padding: 12px;">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}</td>
                        <td style="padding: 12px;">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i:s') : '-' }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: 600;
                                @if ($attendance->status == 'present')
                                    background: #dcfce7;
                                    color: #15803d;
                                @elseif ($attendance->status == 'late')
                                    background: #fef3c7;
                                    color: #92400e;
                                @else
                                    background: #fee2e2;
                                    color: #991b1b;
                                @endif
                            ">
                                @if ($attendance->status == 'present')
                                    ✅ Hadir
                                @elseif ($attendance->status == 'late')
                                    ⏰ Terlambat
                                @else
                                    ❌ Absen
                                @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #6b7280;">
                            Belum ada data absensi bulan ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Check-In Modal -->
<div id="checkInModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 50; flex-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 6px;">📸 Check-In PKL/Magang</h2>
        <p style="color: #6b7280; margin-bottom: 20px; font-size: 14px;">Lengkapi formulir di bawah ini untuk melakukan check-in</p>

        <form id="checkInForm" method="POST" action="{{ route('attendance.checkIn') }}" enctype="multipart/form-data" style="space-y: 16px;">
            @csrf

            <!-- Camera Section -->
            <div class="form-group">
                <label class="form-label">
                    <span class="icon">📸</span>
                    Foto Selfie
                    <span class="required">*</span>
                </label>
                <div style="border: 2px dashed var(--primary); border-radius: 8px; padding: 16px; background: #f0f9ff;">
                    <!-- Camera View -->
                    <div id="cameraView" style="display: none;">
                        <video id="videoPreview" style="width: 100%; border-radius: 6px; margin-bottom: 12px;"></video>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <button type="button" id="capturePhotoBtn" onclick="capturePhoto()" class="btn btn-success" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%);">
                                ✓ Ambil Foto
                            </button>
                            <button type="button" id="closeCameraBtn" onclick="closeCamera()" class="btn btn-secondary">
                                ✕ Batal
                            </button>
                        </div>
                    </div>

                    <!-- Photo Preview -->
                    <div id="photoView" style="display: none;">
                        <img id="photoPreview" style="width: 100%; border-radius: 6px; margin-bottom: 12px; max-height: 300px; object-fit: cover;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <button type="button" id="saveFotoBtn" onclick="saveFoto()" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);">
                                ✓ Pakai Foto Ini
                            </button>
                            <button type="button" id="retakeFotoBtn" onclick="startCamera()" class="btn btn-secondary">
                                🔄 Ambil Ulang
                            </button>
                        </div>
                    </div>

                    <!-- Start Button -->
                    <div id="startView">
                        <button type="button" id="startCameraBtn" onclick="startCamera()" class="btn btn-primary btn-block">
                            📷 Ambil Foto
                        </button>
                    </div>

                    <!-- Locked Foto View -->
                    <div id="lockedFotoView" style="display: none;">
                        <img id="lockedPhotoPreview" style="width: 100%; border-radius: 6px; margin-bottom: 12px; max-height: 300px; object-fit: cover;">
                        <div style="text-align: center; padding: 12px; background: #f0fdf4; border: 2px solid var(--success); border-radius: 8px; margin-bottom: 12px;">
                            <p style="margin: 0; color: #22c55e; font-weight: 600;">✅ Foto Sudah Disimpan</p>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #16a34a;">Ambil foto baru untuk mengubah</p>
                        </div>
                        <button type="button" onclick="resetPhotoForRetake()" class="btn btn-secondary btn-block">
                            🔄 Ambil Foto Baru
                        </button>
                    </div>
                </div>
                <small class="form-hint">Format: JPG | Foto harus jelas menampilkan wajah Anda</small>
            </div>

            <input type="hidden" id="photoData" name="photo_data">
            <input type="hidden" id="photoLocked" value="false">

            <!-- Location Section -->
            <div class="form-group">
                <label class="form-label">
                    <span class="icon">📍</span>
                    Lokasi Keberadaan
                    <span class="required">*</span>
                </label>
                <button type="button" onclick="getLocation()" class="btn btn-secondary btn-block">
                    📡 Ambil Lokasi Saya
                </button>
                <small class="form-hint">Pastikan GPS/lokasi di perangkat Anda aktif</small>
                <div id="locationStatus" style="display: none; margin-top: 12px; padding: 12px; background: #f0fdf4; border: 2px solid var(--success); border-radius: 8px; font-size: 13px;"></div>
            </div>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <!-- Notes -->
            <div class="form-group">
                <label class="form-label">
                    <span class="icon">📝</span>
                    Catatan (Opsional)
                </label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Sehat, siap bekerja, kondisi baik"></textarea>
            </div>

            <!-- Loading -->
            <div id="loading" class="loading">
                <div class="spinner"></div>
                <p style="color: var(--primary); font-weight: 600;">Memproses check-in...</p>
            </div>

            <!-- Buttons -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button type="button" onclick="closeCheckInModal()" class="btn btn-secondary">
                    ✕ Batal
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    ✅ Konfirmasi Check-In
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let stream = null;
    let capturedPhotoData = null;
    const OFFICE_LAT = {{ (float) env('OFFICE_LATITUDE', -7.2239) }};
    const OFFICE_LNG = {{ (float) env('OFFICE_LONGITUDE', 107.6597) }};
    const OFFICE_RADIUS = {{ (int) env('OFFICE_RADIUS_METERS', 500) }};

    function openCheckInModal() {
        document.getElementById('checkInModal').style.display = 'flex';
        document.getElementById('checkInModal').style.alignItems = 'center';
        document.getElementById('checkInModal').style.justifyContent = 'center';
    }

    function closeCheckInModal() {
        document.getElementById('checkInModal').style.display = 'none';
        closeCamera();
        resetPhotoUI();
    }

    function resetPhotoUI() {
        capturedPhotoData = null;
        document.getElementById('photoData').value = '';
        document.getElementById('photoLocked').value = 'false';
        document.getElementById('startView').style.display = 'block';
        document.getElementById('cameraView').style.display = 'none';
        document.getElementById('photoView').style.display = 'none';
        document.getElementById('lockedFotoView').style.display = 'none';
    }

    function startCamera() {
        // Jika foto sudah disimpan (locked), jangan bisa ambil foto biasa
        const photoLocked = document.getElementById('photoLocked').value === 'true';
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                .then(s => {
                    stream = s;
                    const video = document.getElementById('videoPreview');
                    video.srcObject = stream;
                    video.play();
                    
                    // Show camera view, hide others
                    document.getElementById('startView').style.display = 'none';
                    document.getElementById('lockedFotoView').style.display = 'none';
                    document.getElementById('cameraView').style.display = 'block';
                    document.getElementById('photoView').style.display = 'none';
                })
                .catch(err => {
                    alert('❌ Tidak bisa akses kamera: ' + err.message);
                });
        } else {
            alert('❌ Browser Anda tidak support akses kamera');
        }
    }

    function closeCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        document.getElementById('startView').style.display = 'block';
        document.getElementById('cameraView').style.display = 'none';
        document.getElementById('photoView').style.display = 'none';
    }

    function capturePhoto() {
        const video = document.getElementById('videoPreview');
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);
        
        // Save photo data
        capturedPhotoData = canvas.toDataURL('image/jpeg', 0.9);
        
        // Show preview
        document.getElementById('photoPreview').src = capturedPhotoData;
        
        // Show photo view, hide camera
        document.getElementById('cameraView').style.display = 'none';
        document.getElementById('photoView').style.display = 'block';
        
        // Stop camera
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    function saveFoto() {
        if (capturedPhotoData) {
            document.getElementById('photoData').value = capturedPhotoData;
            document.getElementById('photoLocked').value = 'true';
            
            // Show locked view
            document.getElementById('lockedPhotoPreview').src = capturedPhotoData;
            document.getElementById('photoView').style.display = 'none';
            document.getElementById('lockedFotoView').style.display = 'block';
            document.getElementById('startView').style.display = 'none';
        }
    }

    function resetPhotoForRetake() {
        capturedPhotoData = null;
        document.getElementById('photoData').value = '';
        document.getElementById('photoLocked').value = 'false';
        document.getElementById('lockedFotoView').style.display = 'none';
        document.getElementById('startView').style.display = 'block';
        startCamera();
    }

    function getLocation() {
        const btn = event.target;
        if (!navigator.geolocation) {
            alert('❌ Browser Anda tidak support geolocation');
            return;
        }

        btn.innerHTML = '⏳ Mengambil lokasi...';
        btn.disabled = true;
        btn.style.opacity = '0.6';

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;
                
                // Calculate distance from office
                const distance = calculateDistance(OFFICE_LAT, OFFICE_LNG, lat, lon);
                const isInRange = distance <= OFFICE_RADIUS;
                
                let statusHTML = `
                    ✅ <strong>Lokasi Berhasil Didapat!</strong><br>
                    Lat: ${lat.toFixed(6)}<br>
                    Lng: ${lon.toFixed(6)}<br>
                    📏 Jarak dari kantor: ${Math.round(distance)}m
                `;
                
                if (!isInRange) {
                    statusHTML += `<br><span style="color: #dc2626; font-weight: 600;">⚠️ PERINGATAN: Lokasi terlalu jauh! (Max: ${OFFICE_RADIUS}m)</span>`;
                }
                
                document.getElementById('locationStatus').style.display = 'block';
                document.getElementById('locationStatus').innerHTML = statusHTML;
                
                btn.innerHTML = '✅ Lokasi Sudah Didapat';
                btn.style.background = isInRange 
                    ? 'linear-gradient(135deg, var(--success) 0%, #059669 100%)' 
                    : 'linear-gradient(135deg, var(--warning) 0%, #d97706 100%)';
                btn.style.opacity = '1';
            },
            function(error) {
                alert('❌ Gagal mendapat lokasi: ' + error.message);
                btn.innerHTML = '📡 Ambil Lokasi Saya';
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        );
    }

    // Haversine formula untuk hitung jarak
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth radius in meters
        const rad = Math.PI / 180;
        const deltaLat = (lat2 - lat1) * rad;
        const deltaLon = (lon2 - lon1) * rad;
        const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                  Math.cos(lat1 * rad) * Math.cos(lat2 * rad) *
                  Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    document.getElementById('checkInForm').addEventListener('submit', function(e) {
        const photoData = document.getElementById('photoData').value;
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if (!photoData) {
            e.preventDefault();
            alert('⚠️ Mohon ambil foto terlebih dahulu!');
            return false;
        }
        
        if (!lat || !lng) {
            e.preventDefault();
            alert('⚠️ Mohon ambil lokasi Anda terlebih dahulu!');
            return false;
        }

        // Check geofencing di client-side (server juga akan validasi)
        const distance = calculateDistance(OFFICE_LAT, OFFICE_LNG, parseFloat(lat), parseFloat(lng));
        if (distance > OFFICE_RADIUS) {
            e.preventDefault();
            alert(`⚠️ Lokasi Anda terlalu jauh dari kantor!\n\nJarak: ${Math.round(distance)}m\nBatas maksimal: ${OFFICE_RADIUS}m`);
            return false;
        }

        document.getElementById('loading').classList.add('active');
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').style.opacity = '0.6';
    });
</script>
@endpush
@endsection
