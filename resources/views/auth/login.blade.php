@extends('layouts.app')

@section('title', 'Login - Absensi BPKAD Garut')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px;">
    <div class="card" style="width: 100%; max-width: 420px;">
        <!-- Header with Logo -->
        <div style="text-align: center; padding: 24px; background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%); border-radius: 12px 12px 0 0;">
            <img src="{{ asset('image/logonav.png') }}" alt="Logo" style="height: 60px; margin-bottom: 12px;">
            <h1 style="color: white; font-size: 28px; font-weight: 700; margin: 0;">Absensi BPKAD</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 14px; margin: 4px 0 0 0;">Garut - PKL/Magang System</p>
        </div>

        <!-- Body -->
        <div class="card-body" style="padding: 24px;">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div style="font-size: 20px;"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <strong>Login Gagal!</strong>
                        <p style="font-size: 13px; margin-top: 4px;">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <div style="font-size: 20px;"><i class="fas fa-times-circle"></i></div>
                    <div>
                        <strong>Terjadi Kesalahan!</strong>
                        <p style="font-size: 13px; margin-top: 4px;">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label">
                        <span style="font-size: 18px;"><i class="fas fa-envelope"></i></span>
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" 
                        placeholder="example@bpkad.local" required autofocus>
                    <small class="form-hint">Gunakan email yang terdaftar</small>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label">
                        <span style="font-size: 18px;"><i class="fas fa-lock"></i></span>
                        Password
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="loginPassword" name="password" class="form-control" 
                            placeholder="Masukkan password Anda" required style="padding-right: 40px;">
                        <button type="button" 
                            onclick="toggleLoginPassword()" 
                            style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 18px; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i id="loginPasswordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-hint">Pastikan password terisi dengan benar</small>
                </div>

                <!-- Demo Credentials Info -->
                <div style="background: #f0f9ff; border-left: 3px solid var(--info); padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 13px;">
                    <p style="margin: 0 0 6px 0; font-weight: 600; color: #0369a1;"><i class="fas fa-lightbulb"></i> Demo Login:</p>
                    <p style="margin: 2px 0; color: #0c4a6e;"><strong>Admin:</strong> admin@bpkad.local / admin123</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block" style="font-size: 16px; font-weight: 600; padding: 12px;">
                    <i class="fas fa-sign-in-alt"></i> Login Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div style="display: flex; align-items: center; margin: 20px 0; gap: 8px;">
                <div style="flex: 1; height: 1px; background: #e5e7eb;"></div>
                <span style="color: #9ca3af; font-size: 12px; font-weight: 600;">ATAU</span>
                <div style="flex: 1; height: 1px; background: #e5e7eb;"></div>
            </div>

            <!-- Register Link -->
            <p style="text-align: center; color: #6b7280; margin: 0; font-size: 14px;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; cursor: pointer;">
                    Daftar Sekarang <i class="fas fa-arrow-right"></i>
                </a>
            </p>

            <!-- Footer -->
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 12px; color: #9ca3af;">
                <p style="margin: 0;">Sistem Absensi PKL/Magang BPKAD Garut v1.0</p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleLoginPassword() {
        const passwordInput = document.getElementById('loginPassword');
        const icon = document.getElementById('loginPasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@endsection
