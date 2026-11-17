@extends('layouts.app')
@section('title', 'Sign in')
@section('app-class', 'app--page app--nonav')
@section('body')
<div class="auth">
    <img src="{{ asset('img/logo-horizontal.png') }}" alt="KARIRIS" class="auth__logo">
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        @foreach ([
            ['username', 'Account name', 'text', 'username'],
            ['email', 'Email', 'email', 'email'],
            ['phone_number', 'Phone Number', 'tel', 'tel'],
            ['password', 'Password', 'password', 'new-password'],
            ['password_confirmation', 'Confirm Password', 'password', 'new-password'],
        ] as [$name, $label, $type, $ac])
            <label class="auth__label" for="{{ $name }}">{{ $label }}</label>
            <input class="auth__input" id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" autocomplete="{{ $ac }}"
                   @unless (str_starts_with($name, 'password')) value="{{ old($name) }}" @endunless required>
            @error($name)<p class="error">{{ $message }}</p>@enderror
        @endforeach
        <div class="auth__spacer"></div>
        <button class="btn-grad" type="submit">Sign in</button>
    </form>
</div>
@endsection
