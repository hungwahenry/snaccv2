@props(['loading' => false, 'loadingText' => null])

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'w-full inline-flex items-center justify-center px-6 py-4 bg-primary-500 hover:bg-primary-600 active:bg-primary-700 dark:bg-primary-600 dark:hover:bg-primary-700 border-0 rounded-full font-semibold text-base text-white tracking-normal focus:outline-none focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 transition-all duration-200 ease-out disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary-500 dark:disabled:hover:bg-primary-600 relative'
    ]) }}
    x-data="{ loading: {{ $loading ? 'true' : 'false' }} }"
    x-bind:disabled="loading"
    @if(!$loading)
        x-init="$el.form?.addEventListener('submit', () => loading = true)"
    @endif
>
    <span 
        class="inline-flex items-center justify-center gap-2 transition-opacity duration-200"
        :class="loading ? 'opacity-0 absolute' : 'opacity-100'"
    >
        {{ $slot }}
    </span>
    <span 
        class="inline-flex items-center justify-center gap-2 transition-opacity duration-200"
        :class="!loading ? 'opacity-0 absolute' : 'opacity-100'"
    >
        @if($loadingText)
            <span class="lowercase">{{ $loadingText }}</span>
        @endif
        <x-loading-dots size="md" />
    </span>
</button>
