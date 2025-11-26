@extends('layouts.app')

@section('title', 'Admin Dashboard - Absensi BPKAD Garut')

@section('content')
@include('components.admin-sidebar')

<!-- Main Content -->
<div style="margin-left: 250px; padding: 24px; background: #f9fafb; min-height: 100vh;">
    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 700; margin: 0 0 8px 0; color: #111827;"><i class="fas fa-database"></i> Dashboard Absensi</h1>
        <p style="color: #6b7280; margin: 0; font-size: 14px;">Kelola dan pantau data absensi peserta PKL/Magang</p>
    </div>

    <!-- Summary Stats Bar -->
    <div style="display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
        <!-- Total Users Stat -->
        <div style="background: white; border-radius: 8px; padding: 16px 24px; border-left: 4px solid #3b82f6; flex: 1; min-width: 150px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;"><i class="fas fa-users"></i> Total Peserta</p>
            <p style="font-size: 24px; font-weight: 700; color: #111827; margin: 0;">{{ $stats['totalUsers'] ?? 0 }}</p>
        </div>

        <!-- Today Attendance Stat -->
        <div style="background: white; border-radius: 8px; padding: 16px 24px; border-left: 4px solid #10b981; flex: 1; min-width: 150px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;"><i class="fas fa-check-circle"></i> Hadir Hari Ini</p>
            <p style="font-size: 24px; font-weight: 700; color: #111827; margin: 0;">{{ $stats['todayAttendances'] ?? 0 }}</p>
        </div>

        <!-- Today Late Stat -->
        <div style="background: white; border-radius: 8px; padding: 16px 24px; border-left: 4px solid #f59e0b; flex: 1; min-width: 150px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;"><i class="fas fa-clock"></i> Terlambat</p>
            <p style="font-size: 24px; font-weight: 700; color: #111827; margin: 0;">{{ $stats['todayLate'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Filter & Data Section -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
        <!-- Filter Bar -->
        <div style="background: #f9fafb; padding: 16px 24px; border-bottom: 1px solid #e5e7eb;">
            <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 200px;">
                    <select name="division" class="form-control" style="padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                        <option value="">📊 Semua Bidang</option>
                        <option value="akuntansi" {{ request('division') == 'akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                        <option value="sekretariat" {{ request('division') == 'sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                        <option value="anggaran" {{ request('division') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                        <option value="keuangan" {{ request('division') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                        <option value="perbendaharaan" {{ request('division') == 'perbendaharaan' ? 'selected' : '' }}>Perbendaharaan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 10px 16px; font-size: 12px;">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="padding: 10px 16px; font-size: 12px;">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>

        <!-- Data Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; width: 50px;">No</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151;">Nama Peserta</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151;">Bidang</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151;">Tanggal</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151;">Jam Masuk</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151;">Jam Keluar</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151;">Status</th>
                        <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $index => $attendance)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                            <td style="padding: 12px 16px; color: #6b7280; text-align: center; width: 50px;">{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</td>
                            <td style="padding: 12px 16px;">
                                <div style="font-weight: 600; color: #111827;">{{ $attendance->user->name }}</div>
                                <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">{{ $attendance->user->email }}</div>
                            </td>
                            <td style="padding: 12px 16px;">
                                @if($attendance->user->profile)
                                    <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 500;">
                                        {{ $attendance->user->profile->getDivisionLabel() }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-size: 11px;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; color: #6b7280;">{{ $attendance->date->format('d M Y') }}</td>
                            <td style="padding: 12px 16px; text-align: center; color: #3b82f6; font-weight: 600;">
                                {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                            </td>
                            <td style="padding: 12px 16px; text-align: center; color: #6b7280;">
                                {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                            </td>
                            <td style="padding: 12px 16px; text-align: center;">
                                <span style="
                                    display: inline-block;
                                    padding: 5px 10px;
                                    border-radius: 4px;
                                    font-size: 11px;
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
                                        ✓ Hadir
                                    @elseif ($attendance->status == 'late')
                                        ⏰ Terlambat
                                    @else
                                        ✕ Absen
                                    @endif
                                </span>
                            </td>
                            <td style="padding: 12px 16px; text-align: center;">
                                <button type="button" onclick="showDetailModal({{ $attendance->id }})" style="background: none; border: none; color: #3b82f6; cursor: pointer; font-size: 12px; padding: 4px 8px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 40px 16px; text-align: center; color: #9ca3af;">
                                <i class="fas fa-inbox"></i> Tidak ada data absensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($attendances->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid #e5e7eb; background: #f9fafb; display: flex; justify-content: center;">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Detail Modal -->
<div id="detailModal" style="display: none !important; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 700; margin: 0;"><i class="fas fa-info-circle"></i> Detail Absensi</h2>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">×</button>
        </div>

        <div style="space: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">Nama Peserta</p>
                    <p id="detailName" style="font-weight: 600; margin: 0;"></p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">Email</p>
                    <p id="detailEmail" style="font-weight: 600; margin: 0;"></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">Jam Masuk</p>
                    <p id="detailCheckIn" style="font-weight: 600; margin: 0; color: var(--primary);"></p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">Jam Keluar</p>
                    <p id="detailCheckOut" style="font-weight: 600; margin: 0;"></p>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">Status</p>
                <p id="detailStatus" style="font-weight: 600; margin: 0;"></p>
            </div>

            <div id="photoSection" style="margin-bottom: 16px; display: none;">
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0;"><i class="fas fa-camera"></i> Foto Selfie</p>
                <img id="detailPhoto" style="width: 100%; max-height: 300px; border-radius: 8px; object-fit: cover;">
            </div>

            <div id="locationSection" style="margin-bottom: 16px; display: none;">
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0;"><i class="fas fa-map-marker-alt"></i> Lokasi</p>
                <div style="background: #f0fdf4; border: 1px solid var(--success); padding: 12px; border-radius: 6px; font-size: 12px;">
                    <div style="margin-bottom: 8px;">
                        Lat: <strong id="detailLat"></strong><br>
                        Lng: <strong id="detailLng"></strong>
                    </div>
                    <a id="mapLink" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                        <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                    </a>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" onclick="closeDetailModal()" class="btn btn-secondary btn-block">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetailModal(attendanceId) {
        const modal = document.getElementById('detailModal');
        modal.style.display = 'flex';
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.style.display = 'none';
    }

    // Close modal when clicking outside (on the background)
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detailModal');
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeDetailModal();
                }
            });
        }
    });
</script>

@endsection
