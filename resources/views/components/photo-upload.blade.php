@props(['name' => 'photo', 'currentPhoto' => null, 'size' => 'large'])

@php
    $sizeClasses = [
        'small' => 'w-16 h-16',
        'medium' => 'w-24 h-24',
        'large' => 'w-32 h-32',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['large'];
@endphp

<div x-data="photoUpload()" class="flex flex-col items-center gap-4">
    <!-- Preview Circle -->
    <div class="relative {{ $sizeClass }}">
        <div class="w-full h-full rounded-full bg-gray-100 dark:bg-dark-surface border-2 border-gray-200 dark:border-dark-border overflow-hidden flex items-center justify-center">
            <template x-if="preview || '{{ $currentPhoto }}'">
                <img :src="preview || '{{ $currentPhoto ? Storage::url($currentPhoto) : '' }}'" alt="Preview" class="w-full h-full object-cover" />
            </template>
            <template x-if="!preview && !'{{ $currentPhoto }}'">
                <x-solar-user-linear class="w-12 h-12 text-gray-400" />
            </template>
        </div>

        <!-- Upload Button Overlay -->
        <label class="absolute bottom-0 right-0 w-10 h-10 bg-primary-500 hover:bg-primary-600 rounded-full flex items-center justify-center cursor-pointer transition-colors shadow-soft">
            <x-solar-camera-bold class="w-5 h-5 text-white" />
            <input
                type="file"
                name="{{ $name }}"
                accept="image/jpeg,image/jpg,image/png"
                class="hidden"
                @change="handleFileChange"
            />
        </label>
    </div>

    <!-- File Info -->
    <div x-show="fileName" class="text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400 lowercase" x-text="fileName"></p>
    </div>
</div>

<script>
function photoUpload() {
    return {
        preview: null,
        fileName: '',

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>
