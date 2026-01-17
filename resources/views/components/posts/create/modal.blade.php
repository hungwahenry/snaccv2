<x-modal name="create-snacc" maxWidth="md">
    <div class="flex flex-col max-h-[85vh]">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-dark-border">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white lowercase" style="font-family: 'Boldonse', sans-serif;">create snacc</h2>
            <button
                type="button"
                @click="$dispatch('close-modal', 'create-snacc')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form
            method="POST"
            action="{{ route('snaccs.store') }}"
            enctype="multipart/form-data"
            x-data="createSnaccForm()"
            @submit="handleSubmit"
            class="flex flex-col flex-1 overflow-hidden"
        >
            @csrf

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4">
                <!-- Quoted Snacc Preview -->
                <x-posts.create.quoted-snacc-preview />

                <!-- Text Area -->
                <div>
                    <textarea
                        name="content"
                        x-model="content"
                        placeholder="what's on your mind?"
                        rows="2"
                        maxlength="1200"
                        class="w-full px-4 py-4 bg-gray-50 dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border focus:border-primary-500 dark:focus:border-primary-500 focus:ring-0 rounded-2xl text-base text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 transition-colors duration-200 resize-none"
                    ></textarea>

                    <!-- Character Counter and Media Icons -->
                    <div class="flex items-center justify-between mt-2 px-1">
                        <div class="flex items-center gap-3">
                            <!-- Image Upload Icon -->
                            <button
                                type="button"
                                @click="$refs.fileInput.click()"
                                x-show="images.length < 10 && !selectedGif"
                                class="flex items-center justify-center w-8 h-8 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-dark-bg rounded-lg transition-colors"
                                title="add images"
                            >
                                <x-solar-gallery-add-linear class="w-5 h-5" />
                            </button>

                            <!-- GIF Icon -->
                            <button
                                type="button"
                                x-data=""
                                @click="$dispatch('open-modal', 'gif-picker')"
                                x-show="images.length === 0 && !selectedGif"
                                class="flex items-center justify-center w-8 h-8 text-gray-500 dark:text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-dark-bg rounded-lg transition-colors"
                                title="add gif"
                            >
                                <x-solar-file-smile-linear class="w-5 h-5" />
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-500 dark:text-gray-400 lowercase" x-show="vibetags.length > 0" x-text="vibetags.length + ' vibetags'"></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 lowercase" x-text="content.length + '/1200'"></span>
                        </div>
                    </div>
                </div>

                <!-- Hidden File Input -->
                <input
                    type="file"
                    name="images[]"
                    multiple
                    accept="image/jpeg,image/jpg,image/png,image/gif"
                    @change="handleFiles"
                    x-ref="fileInput"
                    class="hidden"
                />

                <!-- Hidden GIF URL Input -->
                <input
                    type="hidden"
                    name="gif_url"
                    :value="selectedGif ? selectedGif.original_url : ''"
                />

                <!-- Hidden Quoted Snacc Slug Input -->
                <input
                    type="hidden"
                    name="quoted_snacc_slug"
                    :value="quotedSnaccSlug"
                />

                <!-- Media Preview Component -->
                <x-posts.create.media-preview />

                <!-- Visibility Toggle -->
                <x-posts.create.visibility-toggle />
            </div>

            <!-- Footer -->
            <div class="px-4 py-3 border-t border-gray-200 dark:border-dark-border">
                <x-primary-button loading-text="posting">
                    <span class="lowercase">post snacc</span>
                    <x-solar-arrow-right-linear class="w-5 h-5" />
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
