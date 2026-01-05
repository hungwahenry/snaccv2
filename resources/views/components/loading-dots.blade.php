@props(['size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'w-1 h-1',
        'md' => 'w-1.5 h-1.5',
        'lg' => 'w-2 h-2',
    ];
    $dotSize = $sizeClasses[$size] ?? $sizeClasses['md'];

    $gapClasses = [
        'sm' => 'gap-1',
        'md' => 'gap-1.5',
        'lg' => 'gap-2',
    ];
    $dotGap = $gapClasses[$size] ?? $gapClasses['md'];
@endphp

<div class="flex items-center {{ $dotGap }}">
    <div class="{{ $dotSize }} bg-current rounded-full animate-pulse" style="animation-delay: 0ms;"></div>
    <div class="{{ $dotSize }} bg-current rounded-full animate-pulse" style="animation-delay: 150ms;"></div>
    <div class="{{ $dotSize }} bg-current rounded-full animate-pulse" style="animation-delay: 300ms;"></div>
</div>
