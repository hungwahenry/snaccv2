<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="font-bold text-5xl sm:text-6xl text-gray-900 dark:text-white leading-tight lowercase mb-2">
            welcome!
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-400 lowercase">
            let's set up your profile
        </p>
    </div>

    <form method="POST" action="{{ route('onboarding.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Profile Photo Section -->
        <div class="flex flex-col items-center gap-2">
            <x-photo-upload name="profile_photo" />
            <p class="text-xs text-gray-500 dark:text-gray-400 lowercase text-center">
                add a profile photo (optional)
            </p>
        </div>

        <!-- Form Fields -->
        <div class="space-y-5">
            <!-- Username -->
            <div class="space-y-2">
                <x-input-label for="username" value="username" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <x-solar-user-linear class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                    </div>
                    <x-text-input
                        id="username"
                        type="text"
                        name="username"
                        :value="old('username')"
                        required
                        autofocus
                        placeholder="john_doe"
                        pattern="[a-z0-9_]+"
                        maxlength="30"
                        class="pl-12"
                    />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 lowercase">lowercase letters, numbers, and underscores only</p>
                <x-input-error :messages="$errors->get('username')" />
            </div>

            <!-- University Selection -->
            <div class="space-y-2">
                <x-input-label for="university_id" value="university" />
                <x-searchable-select
                    name="university_id"
                    :options="collect($universities)->map(fn($uni) => [
                        'value' => $uni->id,
                        'label' => $uni->name . ' (' . $uni->acronym . ')'
                    ])->toArray()"
                    placeholder="select your university"
                    searchPlaceholder="search universities..."
                    :value="old('university_id')"
                    required
                />
                <x-input-error :messages="$errors->get('university_id')" />
            </div>
        </div>

        <!-- Submit Button -->
        <x-primary-button loading-text="creating profile">
            <span class="lowercase">get started</span>
            <x-solar-arrow-right-linear class="w-5 h-5 ml-2" />
        </x-primary-button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400 lowercase">
            you can add more details to your profile later
        </p>
    </div>
</x-guest-layout>
