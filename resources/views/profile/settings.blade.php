@extends('layouts.app')
@section('title', 'Settings')
@section('nav', 'profile')
@section('body')
<header class="head">
    <div class="head__bar">
        <a href="{{ route('profile') }}" class="head__btn head__btn--left" aria-label="Kembali"><x-icon name="back" /></a>
        <h1 class="head__title">Settings</h1>
    </div>
</header>

<div class="settings">
    <div class="settings__user">
        <img src="{{ $akun->fotoUrl() }}" alt="" class="avatar">
        <span class="row-edit">
            {{ $akun->username }}
            <button class="icon-btn" data-open="modal-username" aria-label="Ubah username"><x-icon name="edit" /></button>
        </span>
    </div>

    <span class="field-label">Email</span>
    <div class="pill">{{ $akun->email }}</div>

    <span class="field-label">Phone Number</span>
    <div class="pill">{{ $akun->phone_number }}</div>

    <span class="field-label">Manage password</span>
    <button class="pill pill--white" data-open="modal-password">•••••••••• <x-icon name="edit" /></button>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout">Log out</button>
    </form>
</div>
@endsection

@push('modals')
@if ($errors->username->any())<span data-autopen="modal-username" hidden></span>@endif
@if ($errors->password->any())<span data-autopen="modal-password" hidden></span>@endif

<div class="overlay" id="modal-username" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal__head">
            <h2 class="modal__title">Change Username</h2>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <form method="POST" action="{{ route('settings.username') }}" data-confirm="warn" data-confirm-title="Change Username?"
              data-confirm-ok="Change" data-confirm-text="You won't be able to change your username again for 30 days.">
            @csrf @method('PUT')
            <div class="modal__body">
                <div class="form-row">
                    <label for="username">Username <b>*</b></label>
                    <input class="input" id="username" name="username" value="{{ old('username', $akun->username) }}" maxlength="50" required>
                    @error('username', 'username')<p class="error">{{ $message }}</p>@enderror
                </div>
                <p class="note">After changing your username, you'll need to wait 30 days before changing it again.</p>
            </div>
            <div class="modal__actions">
                <button type="button" class="btn btn--outline" data-close>Cancel</button>
                <button class="btn btn--fill">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="overlay" id="modal-password" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal__head">
            <h2 class="modal__title">Manage Password</h2>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <form method="POST" action="{{ route('settings.password') }}" data-confirm="warn">
            @csrf @method('PUT')
            <div class="modal__body">
                <div class="form-row">
                    <label for="password">New Password <b>*</b></label>
                    <input class="input" id="password" type="password" name="password" minlength="8" autocomplete="new-password" required>
                    @error('password', 'password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="password_confirmation">Confirm password <b>*</b></label>
                    <input class="input" id="password_confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required>
                </div>
            </div>
            <div class="modal__actions">
                <button type="button" class="btn btn--outline" data-close>Cancel</button>
                <button class="btn btn--fill">Save</button>
            </div>
        </form>
    </div>
</div>
@endpush
