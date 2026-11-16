<?php
    $icon = $icon ?? 'globe';
    $color = $color ?? '#4f72f0';
?>

<span class="flex h-11 w-11 items-center justify-center rounded-xl" style="background-color: <?php echo e($color); ?>1A; color: <?php echo e($color); ?>;">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <?php switch($icon):
            case ('cpu'): ?>
                <rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 3v3M15 3v3M9 18v3M15 18v3M3 9h3M3 15h3M18 9h3M18 15h3"/>
                <?php break; ?>
            <?php case ('tag'): ?>
                <path d="M20 12l-8 8-8-8V4h8z"/><circle cx="8" cy="8" r="1.3" fill="currentColor" stroke="none"/>
                <?php break; ?>
            <?php case ('target'): ?>
                <circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="0.7" fill="currentColor" stroke="none"/>
                <?php break; ?>
            <?php case ('briefcase'): ?>
                <rect x="3" y="7" width="18" height="12" rx="2"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/>
                <?php break; ?>
            <?php case ('layers'): ?>
                <path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/>
                <?php break; ?>
            <?php case ('scale'): ?>
                <path d="M12 3v18M5 8l-3 6a4 4 0 008 0zM19 8l-3 6a4 4 0 008 0zM5 8h14"/>
                <?php break; ?>
            <?php case ('globe'): ?>
                <circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 4 6 4 9s-1.5 6.5-4 9c-2.5-2.5-4-6-4-9s1.5-6.5 4-9z"/>
                <?php break; ?>
            <?php case ('lion'): ?>
                <circle cx="12" cy="13" r="4"/><path d="M4 8c1-2 3-3 4-1M20 8c-1-2-3-3-4-1M8 16c0 2 1.5 4 4 4s4-2 4-4"/>
                <?php break; ?>
            <?php case ('headset'): ?>
                <path d="M4 13a8 8 0 0116 0"/><rect x="3" y="13" width="4" height="6" rx="1.5"/><rect x="17" y="13" width="4" height="6" rx="1.5"/>
                <?php break; ?>
            <?php case ('cursor'): ?>
                <path d="M6 3l14 8-6 1.5L12 19z"/>
                <?php break; ?>
            <?php case ('task'): ?>
                <rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 12l2.5 2.5L16 9"/>
                <?php break; ?>
            <?php case ('chat'): ?>
                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                <?php break; ?>
            <?php case ('paper-plane'): ?>
                <path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4z"/>
                <?php break; ?>
            <?php case ('shield-chat'): ?>
                <path d="M12 3l7 3v6c0 5-3 8-7 9-4-1-7-4-7-9V6z"/><path d="M9 11h6M9 14h4"/>
                <?php break; ?>
            <?php default: ?>
                <circle cx="12" cy="12" r="9"/>
        <?php endswitch; ?>
    </svg>
</span>
<?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/dashboard/numbers/_icon.blade.php ENDPATH**/ ?>