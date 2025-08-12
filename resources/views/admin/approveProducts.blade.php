@extends('layout')
@section('title', 'Approve Products')
@section('nav-title', 'Approve Products')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="px-5">
            <h2 class="text-2xl font-medium ">
                Store List
            </h2>
            <div class="my-5">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                    <li class="me-2">
                        <a href="#" class="inline-block px-4 py-1 text-white bg-primary rounded-3xl active"
                            aria-current="page">All</a>
                    </li>
                    <li class="me-2">
                        <a href="#"
                            class="inline-block border px-4 py-1 rounded-3xl hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Wholesale</a>
                    </li>
                </ul>
            </div>
        </div>
        @php
            $headers = [
                'Sr.',
                'Store Name',
                'Seller Name',
                'Phone Number',
                'Email',
                'Address',
                'Store Type',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @foreach ($stores as $index => $store)
                    @php
                        $storeInfo = json_decode($store->store_info, true);
                        $sellerInfo = json_decode($store->seller->personal_info, true);
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $storeInfo['store_name'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $sellerInfo['full_name'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $storeInfo['phone_no'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $storeInfo['email'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $storeInfo['address'] ?? 'N/A' }}, {{ $storeInfo['city'] ?? '' }},
                            {{ $storeInfo['province'] ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $storeInfo['type'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if ($store->store_status == 1)
                                <span
                                    class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Active</span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <button onclick="viewStoreDetails({{ $store->store_id }})"
                                class="text-blue-600 hover:text-blue-900 mr-2">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>



        <x-modal id="store-modal">
            <x-slot name="title">Store Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>

            <x-slot name="body">
                <div class="p-6 space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                            <tbody>
                                <tr>
                                    <td class="p-3 font-semibold">Store ID:</td>
                                    <td class="p-3" id="store-id"></td>
                                    <td class="p-3 font-semibold">Store Name:</td>
                                    <td class="p-3" id="store-name"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Seller Name:</td>
                                    <td class="p-3" id="seller-name"></td>
                                    <td class="p-3 font-semibold">Phone Number:</td>
                                    <td class="p-3" id="store-phone"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Email:</td>
                                    <td class="p-3" id="store-email"></td>
                                    <td class="p-3 font-semibold">Store Type:</td>
                                    <td class="p-3" id="store-type"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Address:</td>
                                    <td class="p-3" id="store-address" colspan="3"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Status:</td>
                                    <td class="p-3" id="store-status"></td>
                                    <td class="p-3 font-semibold">Created Date:</td>
                                    <td class="p-3" id="store-created"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Products:</td>
                                    <td class="p-3" colspan="3">
                                        <button id="toggle-products" class="text-blue-600 hover:text-blue-800 font-medium">
                                            <span id="toggle-text">Show Products</span>
                                            <svg id="toggle-icon"
                                                class="inline-block w-4 h-4 ml-1 transform transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Products Table Section -->
                    <div id="products-table-section" class="mt-4 hidden">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-700">Store Products</h3>
                            <button id="approve-selected-btn" class="px-3 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700 disabled:opacity-50" disabled>
                                Approve Selected
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-3 text-left border w-10">
                                            <input type="checkbox" id="select-all-products" />
                                        </th>
                                        <th class="p-3 text-left border">Sr.</th>
                                        <th class="p-3 text-left border">Product Name</th>
                                        <th class="p-3 text-left border">Brand</th>
                                        <th class="p-3 text-left border">Price</th>
                                        <th class="p-3 text-left border">Stock</th>
                                        <th class="p-3 text-left border">Status</th>
                                        <th class="p-3 text-left border">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table-body">
                                    <!-- Products will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Product Details Modal -->
                    <div id="product-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                                <div class="flex justify-between items-center p-4 border-b">
                                    <h3 class="text-lg font-semibold text-gray-700">Product Details</h3>
                                    <button onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Image View Modal -->
                                <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-[60]">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <div class="relative max-w-4xl max-h-[90vh]">
                                            <button onclick="closeImageModal()"
                                                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <img id="modal-image" src="" alt="Product Image"
                                                class="max-w-full max-h-[90vh] object-contain rounded-lg">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <!-- Product Basic Info -->
                                    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                                        <div>
                                            <span class="font-semibold">Product Name:</span>
                                            <span id="modal-product-name"></span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">Brand:</span>
                                            <span id="modal-product-brand"></span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">Price:</span>
                                            <span id="modal-product-price"></span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">Stock:</span>
                                            <span id="modal-product-stock"></span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">Status:</span>
                                            <span id="modal-product-status"></span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">Boosted:</span>
                                            <span id="modal-product-boosted"></span>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="font-semibold">Description:</span>
                                            <p id="modal-product-description" class="mt-1 text-gray-600"></p>
                                        </div>
                                    </div>

                                    <!-- Product Images Section -->
                                    <div class="mt-6 mb-6">
                                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Product Images</h4>
                                        <div id="product-images-gallery"
                                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                            <!-- Product images will be populated here -->
                                        </div>
                                    </div>

                                    <!-- Product Variations Section -->
                                    <div class="mt-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-lg font-semibold text-gray-700">Product Variations</h4>
                                            <button id="toggle-variations"
                                                class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                <span id="variations-toggle-text">Show Details</span>
                                                <svg id="variations-toggle-icon"
                                                    class="inline-block w-4 h-4 ml-1 transform transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Summary Cards -->
                             

                                        <!-- Detailed Table -->
                                        <div id="variations-details" class="hidden">
                                            <div class="overflow-x-auto">
                                                <table
                                                    class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                                                    <thead>
                                                        <tr class="bg-gray-100">
                                                            <th class="p-3 text-left border">Image</th>
                                                            <th class="p-3 text-left border">Product</th>
                                                            <th class="p-3 text-left border">Variation</th>
                                                            <th class="p-3 text-left border">Status</th>
                                                            <th class="p-3 text-left border">Qty</th>
                                                            <th class="p-3 text-left border">Weight/Size</th>
                                                            <th class="p-3 text-left border">U.Price</th>
                                                            <th class="p-3 text-left border">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="product-variations-body">
                                                        <!-- Variations will be populated here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </x-slot>
        </x-modal>
    </div>

    <button class="hidden" id="modal-btn" data-modal-target="store-modal" data-modal-toggle="store-modal"></button>
@endsection

@section('js')
    <!-- Toast container -->
    <div id="toast-container" class="fixed top-5 right-5 z-[70] space-y-2"></div>
    <script>
        // Track selected products
        let selectedProductIds = new Set();
        const approveEndpoint = "{{ route('admin.products.approve') }}";

        function viewStoreDetails(storeId) {
            // Find the store data from the current page data
            const stores = @json($stores);
            const store = stores.find(s => s.store_id === storeId);

            if (store) {
                const storeInfo = JSON.parse(store.store_info);
                const sellerInfo = JSON.parse(store.seller.personal_info);

                // Populate modal with store details
                document.getElementById('store-id').textContent = store.store_id;
                document.getElementById('store-name').textContent = storeInfo.store_name || 'N/A';
                document.getElementById('seller-name').textContent = sellerInfo.full_name || 'N/A';
                document.getElementById('store-phone').textContent = storeInfo.phone_no || 'N/A';
                document.getElementById('store-email').textContent = storeInfo.email || 'N/A';
                document.getElementById('store-type').textContent = storeInfo.type || 'N/A';
                document.getElementById('store-address').textContent =
                    `${storeInfo.address || 'N/A'}, ${storeInfo.city || ''}, ${storeInfo.province || ''}`;
                document.getElementById('store-status').textContent = store.store_status == 1 ? 'Active' : 'Inactive';
                document.getElementById('store-created').textContent = new Date(store.created_at).toLocaleDateString();

                // Populate products table
                populateProductsTable(store.products);

                // Open modal
                document.getElementById('modal-btn').click();
            }
        }

        function populateProductsTable(products) {
            const tableBody = document.getElementById('products-table-body');
            tableBody.innerHTML = '';

            // Reset selection
            selectedProductIds = new Set();
            const selectAll = document.getElementById('select-all-products');
            if (selectAll) selectAll.checked = false;

            if (products && products.length > 0) {
                products.forEach((product, index) => {
                    const row = document.createElement('tr');
                    row.className = 'border-b hover:bg-gray-50';

                    const statusBadge = product.product_status == 1 ?
                        '<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Active</span>' :
                        '<span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Inactive</span>';

                    row.innerHTML = `
                          <td class="p-3 border w-10">
                              <input type="checkbox" class="product-checkbox" value="${product.product_id}" />
                          </td>
                          <td class="p-3 border">${index + 1}</td>
                          <td class="p-3 border">${product.product_name || 'N/A'}</td>
                          <td class="p-3 border">${product.product_brand || 'N/A'}</td>
                          <td class="p-3 border">$${product.product_price || '0'}</td>
                          <td class="p-3 border">${product.product_stock || '0'}</td>
                          <td class="p-3 border">${statusBadge}</td>
                          <td class="p-3 border">
                              <button onclick="viewProductDetails(${product.product_id})" 
                                      class="text-blue-600 hover:text-blue-900 text-sm">
                                  View
                              </button>
                          </td>
                      `;
                    tableBody.appendChild(row);
                });

                // Wire up checkbox handlers
                tableBody.querySelectorAll('.product-checkbox').forEach(cb => {
                    cb.addEventListener('change', function() {
                        updateSelectedSet(this.value, this.checked);
                        syncSelectAllCheckbox();
                        toggleApproveBtn();
                    });
                });

                // Select all handler
                if (selectAll) {
                    selectAll.disabled = false;
                    selectAll.onchange = function() {
                        const checked = this.checked;
                        tableBody.querySelectorAll('.product-checkbox').forEach(cb => {
                            cb.checked = checked;
                            updateSelectedSet(cb.value, checked);
                        });
                        toggleApproveBtn();
                    };
                }
            } else {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="8" class="p-3 text-center text-gray-500 border">No products found</td>';
                tableBody.appendChild(row);
                if (selectAll) selectAll.disabled = true;
                toggleApproveBtn();
            }
        }

        function updateSelectedSet(id, isChecked) {
            if (isChecked) {
                selectedProductIds.add(String(id));
            } else {
                selectedProductIds.delete(String(id));
            }
        }

        function syncSelectAllCheckbox() {
            const selectAll = document.getElementById('select-all-products');
            if (!selectAll) return;
            const checkboxes = document.querySelectorAll('#products-table-body .product-checkbox');
            const allChecked = Array.from(checkboxes).length > 0 && Array.from(checkboxes).every(cb => cb.checked);
            selectAll.checked = allChecked;
        }

        // Helper to read selected product IDs elsewhere
        function getSelectedProductIds() {
            return Array.from(selectedProductIds);
        }

        function toggleApproveBtn() {
            const btn = document.getElementById('approve-selected-btn');
            if (btn) btn.disabled = selectedProductIds.size === 0;
        }

        async function approveSelectedProducts() {
            const ids = getSelectedProductIds();
            if (ids.length === 0) return;

            try {
                const btn = document.getElementById('approve-selected-btn');
                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = 'Approving...';
                const res = await fetch(approveEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ product_ids: ids })
                });
                const data = await res.json();
                if (data.success) {
                    // Remove approved rows from the table
                    const tableBody = document.getElementById('products-table-body');
                    ids.forEach(id => {
                        const cb = tableBody.querySelector(`.product-checkbox[value="${id}"]`);
                        if (cb) {
                            const row = cb.closest('tr');
                            if (row) row.remove();
                        }
                        selectedProductIds.delete(String(id));
                    });
                    syncSelectAllCheckbox();
                    toggleApproveBtn();
                    showToast(`Approved ${data.updated_count} product(s) successfully.`, 'success');

                    // If table is empty, render empty row and disable select-all
                    if (tableBody.children.length === 0) {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="8" class="p-3 text-center text-gray-500 border">No products found</td>';
                        tableBody.appendChild(row);
                        const selectAll = document.getElementById('select-all-products');
                        if (selectAll) selectAll.disabled = true;
                    }
                } else {
                    showToast('Failed to approve products.', 'error');
                }
                btn.textContent = originalText;
                btn.disabled = selectedProductIds.size === 0;
            } catch (e) {
                console.error(e);
                showToast('Error while approving products.', 'error');
                const btn = document.getElementById('approve-selected-btn');
                btn.textContent = 'Approve Selected';
                btn.disabled = selectedProductIds.size === 0;
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const base = 'rounded-md shadow px-4 py-3 text-sm flex items-center gap-2 border';
            const styles = type === 'success'
                ? 'bg-green-50 text-green-800 border-green-200'
                : 'bg-red-50 text-red-800 border-red-200';
            toast.className = `${base} ${styles}`;
            toast.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"></path>
                </svg>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function viewProductDetails(productId) {
            const stores = @json($stores);
            let selectedProduct = null;

            // Find the product in any store
            for (const store of stores) {
                if (store.products) {
                    selectedProduct = store.products.find(p => p.product_id == productId);
                    if (selectedProduct) break;
                }
            }

            if (selectedProduct) {
                // Populate basic product info
                document.getElementById('modal-product-name').textContent = selectedProduct.product_name || 'N/A';
                document.getElementById('modal-product-brand').textContent = selectedProduct.product_brand || 'N/A';
                document.getElementById('modal-product-price').textContent = `$${selectedProduct.product_price || '0'}`;
                document.getElementById('modal-product-stock').textContent = selectedProduct.product_stock || '0';
                document.getElementById('modal-product-status').textContent = selectedProduct.product_status == 1 ?
                    'Active' : 'Inactive';
                document.getElementById('modal-product-boosted').textContent = selectedProduct.is_boosted == 1 ? 'Yes' :
                    'No';
                document.getElementById('modal-product-description').textContent = selectedProduct.product_description ||
                    'N/A';

                // Populate product images
                populateProductImages(selectedProduct);

                // Populate product variations
                populateProductVariations(selectedProduct);

                document.getElementById('product-details-modal').classList.remove('hidden');
            }
        }

        function populateProductImages(product) {
            const gallery = document.getElementById('product-images-gallery');
            gallery.innerHTML = '';

            // Parse product images
            let images = [];
            if (product.product_images) {
                try {
                    images = JSON.parse(product.product_images);
                } catch (e) {
                    console.log('Error parsing product images:', e);
                }
            }

            if (images && images.length > 0) {
                images.forEach((imageUrl, index) => {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'relative group';
                    imageDiv.innerHTML = `
                            <img src="${imageUrl}" alt="Product Image ${index + 1}" 
                                 class="w-full h-32 object-cover rounded-lg border cursor-pointer hover:opacity-90 transition-opacity"
                                 onclick="openImageModal('${imageUrl}')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                        `;
                    gallery.appendChild(imageDiv);
                });
            } else {
                // Show default image if no images available
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                imageDiv.innerHTML = `
                        <img src="/asset/defualt-image.png" alt="Default Product Image" 
                             class="w-full h-32 object-cover rounded-lg border opacity-50">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">No images available</span>
                        </div>
                    `;
                gallery.appendChild(imageDiv);
            }
        }

        function populateProductVariations(product) {
            const variationsBody = document.getElementById('product-variations-body');
            variationsBody.innerHTML = '';

            // Parse product variations
            let variations = [];
            if (product.product_variation) {
                try {
                    variations = JSON.parse(product.product_variation);
                } catch (e) {
                    console.log('Error parsing product variations:', e);
                }
            }

            // Parse product images
            let images = [];
            if (product.product_images) {
                try {
                    images = JSON.parse(product.product_images);
                } catch (e) {
                    console.log('Error parsing product images:', e);
                }
            }

            if (variations && variations.length > 0) {
                // Create summary cards
                const totalVariations = variations.length;
                const totalStock = variations.reduce((sum, v) => sum + (parseInt(v.stock) || 0), 0);
                const avgPrice = variations.reduce((sum, v) => sum + (parseFloat(v.price) || 0), 0) / totalVariations;

                // Summary Card 1: Total Variations
            
                // Create detailed table rows
                variations.forEach((variation, index) => {
                    const row = document.createElement('tr');
                    row.className = 'border-b hover:bg-gray-50';

                    // Get image for this variation
                    const imageUrl = images && images.length > 0 ? images[0] : '/asset/defualt-image.png';

                    const statusBadge = product.product_status == 1 ?
                        '<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Active</span>' :
                        '<span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Inactive</span>';

                    const variationPrice = variation.price || product.product_price || '0';
                    const variationStock = variation.stock || product.product_stock || '0';
                    const subtotal = (parseFloat(variationPrice) * parseInt(variationStock)).toFixed(2);

                    row.innerHTML = `
                            <td class="p-3 border">
                                <img src="${imageUrl}" alt="Product Image" class="w-12 h-12 object-cover rounded-full border">
                            </td>
                            <td class="p-3 border font-medium">${product.product_name || 'N/A'}</td>
                            <td class="p-3 border">
                                <div class="text-sm">
                                    ${formatVariationText(variation)}
                                </div>
                            </td>
                            <td class="p-3 border">${statusBadge}</td>
                            <td class="p-3 border text-center">${variationStock}</td>
                            <td class="p-3 border text-center">${variation.weight || '0'} / ${variation.size || 'Default'}</td>
                            <td class="p-3 border text-right">$${variationPrice}</td>
                            <td class="p-3 border text-right font-medium">$${subtotal}</td>
                        `;
                    variationsBody.appendChild(row);
                });
            } else {
                // If no variations, show the main product as a single row
                const imageUrl = images && images.length > 0 ? images[0] : '/asset/defualt-image.png';
                const statusBadge = product.product_status == 1 ?
                    '<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Active</span>' :
                    '<span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Inactive</span>';

                // Create summary card for single product
                const summaryCard = document.createElement('div');
                summaryCard.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4 col-span-3';
                summaryCard.innerHTML = `
                        <div class="flex items-center justify-center">
                            <div class="p-2 bg-gray-100 rounded-full">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Single Product</p>
                                <p class="text-lg font-bold text-gray-900">No variations available</p>
                            </div>
                        </div>
                    `;
                summaryContainer.appendChild(summaryCard);

                const row = document.createElement('tr');
                row.className = 'border-b hover:bg-gray-50';
                const subtotal = (parseFloat(product.product_price || '0') * parseInt(product.product_stock || '0'))
                    .toFixed(2);

                row.innerHTML = `
                        <td class="p-3 border">
                            <img src="${imageUrl}" alt="Product Image" class="w-12 h-12 object-cover rounded-full border">
                        </td>
                        <td class="p-3 border font-medium">${product.product_name || 'N/A'}</td>
                        <td class="p-3 border">
                            <div class="text-sm">
                                Color: Default<br>
                                Size: Default
                            </div>
                        </td>
                        <td class="p-3 border">${statusBadge}</td>
                        <td class="p-3 border text-center">${product.product_stock || '0'}</td>
                        <td class="p-3 border text-center">0 / Default</td>
                        <td class="p-3 border text-right">$${product.product_price || '0'}</td>
                        <td class="p-3 border text-right font-medium">$${subtotal}</td>
                    `;
                variationsBody.appendChild(row);
            }
        }

        function formatVariationText(variation) {
            let text = '';
            if (variation.color) {
                text += `Color: ${variation.color}<br>`;
            }
            if (variation.size) {
                text += `Size: ${variation.size}`;
            }
            if (!variation.color && !variation.size) {
                text = 'Color: Default<br>Size: Default';
            }
            return text;
        }

        function closeProductModal() {
            document.getElementById('product-details-modal').classList.add('hidden');
        }

        function openImageModal(imageUrl) {
            document.getElementById('modal-image').src = imageUrl;
            document.getElementById('image-modal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('image-modal').classList.add('hidden');
        }

        // Add event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle products table
            document.getElementById('toggle-products').addEventListener('click', function() {
                const productsSection = document.getElementById('products-table-section');
                const toggleText = document.getElementById('toggle-text');
                const toggleIcon = document.getElementById('toggle-icon');

                if (productsSection.classList.contains('hidden')) {
                    productsSection.classList.remove('hidden');
                    toggleText.textContent = 'Hide Products';
                    toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    productsSection.classList.add('hidden');
                    toggleText.textContent = 'Show Products';
                    toggleIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Close product modal when clicking outside
            document.getElementById('product-details-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeProductModal();
                }
            });

            // Close image modal when clicking outside
            document.getElementById('image-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });

            // Toggle variations details
            document.getElementById('toggle-variations').addEventListener('click', function() {
                const detailsSection = document.getElementById('variations-details');
                const toggleText = document.getElementById('variations-toggle-text');
                const toggleIcon = document.getElementById('variations-toggle-icon');

                if (detailsSection.classList.contains('hidden')) {
                    detailsSection.classList.remove('hidden');
                    toggleText.textContent = 'Hide Details';
                    toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    detailsSection.classList.add('hidden');
                    toggleText.textContent = 'Show Details';
                    toggleIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Approve Selected button
            document.getElementById('approve-selected-btn').addEventListener('click', function() {
                approveSelectedProducts();
            });
        });
    </script>
@endsection
