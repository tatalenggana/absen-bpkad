@extends('layouts.app')

@section('title', 'Laporan Absensi - Admin')

@section('content')
@include('components.admin-sidebar')

<!-- Main Content -->
<div style="margin-left: 250px; padding: 24px;">
    <!-- Header & Back Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 700; margin: 0 0 8px 0;"><i class="fas fa-chart-bar"></i> Laporan Absensi</h1>
            @if (isset($selectedDate))
                <p style="color: #6b7280; margin: 0;">{{ \Carbon\Carbon::parse($selectedDate)->format('d MMMM Y') }}</p>
            @elseif (isset($month) && isset($year))
                <p style="color: #6b7280; margin: 0;">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
            @endif
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <!-- Report Table -->
    <div class="card">
        <div class="card-header">
            <h2 style="font-size: 20px;"><i class="fas fa-list"></i> Detail Laporan</h2>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; width: 50px;">No</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Nama Peserta</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Tanggal</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Masuk</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Jam Keluar</th>
                    <th style="padding: 12px; text-align: center; font-weight: 600; color: #6b7280;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $index => $attendance)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; font-weight: 600; color: #9ca3af;">{{ $index + 1 }}</td>
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
                                    <i class="fas fa-check-circle"></i> Hadir
                                @elseif ($attendance->status == 'late')
                                    <i class="fas fa-clock"></i> Terlambat
                                @else
                                    <i class="fas fa-times-circle"></i> Absen
                                @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox"></i> Tidak ada data untuk periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($attendances instanceof \Illuminate\Pagination\Paginator)
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $attendances->links() }}
        </div>
    @endif
</div>

@endsection
            @endif

            <!-- Summary Statistics -->
            @if ($attendances->count() > 0)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4">
                        <p class="text-gray-600 font-semibold">Total Hadir</p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ $attendances->where('status', 'present')->count() }}
                        </p>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                        <p class="text-gray-600 font-semibold">Total Terlambat</p>
                        <p class="text-3xl font-bold text-yellow-600">
                            {{ $attendances->where('status', 'late')->count() }}
                        </p>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <p class="text-gray-600 font-semibold">Total Absen</p>
                        <p class="text-3xl font-bold text-red-600">
                            {{ $attendances->where('status', 'absent')->count() }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
