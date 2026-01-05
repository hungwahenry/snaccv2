@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-primary-600 dark:text-primary-400 space-y-1 mt-2']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1">
                <x-solar-danger-circle-bold class="w-4 h-4 flex-shrink-0" />
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
