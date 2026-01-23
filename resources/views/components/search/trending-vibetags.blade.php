@props(['vibetags'])

<div class="py-8 px-4">
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 lowercase" style="font-family: 'Boldonse', sans-serif;">
            trending vibetags
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            discover what's buzzing across snacc
        </p>
    </div>

    <!-- Scattered Vibetag Pills -->
    @if($vibetags->isNotEmpty())
        <div class="flex flex-wrap gap-2 justify-center items-center max-w-4xl mx-auto" style="row-gap: 0.75rem;">
            @foreach($vibetags as $vibetag)
                <a 
                    href="{{ route('search', ['q' => $vibetag->name, 'type' => 'posts']) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-full hover:border-primary-500 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group"
                >
                    <span class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                        ~{{ $vibetag->name }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors">
                        {{ number_format($vibetag->usage_count) }}
                    </span>
                </a>
            @endforeach
        </div>
    @else
        <!-- No Vibetags Yet -->
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <x-solar-hashtag-linear class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" />
            <p class="text-sm text-gray-500 dark:text-gray-400">
                no vibetags yet. start a trend with ~yourvibes!
            </p>
        </div>
    @endif
</div>
