<!-- FAB (Floating Action Button) for Create Snacc -->
<button
    type="button"
    x-data=""
    @click="$dispatch('open-modal', 'create-snacc')"
    class="fixed bottom-20 right-4 lg:bottom-6 lg:right-6 z-40 w-14 h-14 bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center group"
    aria-label="Create Snacc"
>
    <x-solar-magic-stick-3-bold class="w-6 h-6 group-hover:scale-110 transition-transform" />
</button>
