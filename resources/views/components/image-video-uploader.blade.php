@props([
    'id' => 'media',
    'name' => 'media',
    'type' => 'image', // 'image' or 'video'
    'preview' => '/asset/media_1.png',
    'previewClass' => 'w-64 h-48',
])

<div class="mb-4">
    <!-- Hidden file input -->
    <input type="file" id="{{ $id }}" name="{{ $name }}" accept="{{ $type }}/*"
        class="hidden" />

    <!-- Preview label (clickable area) -->
    <label for="{{ $id }}" class="cursor-pointer">
        <div class="relative inline-block border rounded overflow-hidden bg-gray-100 {{ $previewClass }}">
            <!-- Image preview -->
            <img id="{{ $id }}ImagePreview" src="{{ $preview ?: asset('asset/media (1).png') }}" alt="Preview"
                class="w-full h-full object-cover {{ $type === 'video' ? 'hidden' : '' }}" />

            <!-- Video preview -->
            <div id="{{ $id }}VideoWrapper"
                class="absolute top-0 left-0 w-full h-full {{ $type === 'video' ? '' : 'hidden' }}">
                <video id="{{ $id }}VideoPreview" src="{{ $preview }}"
                    class="w-full h-full object-cover" muted playsinline preload="metadata" controls></video>
            </div>

            @if ($type === 'video')
                <!-- 🖉 Edit icon (clickable) -->
                <div id="{{ $id }}EditButton"
                    class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded z-20 hover:bg-black cursor-pointer">
                    🖉 Edit
                </div>
            @endif
        </div>
    </label>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById(@json($id));
        const imagePreview = document.getElementById(@json($id . 'ImagePreview'));
        const videoPreview = document.getElementById(@json($id . 'VideoPreview'));
        const videoWrapper = document.getElementById(@json($id . 'VideoWrapper'));
        const editButton = document.getElementById(@json($id . 'EditButton'));

        if (editButton) {
            editButton.addEventListener("click", function(e) {
                e.preventDefault();
                input.click();
            });
        }

        input.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const url = URL.createObjectURL(file);

            if (file.type.startsWith('image/')) {
                imagePreview.classList.remove('hidden');
                videoWrapper.classList.add('hidden');
                imagePreview.src = url;
            } else if (file.type.startsWith('video/')) {
                imagePreview.classList.add('hidden');
                videoWrapper.classList.remove('hidden');
                videoPreview.src = url;
                videoPreview.load();
            }
        });
    });
</script>
