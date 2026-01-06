@props(['images'])

@php
    $imageUrls = $images->map(fn($img) => Storage::url($img->image_path))->toArray();
    $multipleImages = $images->count() > 1;
@endphp

<div class="mt-3 rounded-xl overflow-hidden">
    <div class="flex gap-1 {{ $multipleImages ? 'overflow-x-auto snap-x snap-mandatory scrollbar-hide' : '' }}">
        @foreach($images as $image)
            <div class="flex-shrink-0 snap-start">
                <img
                    src="{{ Storage::url($image->image_path) }}"
                    alt="Post image"
                    class="h-80 w-auto rounded-xl object-cover cursor-pointer"
                    @click="window.dispatchEvent(new CustomEvent('lightbox-open', { detail: { images: {{ Js::from($imageUrls) }}, index: {{ $loop->index }} } }))"
                >
            </div>
        @endforeach
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
