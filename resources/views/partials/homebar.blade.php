<nav class="homebar" aria-label="Navigasi utama">
    @foreach (['profile' => ['Profile', 'profile'], 'search' => ['Search', 'search'], 'bookmarks' => ['Bookmark', 'bookmark']] as $route => [$label, $icon])
        <a href="{{ route($route) }}" class="homebar__item {{ $active === $route ? 'is-active' : '' }}" @if ($active === $route) aria-current="page" @endif>
            <x-icon :name="$icon" :size="26" />
            <span>{{ $label }}</span>
        </a>
    @endforeach
</nav>
