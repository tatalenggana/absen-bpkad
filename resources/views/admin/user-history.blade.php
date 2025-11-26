@extends('layouts.app')

@section('title', 'Riwayat Absensi - Admin Dashboard')

@section('content')
@include('components.admin-sidebar')

<!-- Main Content -->
<div style="margin-left: 250px; padding: 24px;">
    <!-- Back Button & User Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 700; margin: 0 0 8px 0;"><i class="fas fa-user"></i> {{ $user->name }}</h1>
            <p style="color: #6b7280; margin: 0;">{{ $user->email }}</p>
            @if($user->profile)
                <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0 0;">
                    <i class="fas fa-map-marker-alt"></i> {{ $user->profile->getDivisionLabel() }} | <i class="fas fa-graduation-cap"></i> {{ $user->profile->school_name }}
                </p>
            @endif
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 style="font-size: 14px; margin: 0;"><i class="fas fa-check-circle"></i> Total Hadir</h3>
        </div>
        <div class="card-body" style="text-align: center; padding: 24px;">
            <p style="font-size: 36px; font-weight: 700; color: var(--success); margin: 0;">
                {{ $attendances->where('status', 'present')->count() }}
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <h3 style="font-size: 14px; margin: 0;"><i class="fas fa-clock"></i> Total Terlambat</h3>
        </div>
        <div class="card-body" style="text-align: center; padding: 24px;">
            <p style="font-size: 36px; font-weight: 700; color: var(--warning); margin: 0;">
                {{ $attendances->where('status', 'late')->count() }}
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
            <h3 style="font-size: 14px; margin: 0;"><i class="fas fa-times-circle"></i> Total Absen</h3>
        </div>
        <div class="card-body" style="text-align: center; padding: 24px;">
            <p style="font-size: 36px; font-weight: 700; color: var(--danger); margin: 0;">
                {{ $attendances->where('status', 'absent')->count() }}
            </p>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="card">
    <div class="card-header">
        <h2 style="font-size: 20px;"><i class="fas fa-list"></i> Riwayat Absensi Lengkap</h2>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; width: 50px;">No</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Tanggal</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Masuk</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Keluar</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #6b7280;">Status</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Catatan</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #6b7280;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $index => $attendance)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; font-weight: 600; color: #9ca3af;">{{ $index + 1 }}</td>
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
                                    <i class="fas fa-check-circle"></i> Hadir
                                @elseif ($attendance->status == 'late')
                                    <i class="fas fa-clock"></i> Terlambat
                                @else
                                    <i class="fas fa-times-circle"></i> Absen
                                @endif
                            </span>
                        </td>
                        <td style="padding: 12px; font-size: 13px;">{{ $attendance->notes ?? '-' }}</td>
                        <td style="padding: 12px; text-align: center;">
                            @if($attendance->photo_path || ($attendance->location_latitude && $attendance->location_longitude))
                                <button type="button" onclick="openDetailModal({{ $attendance->id }}, '{{ $attendance->user->name }}', '{{ $attendance->date->format('d M Y') }}', '{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}', '{{ $attendance->photo_path }}', '{{ $attendance->location_latitude }}', '{{ $attendance->location_longitude }}')"
                                    style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </button>
                            @else
                                <span style="color: #d1d5db; font-size: 12px;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox"></i> Belum ada data absensi
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
            <h2 style="font-size: 20px; font-weight: 700; margin: 0;"><i class="fas fa-image"></i> Detail Absensi</h2>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;"><i class="fas fa-times"></i></button>
        </div>

        <!-- User & Date Info -->
        <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;"><i class="fas fa-user"></i> Nama Peserta</p>
                    <p id="detailName" style="font-weight: 600; margin: 0;"></p>
                </div>
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;"><i class="fas fa-calendar"></i> Tanggal</p>
                    <p id="detailDate" style="font-weight: 600; margin: 0;"></p>
                </div>
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;"><i class="fas fa-stopwatch"></i> Jam Masuk</p>
                    <p id="detailTime" style="font-weight: 600; margin: 0; color: var(--primary);"></p>
                </div>
            </div>
        </div>

        <!-- Photo Section -->
        <div id="photoSection" style="display: none; margin-bottom: 20px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 12px 0;"><i class="fas fa-camera"></i> Foto Selfie:</p>
            <img id="detailPhoto" style="width: 100%; border-radius: 8px; max-height: 350px; object-fit: cover;">
        </div>

        <!-- Location Section -->
        <div id="locationSection" style="display: none; margin-bottom: 20px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 12px 0;"><i class="fas fa-map-marker-alt"></i> Lokasi Keberadaan:</p>
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
                    <i class="fas fa-map"></i> Buka di Google Maps <i class="fas fa-external-link-alt"></i>
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
    function openDetailModal(id, name, date, time, photoPath, lat, lng) {
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

</div>
@endsection
