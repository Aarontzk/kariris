@extends('layouts.app')
@section('title', $profesi->nama_profesi)
@section('nav', 'search')
@section('body')
<header class="head">
    <div class="head__bar">
        <a href="{{ route('search') }}" class="head__btn head__btn--left" aria-label="Kembali" onclick="if (history.length > 1) { event.preventDefault(); history.back(); }"><x-icon name="back" /></a>
        <h1 class="head__title">Career Profile</h1>
        <form method="POST" action="{{ route($bookmarked ? 'bookmark.destroy' : 'bookmark.store', $profesi) }}">
            @csrf
            @if ($bookmarked) @method('DELETE') @endif
            <button class="head__btn head__btn--right {{ $bookmarked ? 'is-on' : '' }}" aria-pressed="{{ $bookmarked ? 'true' : 'false' }}"
                    aria-label="{{ $bookmarked ? 'Hapus bookmark' : 'Simpan bookmark' }}">
                <x-icon :name="$bookmarked ? 'bookmark-fill' : 'bookmark'" />
            </button>
        </form>
    </div>
</header>

<div class="hero">
    <img src="{{ $profesi->gambarUrl() }}" alt="" class="hero__img">
    <h2 class="hero__name">{{ $profesi->nama_profesi }}</h2>
    <p class="hero__cat">{{ $profesi->kategori->label }}</p>
    <span class="badge badge--{{ $profesi->status }}">{{ $profesi->status }}</span>
</div>

<div class="section">
    <h3 class="section__title">SUMMARY</h3>
    <div class="card"><p>{{ $profesi->deskripsi }}</p></div>

    <div class="stats">
        <div class="stat"><span class="stat__ic"><x-icon name="money" :size="18" /></span><b>{{ $profesi->gaji }}</b><small>Sallary/Month</small></div>
        <div class="stat"><span class="stat__ic"><x-icon name="trend" :size="18" /></span><b>{{ $profesi->demand }}</b><small>Demand</small></div>
        <div class="stat"><span class="stat__ic stat__ic--y"><x-icon name="star" :size="18" /></span><b>{{ number_format($profesi->ratingRata(), 1) }}</b><small>Rating</small></div>
    </div>

    <h3 class="section__title">How To Join</h3>
    <div class="card"><p>{{ $profesi->cara_bergabung }}</p></div>

    <h3 class="section__title">Skills Required</h3>
    <div class="tags">
        @foreach ($profesi->daftarSkill() as $i => $s)
            <span class="tag {{ $i % 3 === 0 ? 'tag--yellow' : 'tag--blue' }}">{{ $s }}</span>
        @endforeach
    </div>

    <h3 class="section__title">Learn More</h3>
    <a href="{{ route('profesi.day', $profesi) }}" class="lcard">
        <span class="lcard__ic"><x-icon name="sun" /></span>
        <span class="lcard__info"><b>Day In The Life</b><small>A typical workday as a {{ $profesi->nama_profesi }}</small></span>
        <x-icon name="chevron" :size="18" />
    </a>
    <button class="lcard" data-open="modal-review">
        <span class="lcard__ic"><x-icon name="chat" /></span>
        <span class="lcard__info"><b>Career Review</b><small>Career Risk &amp; Challenges</small></span>
        <x-icon name="chevron" :size="18" />
    </button>
</div>
@endsection

@push('modals')
<div class="overlay" id="modal-review" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal__head">
            <h2 class="modal__title">{{ $profesi->nama_profesi }}</h2>
            <button class="modal__close" data-close aria-label="Tutup"><x-icon name="close" :size="16" /></button>
        </div>
        <div class="modal__body">
            <p class="cm-lead">What Professional Say</p>
            @forelse ($profesi->reviews as $r)
                <article class="review">
                    <p class="review__name">{{ $r->akun->username }} <span class="stars">{{ str_repeat('★', $r->rating) }}</span></p>
                    @if ($r->jabatan_reviewer)<p class="review__job">{{ $r->jabatan_reviewer }}</p>@endif
                    <p class="review__text">"{{ $r->isi_ulasan }}"</p>
                    <button class="review__more" data-expand="text">Read More</button>
                </article>
            @empty
                <p class="modal__text">Belum ada review untuk profesi ini.</p>
            @endforelse

            <p class="cm-lead" style="margin:16px 0 0; display:flex; justify-content:space-between">Burnout Rate <span style="color:var(--sekunder)">{{ $profesi->burnout_rate }}%</span></p>
            <div class="bar" role="progressbar" aria-valuenow="{{ $profesi->burnout_rate }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $profesi->burnout_rate }}%"></span></div>
        </div>
    </div>
</div>
@endpush
