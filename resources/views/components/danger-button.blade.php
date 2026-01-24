<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-4 bg-red-500 hover:bg-red-600 active:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 border-0 rounded-full font-semibold text-base text-white tracking-normal focus:outline-none focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 transition-all duration-200 ease-out disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
