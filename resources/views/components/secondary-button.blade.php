<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-4 bg-white dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-full font-semibold text-base tracking-normal focus:outline-none focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-800 transition-all duration-200 ease-out disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
