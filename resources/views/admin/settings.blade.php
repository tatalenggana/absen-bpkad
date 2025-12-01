@extends('admin.layout')

@section('content')
<!-- Header -->
<div class="top-header">
    <div>
        <h1>Pengaturan</h1>
        <p>Kelola konfigurasi sistem absensi</p>
    </div>
</div>

<!-- Content -->
<div style="padding: 25px; padding-top: 0;">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <div class="stat-number">{{ $deadline ?? '09:00' }}</div>
            <div class="stat-label">Batas Check-in</div>
        </div>
        <div class="stat-card success">
            <i class="fas fa-map-marker-alt"></i>
            <div class="stat-number">Online</div>
            <div class="stat-label">Lokasi Aktif</div>
        </div>
        <div class="stat-card warning">
            <i class="fas fa-server"></i>
            <div class="stat-number">{{ config('app.env') }}</div>
            <div class="stat-label">Environment</div>
        </div>
    </div>

    <!-- Settings Form - Deadline -->
    <div class="filter-section" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: var(--text-primary);">⏰ Pengaturan Waktu Check-in</h3>
        <form method="POST" action="{{ route('admin.settings.update-deadline') }}">
            @csrf
            @method('PUT')
            <div style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; max-width: 200px;">
                    <label for="check_in_deadline" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Waktu Batas Check-in
                    </label>
                    <input 
                        type="time" 
                        id="check_in_deadline" 
                        name="check_in_deadline" 
                        class="form-control" 
                        value="{{ $deadline ?? '09:00' }}"
                        required
                    >
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
            @if($errors->has('check_in_deadline'))
                <div style="color: var(--danger); margin-top: 10px; font-size: 14px;">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first('check_in_deadline') }}
                </div>
            @endif
            @if(session('success_deadline'))
                <div style="color: var(--success); margin-top: 10px; font-size: 14px; padding: 10px; background: rgba(16, 185, 129, 0.1); border-radius: 6px;">
                    <i class="fas fa-check-circle"></i> {{ session('success_deadline') }}
                </div>
            @endif
        </form>
    </div>

    <!-- Settings Form - Location -->
    <div class="filter-section" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: var(--text-primary);">📍 Pengaturan Lokasi Absensi</h3>
        <form method="POST" action="{{ route('admin.settings.update-location') }}">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label for="office_latitude" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Latitude
                    </label>
                    <input 
                        type="number" 
                        id="office_latitude" 
                        name="office_latitude" 
                        class="form-control" 
                        step="0.000001"
                        value="{{ $latitude ?? '-7.202507' }}"
                        required
                        placeholder="-7.202507"
                    >
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">Contoh: -7.202507</small>
                </div>
                <div>
                    <label for="office_longitude" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Longitude
                    </label>
                    <input 
                        type="number" 
                        id="office_longitude" 
                        name="office_longitude" 
                        class="form-control" 
                        step="0.000001"
                        value="{{ $longitude ?? '107.890626' }}"
                        required
                        placeholder="107.890626"
                    >
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">Contoh: 107.890626</small>
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="office_radius" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                    Radius Jarak (Meter)
                </label>
                <input 
                    type="number" 
                    id="office_radius" 
                    name="office_radius" 
                    class="form-control" 
                    value="{{ $radius ?? '500' }}"
                    required
                    placeholder="500"
                >
                <small style="color: var(--text-secondary); margin-top: 4px; display: block;">User harus berada dalam radius ini untuk bisa absen</small>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="office_address" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                    Alamat Lokasi
                </label>
                <input 
                    type="text" 
                    id="office_address" 
                    name="office_address" 
                    class="form-control" 
                    value="{{ $address ?? 'SMKN 1 Garut' }}"
                    required
                    placeholder="Masukkan alamat lokasi absensi"
                >
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Lokasi
                </button>
                <button type="button" class="btn btn-secondary" onclick="getMyLocation()">
                    <i class="fas fa-location-arrow"></i> Ambil Lokasi Saya
                </button>
            </div>
            @if($errors->has('office_latitude') || $errors->has('office_longitude') || $errors->has('office_radius'))
                <div style="color: var(--danger); margin-top: 10px; font-size: 14px;">
                    <i class="fas fa-exclamation-circle"></i> 
                    {{ $errors->first('office_latitude') ?? $errors->first('office_longitude') ?? $errors->first('office_radius') }}
                </div>
            @endif
            @if(session('success_location'))
                <div style="color: var(--success); margin-top: 10px; font-size: 14px; padding: 10px; background: rgba(16, 185, 129, 0.1); border-radius: 6px;">
                    <i class="fas fa-check-circle"></i> {{ session('success_location') }}
                </div>
            @endif
        </form>
    </div>

    <!-- System Info -->
    <div class="filter-section">
        <h3 style="margin-bottom: 20px; color: var(--text-primary);">ℹ️ Informasi Sistem</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 12px; font-weight: 600; color: var(--text-primary); width: 200px;">Environment</td>
                <td style="padding: 12px; color: var(--text-secondary);">{{ config('app.env') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 12px; font-weight: 600; color: var(--text-primary);">URL Aplikasi</td>
                <td style="padding: 12px; color: var(--text-secondary);">{{ config('app.url') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 12px; font-weight: 600; color: var(--text-primary);">Database</td>
                <td style="padding: 12px; color: var(--text-secondary);">{{ config('database.default') }}</td>
            </tr>
            <tr>
                <td style="padding: 12px; font-weight: 600; color: var(--text-primary);">Timezone</td>
                <td style="padding: 12px; color: var(--text-secondary);">{{ config('app.timezone') }}</td>
            </tr>
        </table>
    </div>
</div>

<script>
function getMyLocation() {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak support geolocation');
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById('office_latitude').value = lat.toFixed(6);
            document.getElementById('office_longitude').value = lng.toFixed(6);
            
            alert(`✓ Lokasi berhasil diambil!\nLat: ${lat.toFixed(6)}\nLng: ${lng.toFixed(6)}`);
        },
        function(error) {
            alert('Error: ' + error.message);
        }
    );
}
</script>

@endsection
