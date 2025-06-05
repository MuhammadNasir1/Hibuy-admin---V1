@extends('layout')
@section('title', 'Inquiries')
@section('nav-title', 'Inquiries')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Inquiries List</h1>
        </div>
        @php
            $headers = [
                'Sr.',
                'Product',
                'Buyer',
                'Quantity',
                'Bill Amount',
                '20% Amount',
                'Date',
                'Payement SS',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                @foreach ($inquiries as $index => $inquiry)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $inquiry->product->product_name ?? 'N/A' }}</td>
                        <td>{{ $inquiry->buyer_name ?? 'N/A' }}</td>
                        <td>{{ $inquiry->product_stock ?? 'N/A' }}</td>
                        <td>Rs {{ number_format($inquiry->amount, 2) }}</td>
                        <td>Rs {{ number_format($inquiry->twenty_percent_amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('M d, Y') }}</td>
                        <td>

                            @if ($inquiry->payment_ss)
                                <img class="rounded-lg w-11 h-11" src="{{ asset('storage/' . $inquiry->payment_ss) }}"
                                    alt="Payment Screenshot">
                            @else
                                <img class="rounded-lg w-11 h-11" src=" {{ asset('asset/upload.png') }}" alt="Product Image">
                            @endif

                        </td>
                        <td>
                            @php
                                $statusClass = match ($inquiry->status) {
                                    'approved' => 'bg-green-500',
                                    'Pending' => 'bg-yellow-500',
                                    'rejected' => 'bg-red-500',
                                    default => 'bg-gray-500',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded {{ $statusClass }}">
                                {{ $inquiry->status }}
                            </span>
                        </td>
                        <td>
                            <span class='flex gap-4'>
                                <button class="viewModalBtn" data-modal-target="purchases-modal"
                                    data-modal-toggle="purchases-modal" data-inquiry-id="{{ $inquiry->inquiry_id }}">
                                    <svg width='37' height='36' viewBox='0 0 37 36' fill='none'
                                        xmlns='http://www.w3.org/2000/svg'>
                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                            d='M28.0642 18.5C28.0642 18.126 27.8621 17.8812 27.4579 17.3896C25.9788 15.5938 22.7163 12.25 18.9288 12.25C15.1413 12.25 11.8788 15.5938 10.3996 17.3896C9.99542 17.8812 9.79333 18.126 9.79333 18.5C9.79333 18.874 9.99542 19.1187 10.3996 19.6104C11.8788 21.4062 15.1413 24.75 18.9288 24.75C22.7163 24.75 25.9788 21.4062 27.4579 19.6104C27.8621 19.1187 28.0642 18.874 28.0642 18.5ZM18.9288 21.625C19.7576 21.625 20.5524 21.2958 21.1385 20.7097C21.7245 20.1237 22.0538 19.3288 22.0538 18.5C22.0538 17.6712 21.7245 16.8763 21.1385 16.2903C20.5524 15.7042 19.7576 15.375 18.9288 15.375C18.0999 15.375 17.3051 15.7042 16.719 16.2903C16.133 16.8763 15.8038 17.6712 15.8038 18.5C15.8038 19.3288 16.133 20.1237 16.719 20.7097C17.3051 21.2958 18.0999 21.625 18.9288 21.625Z'
                                            fill='url(#paint0_linear_872_5570)' />
                                        <circle opacity='0.1' cx='18.4287' cy='18' r='18'
                                            fill='url(#paint1_linear_872_5570)' />
                                        <defs>
                                            <linearGradient id='paint0_linear_872_5570' x1='18.9288' y1='12.25'
                                                x2='18.9288' y2='24.75' gradientUnits='userSpaceOnUse'>
                                                <stop stop-color='#FCB376' />
                                                <stop offset='1' stop-color='#FE8A29' />
                                            </linearGradient>
                                            <linearGradient id='paint1_linear_872_5570' x1='18.4287' y1='0'
                                                x2='18.4287' y2='36' gradientUnits='userSpaceOnUse'>
                                                <stop stop-color='#FCB376' />
                                                <stop offset='1' stop-color='#FE8A29' />F
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </button>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <x-modal id="purchases-modal">
            <x-slot name="title">Inquiry Details</x-slot>
            <x-slot name="modal_width">max-w-4xl w-full sm:w-11/12 md:w-10/12 lg:w-9/12</x-slot>
            <x-slot name="body">
                <div class="font-sans">
                    <!-- Image Section -->
                    <div class="flex flex-col sm:flex-row items-start gap-4 p-4 sm:p-6">
                        <!-- Main Image -->
                        <div class="w-full sm:w-1/3">
                            <div class="h-48 sm:h-64 bg-gray-100 border-2 border-gray-200 rounded-md overflow-hidden">
                                <img id="main-image" src="{{ asset('asset/Ellipse 2.png') }}" alt="Main Product Image"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                        <!-- Sub Images -->
                        <div class="grid flex-1 grid-cols-2 gap-4 sm:grid-cols-4">
                            <div class="h-20 sm:h-24 bg-gray-100 border-2 border-gray-200 rounded-md overflow-hidden">
                                <img id="sub-image-1" src="{{ asset('asset/Ellipse 2.png') }}" alt="Sub Image 1"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="h-20 sm:h-24 bg-gray-100 border-2 border-gray-200 rounded-md overflow-hidden">
                                <img id="sub-image-2" src="{{ asset('asset/Ellipse 2.png') }}" alt="Sub Image 2"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="h-20 sm:h-24 bg-gray-100 border-2 border-gray-200 rounded-md overflow-hidden">
                                <img id="sub-image-3" src="{{ asset('asset/Ellipse 2.png') }}" alt="Sub Image 3"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="h-20 sm:h-24 bg-gray-100 border-2 border-gray-200 rounded-md overflow-hidden">
                                <img id="sub-image-4" src="{{ asset('asset/Ellipse 2.png') }}" alt="Sub Image 4"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-4 sm:p-6 ">
                        <!-- Title and Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <div class="w-24 sm:w-32 text-sm font-medium text-gray-800">Title</div>
                                    <div id="product_name"
                                        class="text-base sm:text-lg font-semibold text-gray-900 capitalize"></div>
                                </div>
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <div class="w-24 sm:w-32 text-sm font-medium text-gray-800">Rating</div>
                                    <div id="product_rating"
                                        class="text-base sm:text-lg font-semibold text-gray-900 capitalize">4.0</div>
                                </div>
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <div class="w-24 sm:w-32 text-sm font-medium text-gray-800">Brand</div>
                                    <div id="brand_name"
                                        class="text-base font-semibold sm:text-lg text-gray-900 capitalize"></div>
                                </div>
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <div class="w-24 sm:w-32 text-sm font-medium text-gray-800">Price</div>
                                    <div id="product_price" class="text-base sm:text-lg text-gray-900 font-semibold"></div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <div class="w-24 sm:w-32 text-sm font-medium text-gray-800">Category</div>
                                    <div id="product_category" class="text-base sm:text-lg text-gray-900 font-semibold">
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <div class="w-24 sm:w-32 text-sm font-medium text-gray-800">Whole Sale</div>
                                    <div id="whole-sale" class="text-base sm:text-lg font-semibold text-gray-900"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Variations -->
                        <div class="mt-4 sm:mt-6">
                            <div id="product_variations" class="text-gray-800 text-sm font-medium"></div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4 sm:mt-6">
                            <div class="flex flex-col gap-2">
                                <div class="text-sm font-medium text-gray-800">Description</div>
                                <div id="product_description"
                                    class="text-base sm:text-lg text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-md capitalize">
                                </div>
                            </div>
                        </div>

                        <form id="statusForm" method="POST">
                            @csrf
                            <input type="hidden" name="inquiry_id" id="inquiry_id" value="">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4 items-center justify-center">
                                <div class="flex items-center gap-4 sm:gap-6">
                                    <label class="block text-sm font-semibold text-gray-800">Purchase
                                        <br> Stock</label>
                                    <input placeholder="Enter Here" type="text" id="purchase_stock"
                                        name="purchase_stock"
                                        class="w-full sm:w-[200px] p-2 mt-1 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        readonly>
                                </div>
                                <div>
                                    <div class="flex items-center gap-4 sm:gap-6">
                                        <div class="text-sm font-semibold text-gray-800">Price</div>
                                        <div id="price_formula" class="text-base font-semibold sm:text-lg text-gray-900">
                                            $0 (0%: 0)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex w-full gap-14 mt-2">
                                <label class="block text-sm  font-semibold text-gray-800">Note
                                </label>
                                <input placeholder="Enter Here" type="text" id="note"
                                    class="w-full border border-gray-400 rounded-lg  " readonly>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex gap-8 pt-4 mr-20">
                                    <label class="block text-sm font-semibold text-gray-800">Amount SS(20%)</label>
                                    <img id="payment_ss_image" class="w-full h-full object-cover" src=""
                                        alt="">
                                </div>

                                <div class="pt-4">
                                    <div class="flex  gap-2  items-center">
                                        <label class="block text-sm font-semibold text-gray-800">Status</label>
                                        <select id="status" name="status"
                                            class="w-full p-2 border border-gray-400 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-200 rounded-b-lg mt-4 sm:mt-6">
                                <div class="flex flex-col sm:flex-row items-center justify-between p-4">
                                    <button type="button" data-modal-hide="purchases-modal"
                                        class="w-full sm:w-auto text-sm font-medium px-4 py-2 text-gray-800 bg-gray-300 border-2 border-gray-400 rounded-full hover:bg-gray-400 transition">
                                        Close
                                    </button>
                                    <button type="submit" form="statusForm"
                                        class="w-full sm:w-auto mt-2 sm:mt-0 text-sm font-medium px-4 py-2 text-white bg-blue-600 rounded-full hover:bg-blue-700 transition">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-slot>
        </x-modal>

    </div>


@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $('.viewModalBtn').on('click', function() {
                const inquiryId = $(this).data('inquiry-id');
                $('#inquiry_id').val(inquiryId);
                console.log('Set inquiry_id:', inquiryId);
            });


            $('.viewModalBtn').on('click', function() {
                const inquiryId = $(this).data('inquiry-id');
                const url = `/inquiry-details/${inquiryId}`;

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        console.log('Inquiry Details Response:', response);

                        if (response.success) {
                            const data = response.data;

                            // Update basic info
                            $('#product_name').text(data.product_name || 'N/A');
                            $('#product_description').text(data.product_description || 'N/A');
                            $('#brand_name').text(data.product_brand || 'N/A');
                            $('#product_price').text(data.product_price ?
                                `$${data.product_price}` : 'N/A');
                            $('#product_category').text(data.category_name || 'N/A');
                            $('#whole-sale').text(data.product_discounted_price ?
                                `$${data.product_discounted_price}` : 'N/A');
                            $('#price_formula').text(
                                data.product_price && data.product_stock ?
                                `$${data.product_price * data.product_stock} (20%: $${data.twenty_percent_amount || 0})` :
                                '$0 (0%: 0)'
                            );
                            $('#purchase_stock').val(data.product_stock || '');
                            $('#note').val(data.note || '');
                            $('#status').val(data.status).trigger('change');

                            // Update payment_ss image
                            const paymentSsPath = data.payment_ss ?
                                `/storage/${data.payment_ss}` :
                                `/asset/media_1.png`;
                            $('#payment_ss_image').attr('src', paymentSsPath);
                            console.log('Payment SS Path:', paymentSsPath);

                            // Update product images
                            let images = data.product_images || [];
                            if (typeof images === 'string') {
                                try {
                                    images = JSON.parse(images);
                                } catch (e) {
                                    console.error('Error parsing product_images:', e);
                                    images = [];
                                }
                            }
                            if (!Array.isArray(images)) images = [];

                            const defaultImage = '/asset/Ellipse 2.png';
                            // Remove duplicate 'storage/' if present
                            const cleanImages = images.map(img => {
                                if (img.startsWith('storage/')) {
                                    return img.replace('storage/', '');
                                }
                                return img;
                            });

                            $("#main-image").attr("src", cleanImages[0] ?
                                `/storage/${cleanImages[0]}` : defaultImage);
                            $("#sub-image-1").attr("src", cleanImages[1] ?
                                `/storage/${cleanImages[1]}` : defaultImage);
                            $("#sub-image-2").attr("src", cleanImages[2] ?
                                `/storage/${cleanImages[2]}` : defaultImage);
                            $("#sub-image-3").attr("src", cleanImages[3] ?
                                `/storage/${cleanImages[3]}` : defaultImage);
                            $("#sub-image-4").attr("src", cleanImages[4] ?
                                `/storage/${cleanImages[4]}` : defaultImage);

                            console.log('Product Images Paths:', cleanImages.map(img =>
                                `/storage/${img}`));

                            // Variations
                            let variationsHtml = '';
                            const parentGrouped = {};
                            const childGrouped = {};

                            if (data.product_variation && Array.isArray(data
                                    .product_variation)) {
                                data.product_variation.forEach(variation => {
                                    const parentKey = variation.parent_option_name ||
                                        'Option';
                                    if (!parentGrouped[parentKey]) parentGrouped[
                                        parentKey] = [];
                                    parentGrouped[parentKey].push(variation.parentname);

                                    if (variation.children && Array.isArray(variation
                                            .children)) {
                                        variation.children.forEach(child => {
                                            const childKey = child
                                                .child_option_name ||
                                                'Child Option';
                                            if (!childGrouped[childKey])
                                                childGrouped[childKey] = [];
                                            if (!childGrouped[childKey]
                                                .includes(child.name)) {
                                                childGrouped[childKey].push(
                                                    child.name);
                                            }
                                        });
                                    }
                                });
                            }

                            for (let key in parentGrouped) {
                                variationsHtml += `
                                        <div class="mb-4 flex items-center gap-6">
                                            <div class="text-sm font-medium text-gray-500 mb-1">${key}</div>
                                            <div class="flex flex-wrap gap-2">
                                    `;
                                parentGrouped[key].forEach(value => {
                                    variationsHtml += `
                                            <span class="px-3 py-1 text-sm border rounded-full bg-white text-black shadow-sm">${value}</span>
                                        `;
                                });
                                variationsHtml += `</div></div>`;
                            }

                            for (let key in childGrouped) {
                                variationsHtml += `
                                        <div class="mb-4 flex items-center gap-6">
                                            <div class="text-sm font-medium text-gray-500 mb-1">${key}</div>
                                            <div class="flex flex-wrap gap-2">
                                    `;
                                childGrouped[key].forEach(value => {
                                    variationsHtml += `
                                            <span class="px-3 py-1 text-sm border rounded-full bg-white text-black shadow-sm">${value}</span>
                                        `;
                                });
                                variationsHtml += `</div></div>`;
                            }

                            $('#product_variations').html(variationsHtml);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Product not found.'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching inquiry details:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch product details.'
                        });
                    }
                });
            });


            $('#statusForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const inquiryId = $('#inquiry_id').val();
                const status = $('#status').val();

                if (!inquiryId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Inquiry ID is missing. Please try again.'
                    });
                    return;
                }

                if (!status) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select a status.'
                    });
                    return;
                }

                $.ajax({
                    url: '/update-inquiry-status',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Status updated successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#purchases-modal').addClass('hidden');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to update status.'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Status Update Error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'An error occurred while updating the status: ' + (xhr
                                .responseJSON?.message || 'Unknown error')
                        });
                    }
                });
            });
        });
    </script>
@endsection
