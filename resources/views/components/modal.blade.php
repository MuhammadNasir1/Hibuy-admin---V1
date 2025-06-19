<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex justify-center items-center w-full bg-gray-800 bg-opacity-50">
    <div class="relative p-4 w-full {{ $modal_width }} max-h-[90vh] ">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 text-white border-b border-gray-200 md:p-5 bg-primary rounded-t-md">
                <h3 class="text-xl font-semibold">
                    {{ $title }}
                </h3>
                <button type="button"
                    class="inline-flex items-center justify-center w-8 h-8 text-sm text-white bg-transparent close-modal ms-auto"
                    data-modal-hide="{{ $id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 overflow-y-auto max-h-[70vh]">
                {{ $body }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', function(event) {
        if (event.target.closest('[data-modal-target="{{ $id }}"]')) {
            const modal = document.getElementById('{{ $id }}');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');

            }
        }
        if (event.target.closest('[data-modal-hide="{{ $id }}"]')) {
            const modal = document.getElementById('{{ $id }}');
            if (modal) {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            }
        }
    });
    </script>
