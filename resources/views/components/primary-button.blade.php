<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex items-center justify-center px-6 py-4 bg-primary-500 hover:bg-primary-600 active:bg-primary-700 dark:bg-primary-600 dark:hover:bg-primary-700 border-0 rounded-full font-semibold text-base text-white tracking-normal focus:outline-none focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 transition-all duration-200 ease-out']) }}>
    {{ $slot }}
</button>
