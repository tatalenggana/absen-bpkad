@extends('layouts.app')

@section('title', 'Admin Dashboard - Absensi BPKAD Garut')

@section('content')
<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <!-- Total Users Card -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <h3 style="font-size: 14px; margin: 0;">👥 Total Peserta PKL</h3>
        </div>
        <div class="card-body" style="text-align: center; padding: 24px;">
            <p style="font-size: 36px; font-weight: 700; color: var(--primary); margin: 0;">{{ $stats['totalUsers'] ?? 0 }}</p>
            <p style="font-size: 12px; color: #6b7280; margin: 8px 0 0 0;">Peserta Aktif</p>
        </div>
    </div>

    <!-- Today Attendance Card -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 style="font-size: 14px; margin: 0;">✅ Absensi Hari Ini</h3>
        </div>
        <div class="card-body" style="text-align: center; padding: 24px;">
            <p style="font-size: 36px; font-weight: 700; color: var(--success); margin: 0;">{{ $stats['todayAttendances'] ?? 0 }}</p>
            <p style="font-size: 12px; color: #6b7280; margin: 8px 0 0 0;">Sudah Check-In</p>
        </div>
    </div>

    <!-- Today Late Card -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <h3 style="font-size: 14px; margin: 0;">⏰ Terlambat Hari Ini</h3>
        </div>
        <div class="card-body" style="text-align: center; padding: 24px;">
            <p style="font-size: 36px; font-weight: 700; color: var(--warning); margin: 0;">{{ $stats['todayLate'] ?? 0 }}</p>
            <p style="font-size: 12px; color: #6b7280; margin: 8px 0 0 0;">Peserta Terlambat</p>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2 style="font-size: 20px;">🔍 Filter & Laporan</h2>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <!-- Filter by Date -->
            <form method="GET" action="{{ route('admin.filterDate') }}" style="display: flex; gap: 12px;">
                <input type="date" name="date" required class="form-control" style="flex: 1;">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>

            <!-- Filter by Month -->
            <form method="GET" action="{{ route('admin.filterMonth') }}" style="display: flex; gap: 12px;">
                <select name="month" required class="form-control" style="flex: 1;">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
                <select name="year" required class="form-control" style="flex: 1;">
                    @for ($y = now()->year - 2; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="card">
    <div class="card-header">
        <h2 style="font-size: 20px;">📋 Data Absensi</h2>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Nama Peserta</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Tanggal</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Masuk</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Keluar</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #6b7280;">Status</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #6b7280;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.2s;">
                        <td style="padding: 12px; font-weight: 500;">{{ $attendance->user->name }}</td>
                        <td style="padding: 12px;">{{ $attendance->date->format('d M Y') }}</td>
                        <td style="padding: 12px; color: var(--primary); font-weight: 600;">
                            {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}
                        </td>
                        <td style="padding: 12px; color: #6b7280;">
                            {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i:s') : '-' }}
                        </td>
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
                        <td style="padding: 12px; text-align: center;">
                            <button type="button" onclick="openDetailModal('{{ $attendance->user->name }}', '{{ $attendance->date->format('d M Y') }}', '{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}', '{{ $attendance->photo_path }}', '{{ $attendance->location_latitude }}', '{{ $attendance->location_longitude }}')" 
                                style="color: var(--primary); font-weight: 600; border: none; background: none; cursor: pointer; text-decoration: none;">
                                👁️ Lihat Detail →
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #6b7280;">
                            📭 Tidak ada data absensi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
        {{ $attendances->links() }}
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 50; flex-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 20px; font-weight: 700; margin: 0;">📸 Detail Absensi</h2>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">✕</button>
        </div>

        <!-- User & Date Info -->
        <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;">👤 Nama Peserta</p>
                    <p id="detailName" style="font-weight: 600; margin: 0;"></p>
                </div>
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;">📅 Tanggal</p>
                    <p id="detailDate" style="font-weight: 600; margin: 0;"></p>
                </div>
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;">⏱️ Jam Masuk</p>
                    <p id="detailTime" style="font-weight: 600; margin: 0; color: var(--primary);"></p>
                </div>
            </div>
        </div>

        <!-- Photo Section -->
        <div id="photoSection" style="display: none; margin-bottom: 20px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 12px 0;">📸 Foto Selfie:</p>
            <img id="detailPhoto" style="width: 100%; border-radius: 8px; max-height: 350px; object-fit: cover;">
        </div>

        <!-- Location Section -->
        <div id="locationSection" style="display: none; margin-bottom: 20px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 12px 0;">📍 Lokasi Keberadaan:</p>
            <div style="background: #f0fdf4; border: 2px solid var(--success); padding: 12px; border-radius: 8px; font-size: 13px;">
                <div style="margin-bottom: 8px;">
                    <strong style="color: #22c55e;">Latitude:</strong>
                    <span id="detailLat"></span>
                </div>
                <div>
                    <strong style="color: #22c55e;">Longitude:</strong>
                    <span id="detailLng"></span>
                </div>
                <a id="mapLink" href="#" target="_blank" style="display: block; margin-top: 12px; color: var(--primary); font-weight: 600; text-decoration: none;">
                    🗺️ Buka di Google Maps →
                </a>
            </div>
        </div>

        <!-- Close Button -->
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" onclick="closeDetailModal()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<script>
    function openDetailModal(name, date, time, photoPath, lat, lng) {
        document.getElementById('detailName').textContent = name;
        document.getElementById('detailDate').textContent = date;
        document.getElementById('detailTime').textContent = time;
        
        // Photo
        if (photoPath && photoPath.trim()) {
            document.getElementById('photoSection').style.display = 'block';
            document.getElementById('detailPhoto').src = '{{ asset("storage") }}/' + photoPath;
        } else {
            document.getElementById('photoSection').style.display = 'none';
        }
        
        // Location
        if (lat && lat.trim() && lng && lng.trim()) {
            document.getElementById('locationSection').style.display = 'block';
            document.getElementById('detailLat').textContent = lat;
            document.getElementById('detailLng').textContent = lng;
            document.getElementById('mapLink').href = `https://www.google.com/maps?q=${lat},${lng}`;
        } else {
            document.getElementById('locationSection').style.display = 'none';
        }
        
        document.getElementById('detailModal').style.display = 'flex';
        document.getElementById('detailModal').style.alignItems = 'center';
        document.getElementById('detailModal').style.justifyContent = 'center';
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
</script>

@endsection
