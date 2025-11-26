@extends('admin.layout')

@section('content')
<!-- Header -->
<div class="top-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang di panel admin absensi</p>
    </div>
</div>

<!-- Content -->
<div style="padding: 25px; padding-top: 0;">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <div class="stat-number">{{ $stats['totalUsers'] ?? 0 }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
        <div class="stat-card success">
            <i class="fas fa-check-circle"></i>
            <div class="stat-number">{{ $stats['todayAttendances'] ?? 0 }}</div>
            <div class="stat-label">Hadir Hari Ini</div>
        </div>
        <div class="stat-card warning">
            <i class="fas fa-clock"></i>
            <div class="stat-number">{{ $stats['todayLate'] ?? 0 }}</div>
            <div class="stat-label">Terlambat Hari Ini</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 12px; align-items: center; width: 100%;">
            <select name="division" class="form-control" style="min-width: 200px;">
                <option value="">📊 Semua Bidang</option>
                <option value="akuntansi" {{ request('division') == 'akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                <option value="sekretariat" {{ request('division') == 'sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                <option value="anggaran" {{ request('division') == 'anggaran' ? 'selected' : '' }}>Anggaran</option>
                <option value="keuangan" {{ request('division') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                <option value="perbendaharaan" {{ request('division') == 'perbendaharaan' ? 'selected' : '' }}>Perbendaharaan</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
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
                                <button type="button" class="btn btn-primary" onclick="alert('Detail: ' + '{{ $attendance->user->name }}')">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
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

@endsection
