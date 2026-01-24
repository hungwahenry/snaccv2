<x-settings.layout>
    <form method="post" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('patch')
        
        <!-- Public Identity -->
        <section>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white lowercase mb-6">public identity</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Photo Column -->
                <div class="space-y-4">
                    <x-input-label value="profile photo" />
                    
                    <div x-data="{ photoPreview: null }">
                        <div class="relative group w-32 h-32 mx-auto md:mx-0">
                            <div class="w-full h-full rounded-3xl overflow-hidden ring-4 ring-gray-50 dark:ring-dark-bg cursor-pointer" @click="$refs.photoInput.click()">
                                <img 
                                    x-bind:src="photoPreview" 
                                    x-show="photoPreview"
                                    class="w-full h-full object-cover"
                                >
                                <img 
                                    src="{{ $user->profile->profile_photo ? Storage::url($user->profile->profile_photo) : 'https://api.dicebear.com/9.x/thumbs/svg?seed=' . urlencode($user->profile->username) }}" 
                                    x-show="!photoPreview"
                                    alt="{{ $user->name }}" 
                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                                >
                                
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <x-solar-camera-add-linear class="w-8 h-8 text-white" />
                                </div>
                            </div>
                        </div>

                        <input 
                            type="file" 
                            name="profile_photo" 
                            class="hidden" 
                            x-ref="photoInput"
                            accept="image/*"
                            @change="
                                const file = $refs.photoInput.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            "
                        >
                    </div>
                    
                    <x-input-error :messages="$errors->get('profile_photo')" />
                </div>

                <!-- Info Column -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Username -->
                    <div>
                        <x-input-label for="username" value="username" />
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">@</span>
                            </div>
                            <x-text-input 
                                id="username" 
                                name="username"
                                type="text" 
                                class="pl-9 bg-white" 
                                :value="old('username', $user->profile->username)"
                                required 
                                autocomplete="username" 
                            />
                        </div>
                        <p class="mt-2 text-xs text-gray-400">snacc.com/{{ $user->profile->username }}</p>
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Bio -->
                    <div>
                        <x-input-label for="bio" value="bio" />
                        <x-textarea 
                            id="bio" 
                            name="bio"
                            rows="4" 
                            class="mt-2" 
                            placeholder="tell us about yourself..."
                        >{{ old('bio', $user->profile->bio) }}</x-textarea>
                        <div class="flex justify-between mt-2">
                            <p class="text-xs text-gray-400">visible on your public profile.</p>
                            <p class="text-xs text-gray-400" x-data="{ count: {{ strlen($user->profile->bio ?? '') }} }" x-init="$watch('$el.value', value => count = value.length)">
                                <span x-text="document.getElementById('bio').value.length"></span>/160
                            </p>
                        </div>
                        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                    </div>
                </div>
            </div>
        </section>

        <div class="border-t border-gray-100 dark:border-dark-border"></div>

        <!-- Student Details -->
        <section>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white lowercase mb-6">student details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Graduation Year -->
                <div>
                    <x-input-label for="graduation_year" value="graduation year" />
                    <x-text-input 
                        id="graduation_year" 
                        name="graduation_year"
                        type="number" 
                        min="2000" 
                        max="2100" 
                        class="mt-2 block w-full" 
                        :value="old('graduation_year', $user->profile->graduation_year)"
                        required 
                    />
                    <x-input-error :messages="$errors->get('graduation_year')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div>
                    <x-input-label for="gender" value="gender" />
                    <x-select id="gender" name="gender" class="mt-2 block w-full">
                        <option value="prefer_not_to_say" {{ old('gender', $user->profile->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>prefer not to say</option>
                        <option value="male" {{ old('gender', $user->profile->gender) === 'male' ? 'selected' : '' }}>male</option>
                        <option value="female" {{ old('gender', $user->profile->gender) === 'female' ? 'selected' : '' }}>female</option>
                        <option value="other" {{ old('gender', $user->profile->gender) === 'other' ? 'selected' : '' }}>other</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>
            </div>
        </section>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-dark-border">
            <x-primary-button>
                {{ __('save changes') }}
            </x-primary-button>
        </div>
    </form>
</x-settings.layout>
