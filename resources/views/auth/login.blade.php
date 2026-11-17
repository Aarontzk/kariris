@extends('layouts.app')
@section('title', 'Log in')
@section('app-class', 'app--page app--nonav')
@section('body')
<div class="auth">
    <img src="{{ asset('img/logo-horizontal.png') }}" alt="KARIRIS" class="auth__logo">
    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        <label class="auth__label" for="username">Account name</label>
        <input class="auth__input" id="username" name="username" value="{{ old('username') }}" autocomplete="username" placeholder="username atau email" required>
        @error('username')<p class="error">{{ $message }}</p>@enderror

        <label class="auth__label" for="password">Password</label>
        <input class="auth__input" id="password" type="password" name="password" autocomplete="current-password" required>
        @error('password')<p class="error">{{ $message }}</p>@enderror

        <button class="btn-grad" type="submit">Log in</button>
        <p class="auth__note">Don't have account? <a href="{{ route('register') }}">Sign in here</a></p>
    </form>
</div>
@endsection
