<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KARIRIS') · KARIRIS</title>
    <link rel="icon" href="{{ asset('img/logo-k.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
<div class="app @yield('app-class')">
    <main class="app__body">
        @yield('body')
    </main>

    @hasSection('nav')
        @include('partials.homebar', ['active' => trim($__env->yieldContent('nav'))])
    @endif
</div>

@stack('modals')
@include('partials.confirm')

@if (session('toast'))
    <div class="toast {{ session('toastType') === 'danger' ? 'toast--danger' : '' }}" data-toast role="status">
        <x-icon name="check" /> {{ session('toast') }}
    </div>
@endif

<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</body>
</html>
