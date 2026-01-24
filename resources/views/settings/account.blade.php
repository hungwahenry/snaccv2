<x-settings.layout>
    <div class="space-y-10">
        <!-- Email Section -->
        <section>
            <header>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase">email address</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    update your email address. you may need to verify your new email.
                </p>
            </header>

            <form method="post" action="{{ route('settings.account.email') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="email" value="email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full max-w-xl" :value="old('email', $user->email)" required autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('save') }}</x-primary-button>

                    @if (session('status') === 'email-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-green-600 dark:text-green-400"
                        >{{ __('saved.') }}</p>
                    @endif
                </div>
            </form>
        </section>

        <div class="border-t border-gray-100 dark:border-dark-border"></div>

        <!-- Export Data Section -->
        <section>
            <header>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase">export data</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    download a copy of your snaccs and profile data.
                </p>
            </header>

            <div class="mt-6">
                <form method="post" action="{{ route('settings.account.export') }}">
                    @csrf
                    <x-secondary-button type="submit" class="w-full sm:w-auto">
                        {{ __('export data items') }}
                    </x-secondary-button>
                </form>
            </div>
        </section>

        <div class="border-t border-gray-100 dark:border-dark-border"></div>

        <!-- Delete Account Section -->
        <section class="space-y-6">
            <header>
                <h2 class="text-lg font-bold text-red-600 dark:text-red-500 lowercase">delete account</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    once your account is deleted, all of its resources and data will be permanently deleted.
                </p>
            </header>

            <x-danger-button
                x-data=""
                @click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            >{{ __('delete account') }}</x-danger-button>

            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('settings.account.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900 dark:text-white lowercase">
                        are you sure you want to delete your account?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        once your account is deleted, all of its resources and data will be permanently deleted. please enter your email address to confirm you would like to permanently delete your account.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="email_confirmation" value="email confirmation" class="sr-only" />

                        <x-text-input
                            id="email_confirmation"
                            name="email_confirmation"
                            type="email"
                            class="mt-1 block w-3/4"
                            placeholder="enter your email to confirm"
                            required
                        />

                        <x-input-error :messages="$errors->userDeletion->get('email_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close-modal', 'confirm-user-deletion')">
                            {{ __('cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ml-3">
                            {{ __('delete account') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </section>
    </div>
</x-settings.layout>
