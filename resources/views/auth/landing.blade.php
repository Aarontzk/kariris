@extends('layouts.app')
@section('title', 'Know Your Career')
@section('app-class', 'app--nonav')
@section('body')
<div class="landing">
    <img src="{{ asset('img/logo-k.png') }}" alt="" class="landing__k">
    <img src="{{ asset('img/logo-wordmark.png') }}" alt="KARIRIS" class="landing__word">
    <p class="landing__tag">KNOW YOUR CAREER</p>
    <div class="landing__actions">
        <a href="{{ route('login') }}" class="btn-grad">Log in</a>
        <a href="{{ route('register') }}" class="btn-grad">Sign in</a>
    </div>
</div>
@endsection
