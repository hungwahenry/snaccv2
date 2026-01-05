<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Complete Your Profile</h2>
        <p class="mt-2 text-sm text-gray-600">
            Just a few more details to get started
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('onboarding.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- University Selection -->
        <div>
            <x-input-label for="university_id" :value="__('University')" />
            <select
                id="university_id"
                name="university_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required
            >
                <option value="">Select your university</option>
                @foreach($universities as $university)
                    <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                        {{ $university->name }} ({{ $university->acronym }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('university_id')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input
                id="username"
                class="block mt-1 w-full"
                type="text"
                name="username"
                :value="old('username')"
                required
                placeholder="e.g., john_doe"
                pattern="[a-z0-9_]+"
                maxlength="30"
            />
            <p class="mt-1 text-xs text-gray-500">Lowercase letters, numbers, and underscores only (3-30 characters)</p>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Graduation Year -->
        <div class="mt-4">
            <x-input-label for="graduation_year" :value="__('Expected Graduation Year')" />
            <select
                id="graduation_year"
                name="graduation_year"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required
            >
                <option value="">Select year</option>
                @for($year = date('Y'); $year <= date('Y') + 10; $year++)
                    <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
            <x-input-error :messages="$errors->get('graduation_year')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select
                id="gender"
                name="gender"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required
            >
                <option value="">Select gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                <option value="prefer_not_to_say" {{ old('gender') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Bio (Optional) -->
        <div class="mt-4">
            <x-input-label for="bio" :value="__('Bio (Optional)')" />
            <textarea
                id="bio"
                name="bio"
                rows="3"
                maxlength="500"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                placeholder="Tell us a bit about yourself..."
            >{{ old('bio') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <!-- Profile Photo (Optional) -->
        <div class="mt-4">
            <x-input-label for="profile_photo" :value="__('Profile Photo (Optional)')" />
            <input
                id="profile_photo"
                type="file"
                name="profile_photo"
                accept="image/jpeg,image/jpg,image/png"
                class="block mt-1 w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100"
            />
            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG (max 2MB)</p>
            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Complete Profile') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
