@extends('layouts.app')
@section('title', 'Profile')
@section('nav', 'profile')
@php
    $tab = session('tab', request('tab') === 'pengalaman' || $errors->pengalaman->any() ? 'pengalaman' : 'skills');
@endphp
@section('body')
<header class="ptop">
    <img src="{{ asset('img/logo-horizontal.png') }}" alt="KARIRIS" class="ptop__logo">
    <a href="{{ route('settings') }}" aria-label="Settings"><img src="{{ asset('img/ic-setting.svg') }}" alt="" class="ptop__set"></a>
</header>

<img src="{{ $akun->fotoUrl() }}" alt="Foto {{ $akun->username }}" class="avatar">
<p class="uname">{{ $akun->username }}</p>

<div>
    <div class="tabs" data-tabs role="tablist">
        <button class="tab {{ $tab === 'skills' ? 'is-active' : '' }}" data-tab="skills" role="tab">Skills</button>
        <button class="tab {{ $tab === 'pengalaman' ? 'is-active' : '' }}" data-tab="pengalaman" role="tab">Pengalaman</button>
    </div>

    <section class="panel {{ $tab === 'skills' ? 'is-active' : '' }}" data-panel="skills">
        <div class="panel__head">
            <span>{{ $akun->skills->count() }} skill</span>
            <button class="btn-add" data-open="modal-skill" data-form="form-skill" data-title="Add Skill"><x-icon name="plus" :size="14" /> Tambah</button>
        </div>
        @if ($akun->skills->isEmpty())
            <p class="empty">Belum ada skill. Tambahkan skill pertamamu!</p>
        @else
            <div class="skills">
                @foreach ($akun->skills as $skill)
                    <article class="scard">
                        <h3 class="scard__name">{{ $skill->nama_skill }}</h3>
                        <span class="level level--{{ $skill->level }}">{{ $skill->level }}</span>
                        <button class="kebab" data-menu aria-label="Menu {{ $skill->nama_skill }}"><x-icon name="more" /></button>
                        <div class="menu">
                            <button data-open="modal-skill" data-form="form-skill" data-title="Edit Skill" data-method="PUT"
                                    data-action="{{ route('skills.update', $skill) }}"
                                    data-values="{{ json_encode(['nama_skill' => $skill->nama_skill, 'level' => $skill->level]) }}">Edit</button>
                            <form method="POST" action="{{ route('skills.destroy', $skill) }}" data-confirm="danger"
                                  data-confirm-title="Delete This Entry?" data-confirm-ok="Delete"
                                  data-confirm-text="This action cannot be undone. Are you sure you want to delete this skill?">
                                @csrf @method('DELETE')
                                <button class="danger">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="panel {{ $tab === 'pengalaman' ? 'is-active' : '' }}" data-panel="pengalaman">
        <div class="panel__head">
            <span>{{ $akun->pengalaman->count() }} Pengalaman</span>
            <button class="btn-add" data-open="modal-exp" data-form="form-exp" data-title="Add Experience"><x-icon name="plus" :size="14" /> Tambah</button>
        </div>
        @forelse ($akun->pengalaman as $exp)
            <article class="ecard">
                <div class="ecard__top">
                    <h3 class="ecard__pos">{{ $exp->posisi }}</h3>
                    <span class="ecard__type">{{ $exp->tipe }}</span>
                </div>
                <p class="ecard__sub">{{ $exp->perusahaan }} • {{ $exp->periode() }}</p>
                <p class="ecard__desc">{{ $exp->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                <button class="ecard__toggle" data-expand aria-label="Tampilkan deskripsi"><x-icon name="chevron-down" /></button>
                <button class="kebab" data-menu aria-label="Menu {{ $exp->posisi }}"><x-icon name="more" /></button>
                <div class="menu">
                    <button data-open="modal-exp" data-form="form-exp" data-title="Edit Experience" data-method="PUT"
                            data-action="{{ route('pengalaman.update', $exp) }}"
                            data-values="{{ json_encode(['tipe' => $exp->tipe, 'posisi' => $exp->posisi, 'perusahaan' => $exp->perusahaan, 'tanggal_mulai' => $exp->tanggal_mulai->format('Y-m-d'), 'tanggal_selesai' => $exp->tanggal_selesai?->format('Y-m-d'), 'deskripsi' => $exp->deskripsi]) }}">Edit</button>
                    <form method="POST" action="{{ route('pengalaman.destroy', $exp) }}" data-confirm="danger"
                          data-confirm-title="Delete This Entry?" data-confirm-ok="Delete"
                          data-confirm-text="This action cannot be undone. Are you sure you want to delete this experience?">
                        @csrf @method('DELETE')
                        <button class="danger">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="empty">Belum ada pengalaman. Tambahkan pengalaman pertamamu!</p>
        @endforelse
    </section>
</div>

<button class="cm-btn" data-open="modal-cm" aria-label="Career Match">
    <img src="{{ asset('img/career-match.png') }}" alt="">
    <span>Career Match</span>
</button>
@endsection

@push('modals')
@if ($errors->skill->any())<span data-autopen="modal-skill" hidden></span>@endif
@if ($errors->pengalaman->any())<span data-autopen="modal-exp" hidden></span>@endif

<div class="overlay" id="modal-skill" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal__head">
            <h2 class="modal__title" data-form-title>Add Skill</h2>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <form id="form-skill" method="POST" action="{{ route('skills.store') }}" data-default-action="{{ route('skills.store') }}" data-confirm="warn">
            @csrf <input type="hidden" name="_method" value="POST">
            <div class="modal__body">
                <div class="form-row">
                    <label for="nama_skill">Skill Name <b>*</b></label>
                    <input class="input" id="nama_skill" name="nama_skill" maxlength="60" value="{{ old('nama_skill') }}" placeholder="e.g. Figma" required>
                    @error('nama_skill', 'skill')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="level">Proficiency Level <b>*</b></label>
                    <select class="input" id="level" name="level" required>
                        <option value="">Pilih level</option>
                        @foreach ($levels as $l)<option @selected(old('level') === $l)>{{ $l }}</option>@endforeach
                    </select>
                    @error('level', 'skill')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="modal__actions">
                <button type="button" class="btn btn--outline" data-close>Cancel</button>
                <button class="btn btn--fill">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="overlay" id="modal-exp" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal__head">
            <h2 class="modal__title" data-form-title>Add Experience</h2>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <form id="form-exp" method="POST" action="{{ route('pengalaman.store') }}" data-default-action="{{ route('pengalaman.store') }}" data-confirm="warn">
            @csrf <input type="hidden" name="_method" value="POST">
            <div class="modal__body">
                <div class="form-row">
                    <label for="tipe">Type <b>*</b></label>
                    <select class="input" id="tipe" name="tipe" required>
                        <option value="">Pilih tipe</option>
                        @foreach ($tipe as $t)<option @selected(old('tipe') === $t)>{{ $t }}</option>@endforeach
                    </select>
                    @error('tipe', 'pengalaman')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="posisi">Role / position <b>*</b></label>
                    <input class="input" id="posisi" name="posisi" maxlength="100" value="{{ old('posisi') }}" placeholder="e.g. Digital Marketing Intern" required>
                    @error('posisi', 'pengalaman')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="perusahaan">Organization / Institution <b>*</b></label>
                    <input class="input" id="perusahaan" name="perusahaan" maxlength="100" value="{{ old('perusahaan') }}" placeholder="e.g. Telkom" required>
                    @error('perusahaan', 'pengalaman')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label>Duration <b>*</b></label>
                    <div class="form-grid">
                        <div><small>Start</small><input class="input" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" max="{{ now()->toDateString() }}" required></div>
                        <div><small>End</small><input class="input" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"></div>
                    </div>
                    @error('tanggal_mulai', 'pengalaman')<p class="error">{{ $message }}</p>@enderror
                    @error('tanggal_selesai', 'pengalaman')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="deskripsi">Activities Description</label>
                    <textarea class="input" id="deskripsi" name="deskripsi" maxlength="1000">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div class="modal__actions">
                <button type="button" class="btn btn--outline" data-close>Cancel</button>
                <button class="btn btn--fill">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="overlay" id="modal-cm" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal__head">
            <div>
                <h2 class="modal__title">Career Match</h2>
                <p class="modal__sub">Based on {{ $akun->skills->count() }} skills + {{ $akun->pengalaman->count() }} experience</p>
            </div>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <div class="modal__body">
            @if ($matches->isEmpty())
                <p class="modal__text">Tambahkan minimal satu skill agar KARIRIS bisa menghitung karier yang cocok untukmu.</p>
            @else
                <p class="cm-lead">Karier yang cocok dengan kamu</p>
                <div class="cm-circles">
                    @foreach ($matches as $i => $m)
                        <button class="cm-circle {{ $i === 0 ? 'is-active' : '' }}" data-cm="{{ $i }}">
                            <span class="cm-circle__ring">{{ $m['persen'] }}%</span>
                            <span class="cm-circle__label">{{ $m['profesi']->nama_profesi }}</span>
                        </button>
                    @endforeach
                </div>
                @foreach ($matches as $i => $m)
                    <div class="cm-detail {{ $i === 0 ? 'is-active' : '' }}" data-cm-detail="{{ $i }}">
                        <div class="cm-detail__title">
                            {{ $m['profesi']->nama_profesi }}
                            <a href="{{ route('profesi.show', $m['profesi']) }}">Lihat profesi ›</a>
                        </div>
                        <div class="tags">
                            @foreach ($m['dimiliki'] as $s)<span class="tag tag--have">{{ $s }}</span>@endforeach
                            @foreach ($m['kurang'] as $s)<span class="tag tag--miss">{{ $s }}</span>@endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endpush
