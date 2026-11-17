@extends('layouts.app')
@section('title', 'Welcome')
@section('app-class', 'app--nonav')
@section('body')
<a href="{{ route('profile') }}" class="welcome" aria-label="Lanjut ke profil">
    <img src="{{ asset('img/logo-k.png') }}" alt="" width="152">
    <h1>Welcome, {{ $akun->username }}!</h1>
</a>
<script>setTimeout(() => location.href = @json(route('profile')), 1800);</script>
@endsection
