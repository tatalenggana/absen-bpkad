@extends('layouts.app')

@section('title', 'Pengaturan Admin - Absensi BPKAD Garut')

@section('content')
@include('components.admin-sidebar')

<div style="margin-left: 250px; padding: 24px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 700; margin: 0 0 8px 0;"><i class="fas fa-cog"></i> Pengaturan Sistem</h1>
            <p style="color: #6b7280; margin: 0;">Atur deadline absensi dan konfigurasi sistem</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">
            <div style="font-size: 20px;"><i class="fas fa-check-circle"></i></div>
            <div>
                <strong>Berhasil!</strong>
                <p style="font-size: 14px; margin-top: 4px;">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <div style="font-size: 20px;"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <strong>Terjadi Kesalahan!</strong>
                <p style="font-size: 14px; margin-top: 4px;">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Settings Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
        <!-- Deadline Absensi Card -->
        <div class="card">
            <div class="card-header">
                <h2 style="font-size: 18px; margin: 0;"><i class="fas fa-clock"></i> Deadline Check-In</h2>
                <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0 0;">Atur jam deadline untuk check-in pagi</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.update-deadline') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-clock"></i> Jam Deadline
                        </label>
                        <input type="time" name="check_in_deadline" value="{{ env('CHECK_IN_DEADLINE', '08:00') }}" class="form-control" required>
                        <small class="form-hint">Format: HH:MM (Contoh: 08:00, 09:30)</small>
                    </div>

                    <div style="background: #fef3c7; border-left: 3px solid var(--warning); padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 13px;">
                        <p style="margin: 0;"><strong><i class="fas fa-info-circle"></i> Catatan:</strong></p>
                        <p style="margin: 4px 0 0 0;">Absensi sebelum jam ini → <strong>HADIR</strong></p>
                        <p style="margin: 4px 0 0 0;">Absensi setelah jam ini → <strong>TERLAMBAT</strong></p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h2 style="font-size: 18px; margin: 0;"><i class="fas fa-info-circle"></i> Informasi Sistem</h2>
                <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0 0;">Status dan konfigurasi sistem</p>
            </div>
            <div class="card-body">
                <div style="space-y: 12px;">
                    <div style="display: flex; justify-content: space-between; padding: 12px; background: #f3f4f6; border-radius: 6px; margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Environment</span>
                        <strong style="color: var(--primary);">{{ env('APP_ENV', 'production') }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px; background: #f3f4f6; border-radius: 6px; margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">APP URL</span>
                        <strong style="color: var(--primary);">{{ env('APP_URL', 'http://localhost') }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px; background: #f3f4f6; border-radius: 6px; margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Database</span>
                        <strong style="color: var(--primary);">{{ env('DB_CONNECTION', 'mysql') }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; padding: 12px; background: #f3f4f6; border-radius: 6px;">
                        <span style="color: #6b7280; font-size: 14px;">Timezone</span>
                        <strong style="color: var(--primary);">{{ env('APP_TIMEZONE', 'UTC') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
