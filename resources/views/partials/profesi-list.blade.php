{{-- $route, $title, $profesi, $keyword, $kategori, $filters, $emptyText --}}
<header class="head">
    <div class="head__bar">
        @if ($keyword !== '')
            <a href="{{ route($route, ['kategori' => $kategori]) }}" class="head__btn head__btn--left" aria-label="Kembali"><x-icon name="back" /></a>
            <h1 class="head__title">{{ $route === 'search' ? 'Search Results' : $title }}</h1>
        @else
            <h1 class="head__title">{{ $title }}</h1>
        @endif
    </div>
    <form class="searchbox" method="GET" action="{{ route($route) }}" role="search">
        <x-icon name="search" />
        <input type="search" name="q" value="{{ $keyword }}" placeholder="Search Profession..." aria-label="Cari profesi" maxlength="80">
        <input type="hidden" name="kategori" value="{{ $kategori }}">
    </form>
</header>

<nav class="chips" aria-label="Filter kategori">
    @foreach ($filters as $f)
        <a href="{{ route($route, array_filter(['kategori' => $f, 'q' => $keyword])) }}" class="chip {{ $kategori === $f ? 'is-active' : '' }}">{{ $f }}</a>
    @endforeach
</nav>

@if ($keyword !== '')
    <p class="result-count">{{ $profesi->count() }} Professions Found</p>
@endif

@if ($profesi->isEmpty())
    <p class="empty">{{ $emptyText }}</p>
@else
    <div class="list">
        @foreach ($profesi as $p)
            <a href="{{ route('profesi.show', $p) }}" class="pcard">
                <img src="{{ $p->gambarUrl() }}" alt="" class="pcard__img" loading="lazy">
                <div class="pcard__info">
                    <p class="pcard__name">{{ $p->nama_profesi }}</p>
                    <p class="pcard__meta">{{ $p->kategori->label }} • <span class="status status--{{ $p->status }}">{{ $p->status }}</span></p>
                </div>
                <span class="pcard__chev"><x-icon name="chevron" :size="16" /></span>
            </a>
        @endforeach
    </div>
@endif
