@props(['images'])

@php
    $imageUrls = $images->map(fn($img) => Storage::url($img->image_path))->toArray();
@endphp

<div class="mt-3 -mx-4">
    <div class="flex gap-1 overflow-x-auto snap-x snap-mandatory scrollbar-hide pl-4 pr-4">
        @foreach($images as $image)
            <div class="flex-shrink-0 snap-start {{ $loop->first ? '-ml-4' : '' }}">
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
