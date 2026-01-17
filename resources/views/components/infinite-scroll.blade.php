@props(['nextUrl', 'container'])

<div x-data="{
    nextUrl: '{{ $nextUrl }}',
    isLoading: false,
    hasError: false,
    init() {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !this.isLoading && this.nextUrl && !this.hasError) {
                this.loadMore();
            }
        }, { rootMargin: '450px' });
        observer.observe(this.$el);
    },
    loadMore() {
        if (this.isLoading || !this.nextUrl) return;
        
        this.isLoading = true;
        this.hasError = false;
        
        fetch(this.nextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                const container = document.querySelector('{{ $container }}');
                if (container) {
                    const temp = document.createElement('div');
                    temp.innerHTML = data.html;
                    while (temp.firstChild) {
                         container.appendChild(temp.firstChild);
                    }
                }
                
                this.nextUrl = data.next_page_url;
                if (!this.nextUrl) {
                    this.$el.remove();
                }
            })
            .catch(err => {
                console.error('Infinite Scroll Error:', err);
                this.hasError = true;
            })
            .finally(() => {
                this.isLoading = false;
            });
    }
}" class="py-8 flex justify-center w-full min-h-[50px]">

    <!-- Loading State -->
    <div x-show="!hasError" class="text-primary-500 transition-opacity duration-200" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <x-loading-dots size="lg" />
    </div>
    
    <!-- Error State -->
    <div x-show="hasError" class="flex flex-col items-center gap-2" style="display: none;"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <p class="text-xs text-red-500 font-medium lowercase">could not load snaccs</p>
        <button @click="loadMore()" class="text-xs text-gray-500 dark:text-gray-400 underline hover:text-gray-700 dark:hover:text-gray-200 lowercase">
            try again
        </button>
    </div>

</div>
