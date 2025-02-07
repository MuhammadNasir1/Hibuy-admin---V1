<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden  fixed top-0 right-0 left-0 z-[200] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="fixed inset-0 transition-opacity">
        <div id="backdrop-{{ $id }}" class="absolute inset-0 opacity-75 bg-slate-800"></div>
    </div>
    <div class="relative p-4 w-full  {{ $modal_width }} max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white shadow rounded-2xl dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-center px-4 py-2 border-b rounded-t-2xl bg-customblue">
                <h3 class="font-semibold text-white text-md" id="modalTitle">{{ $title }}</h3>
                <button type="button"
                    class="end-2.5 text-white bg-transparent modalCloseBtn text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
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
            <div class="">
                {{ $body }}
            </div>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    // Handle close button click
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden'); // Hide the modal
            }
        });
    });

    // Handle backdrop click
    document.querySelectorAll('[id^="backdrop-"]').forEach(backdrop => {
        backdrop.addEventListener('click', function () {
            const modal = this.closest('.fixed');
            if (modal) {
                modal.classList.add('hidden'); // Hide the modal
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // Handle close button click
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden'); // Hide the modal
            }
        });
    });

    // Handle backdrop click
    document.querySelectorAll('[id^="backdrop-"]').forEach(backdrop => {
        backdrop.addEventListener('click', function () {
            const modal = this.closest('.fixed');
            if (modal) {
                modal.classList.add('hidden'); // Hide the modal
            }
        });
    });
});

    </script>
