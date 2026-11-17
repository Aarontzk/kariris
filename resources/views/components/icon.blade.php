@props(['name', 'size' => 20])
@php
    // Ikon garis sederhana (gaya Tabler/Material, sesuai ikon di Figma)
    $paths = [
        'profile' => '<path d="M2 21a5 5 0 0 1 5-5h10a5 5 0 0 1 5 5 2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Z"/><circle cx="12" cy="7" r="4"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
        'bookmark' => '<path d="M18 7v14l-6-4-6 4V7a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4Z"/>',
        'bookmark-fill' => '<path fill="currentColor" d="M18 7v14l-6-4-6 4V7a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4Z"/>',
        'back' => '<path d="m15 5-7 7 7 7"/>',
        'chevron' => '<path d="m9 5 7 7-7 7"/>',
        'chevron-down' => '<path d="m5 9 7 7 7-7"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'close' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'more' => '<circle cx="12" cy="5" r="1.4" fill="currentColor"/><circle cx="12" cy="12" r="1.4" fill="currentColor"/><circle cx="12" cy="19" r="1.4" fill="currentColor"/>',
        'edit' => '<path d="M4 20h4L19 9l-4-4L4 16v4Z"/><path d="m13.5 6.5 4 4"/>',
        'home' => '<path d="M4 11 12 4l8 7v9a1 1 0 0 1-1 1h-5v-6h-4v6H5a1 1 0 0 1-1-1v-9Z"/>',
        'check' => '<path d="m5 12 5 5 9-10"/>',
        'money' => '<rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/>',
        'trend' => '<path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
        'star' => '<path fill="currentColor" d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5 19 19M5 19l1.5-1.5M17.5 6.5 19 5"/>',
        'chat' => '<path d="M4 5h16v11H8l-4 4V5Z"/>',
        'meeting' => '<circle cx="8" cy="8" r="3"/><circle cx="16" cy="8" r="3"/><path d="M2 20a6 6 0 0 1 12 0M10 20a6 6 0 0 1 12 0"/>',
        'code' => '<path d="m8 7-5 5 5 5M16 7l5 5-5 5M14 4l-4 16"/>',
        'break' => '<path d="M4 9h13v5a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5V9Z"/><path d="M17 10h1a3 3 0 0 1 0 6h-1M8 3v3M12 3v3"/>',
        'bug' => '<rect x="7" y="7" width="10" height="13" rx="5"/><path d="M12 7V4M3 13h4M17 13h4M4 7l3 2M20 7l-3 2M4 19l3-2M20 19l-3-2"/>',
        'doc' => '<path d="M6 3h9l4 4v14H6V3Z"/><path d="M9 12h6M9 16h6"/>',
        'design' => '<path d="M12 3a9 9 0 1 0 0 18c1 0 1.5-.8 1.5-1.5 0-1.2-1-1.5-1-2.5s.8-1.5 2-1.5H17a4 4 0 0 0 4-4c0-4.7-4-8.5-9-8.5Z"/><circle cx="7.5" cy="11" r="1"/><circle cx="12" cy="7.5" r="1"/>',
        'idea' => '<path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3Z"/>',
        'task' => '<rect x="4" y="4" width="16" height="16" rx="3"/><path d="m8 12 3 3 5-6"/>',
        'rocket' => '<path d="M5 15c-1 1-1.5 4-1.5 4s3-.5 4-1.5M9 15l-3-3c1-4 4-8 12-9-1 8-5 11-9 12Z"/><circle cx="14.5" cy="9.5" r="1.5"/>',
        'logout' => '<path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h11"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
    ];
@endphp
<svg {{ $attributes->merge(['width' => $size, 'height' => $size]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? $paths['task'] !!}</svg>
