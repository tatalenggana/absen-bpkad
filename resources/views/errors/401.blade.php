@extends('layouts.app')

@section('title', 'Unauthorized')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="text-align: center;">
        <div style="font-size: 120px; margin-bottom: 20px;"><i class="fas fa-lock"></i></div>
        <h1 style="font-size: 48px; font-weight: 700; color: var(--primary); margin: 0 0 10px 0;">401</h1>
        <h2 style="font-size: 24px; color: #6b7280; margin: 0 0 20px 0;">Unauthorized Access</h2>
        <p style="font-size: 16px; color: #9ca3af; margin: 0 0 30px 0; max-width: 500px;">
            Anda tidak memiliki akses untuk mengakses halaman ini. Silakan login terlebih dahulu atau hubungi administrator.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="display: inline-block;">
            ← Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
