@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="text-align: center;">
        <div style="font-size: 120px; margin-bottom: 20px;"><i class="fas fa-search"></i></div>
        <h1 style="font-size: 48px; font-weight: 700; color: var(--warning); margin: 0 0 10px 0;">404</h1>
        <h2 style="font-size: 24px; color: #6b7280; margin: 0 0 20px 0;">Halaman Tidak Ditemukan</h2>
        <p style="font-size: 16px; color: #9ca3af; margin: 0 0 30px 0; max-width: 500px;">
            Maaf, halaman yang Anda cari tidak ada atau telah dihapus. Silakan periksa kembali URL Anda.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="display: inline-block;">
            ← Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
