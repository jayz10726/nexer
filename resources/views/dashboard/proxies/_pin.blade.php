@php
    $color = $color ?? '#00BFFF';
@endphp

<span class="flex h-10 w-10 items-center justify-center rounded-lg" style="background-color: {{ $color }}1A; color: {{ $color }};">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 21s-7-6.5-7-11a7 7 0 1114 0c0 4.5-7 11-7 11z"/>
        <circle cx="12" cy="10" r="2.5"/>
    </svg>
</span>
