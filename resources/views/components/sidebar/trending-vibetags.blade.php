<div class="mb-6 px-6">
    <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">trending vibes</h3>
    
    @if($vibetags->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            @foreach($vibetags as $vibetag)
                <a 
                    href="{{ route('search', ['q' => $vibetag->name, 'type' => 'posts']) }}"
                    class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-50 dark:bg-dark-bg hover:bg-primary-50 dark:hover:bg-primary-900/10 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                >
                    ~{{ $vibetag->name }}
                </a>
            @endforeach
        </div>
    @else
        <p class="text-xs text-gray-400 dark:text-gray-500 italic">no trends yet</p>
    @endif
</div>