@php
    // $complaint may be available in the parent view
    $hasImage = isset($complaint) && !empty($complaint->image);
    $hasVideo = isset($complaint) && !empty($complaint->video);
@endphp

<div class="mb-3">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar (jpeg, png, jpg, gif, max 2MB)</label>
    @if($hasImage)
        <div class="mt-2 mb-2">
            <img src="{{ asset('storage/' . ltrim($complaint->image, '/')) }}" alt="Gambar Aduan" class="w-48 h-32 object-cover rounded shadow-sm">
        </div>
        <div class="text-xs text-gray-500 mb-2">(Imej sedia ada dipaparkan di atas - memilih fail baru akan menggantikan)</div>
    @endif
    <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-700">
</div>

<div class="mb-3">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Video (mp4, avi, mpeg, mov, max 10MB)</label>
    @if($hasVideo)
        <div class="mt-2 mb-2">
            <video controls class="w-64 h-36 rounded shadow-sm">
                <source src="{{ asset('storage/' . ltrim($complaint->video, '/')) }}" type="video/mp4">
            </video>
        </div>
        <div class="text-xs text-gray-500 mb-2">(Video sedia ada dipaparkan di atas - memilih fail baru akan menggantikan)</div>
    @endif
    <input type="file" name="video" class="mt-1 block w-full text-sm text-gray-700">
</div>
