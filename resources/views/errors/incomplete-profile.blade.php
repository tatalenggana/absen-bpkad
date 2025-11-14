@extends('layouts.app')

@section('title', 'Profil Belum Lengkap')

@section('content')
<div style="padding: 40px; text-align:center;">
    <h1>Profil Belum Lengkap</h1>
    <p>Silakan lengkapi data profil Anda sebelum melanjutkan.</p>

    <a href="{{ route('user.profile') }}" class="btn btn-primary">
        Lengkapi Profil
    </a>
</div>
@endsection
