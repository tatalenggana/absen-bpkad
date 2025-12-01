@extends('admin.layout')

@section('content')
<!-- Header -->
<div class="top-header">
    <div>
        <h1>Laporan Absensi</h1>
        <p>Data lengkap absensi semua peserta PKL</p>
    </div>
</div>

<!-- Content -->
<div style="padding: 25px; padding-top: 0;">
    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.attendance-report') }}" style="display: flex; gap: 12px; align-items: center; width: 100%; flex-wrap: wrap;">
            <select name="division" class="form-control" style="min-width: 200px;">
                <option value="">📊 Semua Bidang</option>
                <option value="akuntansi" {{ request('division') == 'akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                <option value="sekretariat" {{ request('division') == 'sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                <option value="anggaran" {{ request('division') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                <option value="keuangan" {{ request('division') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                <option value="perbendaharaan" {{ request('division') == 'perbendaharaan' ? 'selected' : '' }}>Perbendaharaan</option>
            </select>

            <input type="date" name="date" class="form-control" style="max-width: 150px;" value="{{ request('date') }}">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.attendance-report') }}" class="btn btn-secondary">
                <i class="fas fa-sync"></i> Reset
            </a>
        </form>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        @if($attendances->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Bidang</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $index => $attendance)
                        <tr>
                            <td>{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $attendance->user->name }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">{{ $attendance->user->email }}</div>
                            </td>
                            <td>
                                @if($attendance->user->profile)
                                    <span class="table-badge badge-success">
                                        {{ $attendance->user->profile->getDivisionLabel() }}
                                    </span>
                                @else
                                    <span style="color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td style="color: var(--primary); font-weight: 600;">
                                {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                            </td>
                            <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</td>
                            <td>
                                @if ($attendance->status == 'present')
                                    <span class="table-badge badge-success">✓ Hadir</span>
                                @elseif ($attendance->status == 'late')
                                    <span class="table-badge badge-warning">⏰ Terlambat</span>
                                @else
                                    <span class="table-badge badge-danger">✕ Absen</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->photo_path || ($attendance->location_latitude && $attendance->location_longitude))
                                    <button type="button" class="btn btn-primary" onclick="openDetailModal('{{ $attendance->user->name }}', '{{ $attendance->date->format('d M Y') }}', '{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}', '{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i:s') : '-' }}', '{{ $attendance->status }}', '{{ $attendance->photo_path }}', '{{ $attendance->location_latitude }}', '{{ $attendance->location_longitude }}')">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </button>
                                @else
                                    <span style="color: var(--text-secondary); font-size: 12px;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                Tidak ada data absensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($attendances->hasPages())
                <div style="display: flex; justify-content: center; padding: 20px;">
                    {{ $attendances->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Tidak ada data absensi</h3>
                <p>Silakan coba filter yang berbeda atau kembali lagi nanti</p>
            </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 50; align-items: center; justify-content: center;">
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
                    <p id="detailCheckIn" style="font-weight: 600; margin: 0; color: var(--primary);"></p>
                </div>
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;"><i class="fas fa-sign-out-alt"></i> Jam Keluar</p>
                    <p id="detailCheckOut" style="font-weight: 600; margin: 0;"></p>
                </div>
                <div>
                    <p style="color: #6b7280; margin: 0 0 4px 0; font-size: 12px;"><i class="fas fa-flag"></i> Status</p>
                    <p id="detailStatus" style="font-weight: 600; margin: 0;"></p>
                </div>
            </div>
        </div>

        <!-- Photo Section -->
        <div id="photoSection" style="display: none; margin-bottom: 20px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 12px 0;"><i class="fas fa-camera"></i> Foto Selfie:</p>
            <img id="detailPhoto" style="width: 100%; border-radius: 8px; max-height: 350px; object-fit: cover; cursor: pointer;" onclick="openPhotoFullscreen(this.src)">
        </div>

        <!-- Location Section -->
        <div id="locationSection" style="display: none; margin-bottom: 20px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 12px 0;"><i class="fas fa-map-marker-alt"></i> Lokasi Keberadaan:</p>
            <div style="background: #f0fdf4; border: 2px solid var(--success); padding: 12px; border-radius: 8px; font-size: 13px;">
                <div style="margin-bottom: 8px;">
                    <strong style="color: #22c55e;">Latitude:</strong>
                    <span id="detailLat"></span>
                </div>
                <div style="margin-bottom: 8px;">
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
    function openDetailModal(name, date, checkIn, checkOut, status, photoPath, lat, lng) {
        document.getElementById('detailName').textContent = name;
        document.getElementById('detailDate').textContent = date;
        document.getElementById('detailCheckIn').textContent = checkIn;
        document.getElementById('detailCheckOut').textContent = checkOut;
        
        // Status badge
        let statusBadge = '';
        if (status === 'present') {
            statusBadge = '<span style="background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-check-circle"></i> Hadir</span>';
        } else if (status === 'late') {
            statusBadge = '<span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-clock"></i> Terlambat</span>';
        } else {
            statusBadge = '<span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-times-circle"></i> Absen</span>';
        }
        document.getElementById('detailStatus').innerHTML = statusBadge;
        
        // Photo
        if (photoPath && photoPath.trim()) {
            document.getElementById('photoSection').style.display = 'block';
            document.getElementById('detailPhoto').src = '/storage/' + photoPath;
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
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    
    function openPhotoFullscreen(photoSrc) {
        const modal = document.createElement('div');
        modal.style.cssText = 'position: fixed; inset: 0; background: rgba(0, 0, 0, 0.9); z-index: 100; display: flex; align-items: center; justify-content: center;';
        modal.innerHTML = `
            <div style="position: relative; width: 90%; height: 90%; display: flex; align-items: center; justify-content: center;">
                <img src="${photoSrc}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                <button type="button" onclick="this.parentElement.parentElement.remove()" style="position: absolute; top: 20px; right: 20px; background: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 24px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
                <a href="${photoSrc}" download style="position: absolute; bottom: 20px; right: 20px; background: var(--primary); color: white; padding: 10px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        `;
        document.body.appendChild(modal);
        modal.onclick = function(e) {
            if (e.target === this) this.remove();
        }
    }</script>

@endsection
