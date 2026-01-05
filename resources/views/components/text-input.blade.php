@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-4 bg-gray-50 dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border focus:border-primary-500 dark:focus:border-primary-500 focus:ring-0 rounded-2xl text-base text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 transition-colors duration-200']) }}>
