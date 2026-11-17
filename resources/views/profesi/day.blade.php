@extends('layouts.app')
@section('title', 'Day in the Life')
@section('nav', 'search')
@section('body')
<header class="head">
    <div class="head__bar">
        <a href="{{ route('profesi.show', $profesi) }}" class="head__btn head__btn--left" aria-label="Kembali"><x-icon name="back" /></a>
        <h1 class="head__title">Day in the Life</h1>
        <a href="{{ route('profile') }}" class="head__btn head__btn--right" aria-label="Beranda"><x-icon name="home" /></a>
    </div>
</header>

<div class="hero">
    <img src="{{ $profesi->gambarUrl() }}" alt="" class="hero__img" style="width:90px;height:90px">
    <h2 class="hero__name">{{ $profesi->nama_profesi }}</h2>
    <p class="hero__cat">{{ $profesi->kategori->label }}</p>
</div>

<div>
    <div class="tabs" data-tabs role="tablist">
        <button class="tab tab--yellow {{ $tab === 'rutin' ? 'is-active' : '' }}" data-tab="rutin" role="tab">Rutin</button>
        <button class="tab tab--yellow {{ $tab === 'project' ? 'is-active' : '' }}" data-tab="project" role="tab">Project</button>
    </div>

    <section class="panel {{ $tab === 'rutin' ? 'is-active' : '' }}" data-panel="rutin">
        <p class="dil-label">RUTINITAS HARIAN</p>
        <div class="dil-stats">
            <div><small>Total aktivitas</small><b>{{ $rutinitas->count() }}</b></div>
            <div><small>Estimasi durasi</small><b>{{ $totalJam }} jam</b></div>
        </div>
        @if ($rutinitas->isEmpty())<p class="empty">Rutinitas untuk profesi ini belum tersedia.</p>@endif
        @foreach ($rutinitas as $r)
            <button class="acard" data-sheet="{{ json_encode(['nama' => $r->kegiatan, 'sub' => 'Detail Aktivitas', 'deskripsi' => $r->deskripsi, 'chips' => [$r->jamMulai(), $r->durasiMenit().' menit']]) }}">
                <span class="acard__ic"><x-icon :name="$r->ikon" /></span>
                <span class="acard__info"><b>{{ $r->kegiatan }}</b><small>{{ $r->jam }}</small></span>
                <span class="acard__go"><x-icon name="arrow" :size="16" /></span>
            </button>
        @endforeach
    </section>

    <section class="panel {{ $tab === 'project' ? 'is-active' : '' }}" data-panel="project">
        <p class="dil-label">FASE PROYEK</p>
        <div class="dil-stats">
            <div><small>Total fase</small><b>{{ $fase->count() }}</b></div>
            <div><small>Estimasi durasi</small><b>{{ $totalMinggu }} minggu</b></div>
        </div>
        @if ($fase->isEmpty())<p class="empty">Fase proyek untuk profesi ini belum tersedia.</p>@endif
        @foreach ($fase as $f)
            <button class="acard" data-sheet="{{ json_encode(['nama' => 'Fase: '.$f->nama_fase, 'sub' => 'Detail Fase Project', 'deskripsi' => $f->deskripsi, 'chips' => [$f->durasi, $f->sprint]]) }}">
                <span class="acard__ic"><x-icon :name="$f->ikon" /></span>
                <span class="acard__info"><b>Fase {{ $f->urutan }} | {{ $f->nama_fase }}</b><small>{{ $f->ringkasan }}</small></span>
                <span class="acard__go"><x-icon name="arrow" :size="16" /></span>
            </button>
        @endforeach
    </section>
</div>
@endsection

@push('modals')
<div class="sheet-wrap" id="sheet" role="dialog" aria-modal="true">
    <div class="sheet">
        <div class="sheet__head">
            <span class="acard__ic" data-sheet-icon></span>
            <div style="flex:1"><b data-sheet-name></b><small data-sheet-sub></small></div>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <div class="sheet__body">
            <div class="sheet__chips" data-sheet-chips></div>
            <h4>DESKRIPSI</h4>
            <p data-sheet-desc></p>
        </div>
    </div>
</div>
@endpush
