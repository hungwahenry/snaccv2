@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-center']) }}>
        <p class="text-sm text-gray-600 dark:text-gray-400 lowercase">{{ $status }}</p>
    </div>
@endif
