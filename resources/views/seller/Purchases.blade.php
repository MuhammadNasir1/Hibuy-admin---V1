@extends('layout')
@section('title', 'Purchases')
@section('nav-title', 'Purchases')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Purchases List</h1>
        </div>
        @php
            $headers = [
                'Sr.',
                'Image',
                'Title',
                'Category',
                'Seller',
                'Bill Amount',
                'Date',
                'Payment SS',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                @foreach ($inquiries as $index => $inquiry)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @php
                                $images = $inquiry->product->product_images;
                                $imageUrl = 'asset/Ellipse 2.png';

                                if (!empty($images)) {
                                    $decoded = json_decode($images);
                                    if (json_last_error() === JSON_ERROR_NONE && !empty($decoded[0])) {
                                        $imageUrl = asset($decoded[0]);
                                    } else {
                                        $imageUrl = asset('asset/Ellipse 2.png');
                                    }
                                } else {
                                    $imageUrl = asset('asset/Ellipse 2.png');
                                }
                            @endphp

                            <img class="rounded-full w-11 h-11" src="{{ $imageUrl }}" alt="Product Image">

                        </td>
                        <td>{{ $inquiry->product->product_name ?? 'N/A' }}</td>
                        <td>{{ $inquiry->product->category->name ?? 'N/A' }}</td>
                        <td>{{ $inquiry->seller->user_name ?? 'N/A' }}</td>
                        <td>Rs {{ number_format($inquiry->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('M d, Y') }}</td>
                        <td>
                            <button data-modal-target="payment-modal" data-modal-toggle="payment-modal"
                                data-inquiry-id="{{ $inquiry->inquiry_id }}" class="open-payment-modal">
                                @if ($inquiry->payment_ss)
                                    <img class="rounded-lg w-11 h-11" src="{{ asset('storage/' . $inquiry->payment_ss) }}"
                                        alt="Payment Screenshot">
                                @else
                                    <img class="rounded-lg w-11 h-11" src=" {{ asset('asset/upload.png') }}"
                                        alt="Product Image">
                                @endif
                            </button>

                        </td>
                        <td>
                            @php
                                $statusClass = match ($inquiry->status) {
                                    'approved' => 'bg-green-500',
                                    'pending' => 'bg-yellow-500',
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
            <x-slot name="title">Purchase Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="">
                    <form>

                        <div class="flex items-start gap-4 px-6">
                            <!-- Main Image -->
                            <div class="w-1/3">
                                <div class="h-48 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                    <img id="main-image" src="" alt="Main Product Image"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>
                            <!-- Sub Images -->
                            <div class="grid flex-1 grid-cols-2 gap-4 md:grid-cols-4">
                                <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                    <img id="sub-image-1" src="" alt="Sub Image 1"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                    <img id="sub-image-2" src="" alt="Sub Image 2"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                    <img id="sub-image-3" src="" alt="Sub Image 3"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                    <img id="sub-image-4" src="" alt="Sub Image 4"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div class="p-6">
                            <!-- Title -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="space-y-5">
                                    <div class="flex items-center gap-6">
                                        <div class="w-32 text-sm font-medium text-gray-800">Title</div>
                                        <div id="product_name" class="text-base font-semibold text-gray-900 capitalize">
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6">
                                        <div class="w-32 text-sm font-medium text-gray-800">Rating</div>
                                        <div id="product_rating" class="text-base font-semibold text-gray-900 capitalize">
                                            4.0</div>
                                    </div>

                                    <div class="flex items-center gap-6">
                                        <div class="w-32 text-sm font-medium text-gray-800">Brand</div>
                                        <div id="brand_name" class="text-base font-semibold text-gray-900 capitalize">
                                        </div>
                                    </div>

                                    {{-- <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Stock</div>
                                            <div id="stock" class="text-base text-gray-700 capitalize"></div>
                                        </div> --}}
                                    <div class="flex items-center gap-6">
                                        <div class="w-32 text-sm font-medium text-gray-800">Price</div>
                                        <div id="product_price" class="text-base text-gray-900 font-semibold">
                                        </div>
                                    </div>


                                </div>

                                <div class="space-y-5">
                                    {{-- <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Orders</div>
                                            <div id="Orders" class="text-base text-gray-700 capitalize">56</div>
                                        </div> --}}

                                    <div class="flex items-center gap-6">
                                        <div class="w-32 text-sm font-medium text-gray-800">Category</div>
                                        <div id="product_category" class="text-base font-semibold text-gray-900 ">
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6">
                                        <div class="w-32 text-sm font-medium text-gray-800">Whole Sale</div>
                                        <div id="whole-sale" class="text-base font-semibold text-gray-900"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variations -->
                            <div class="mt-6">
                                <div id="product_variations"\>
                                    <!-- Variations will be dynamically inserted here -->
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <div class="flex flex-col gap-2">
                                    <div class="text-sm font-medium text-gray-800">Description</div>
                                    <div id="product_description"
                                        class="text-base text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-md capitalize">

                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 mt-4">
                                <div class="flex items-center gap-6">
                                    <label class="block text-sm  font-semibold text-gray-800">Purchase
                                        <br>Stock</label>
                                    <input placeholder="Enter Here" type="text" id="purchase_stock"
                                        class="w-[200px] p-2 mt-1 border border-gray-400 rounded-lg " readonly>
                                </div>
                                <div>
                                    <div class="flex items-center gap-6 align-middle">
                                        <div class="text-sm font-semibold text-gray-800">Price</div>
                                        {{-- <div  class="text-gray-700">$100 (20%: 20)</div> --}}
                                        <div id="price_formula" class="text-base font-semibold text-gray-900">$0 (0%: 0)
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="flex w-full gap-14 mt-2">
                                <label class="block text-sm  font-medium text-gray-800">Note
                                </label>
                                <input placeholder="Enter Here" type="text" id="note"
                                    class="w-full border border-gray-400 rounded-lg  " readonly>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 ">
                                <div class="flex gap-8 pt-4 mr-14">
                                    <label class="text-sm font-semibold text-gray-800">Amount SS(20%)</label>
                                    <img id="payment_ss_image" class="w-full h-full object-cover rounded-lg"
                                        src="" alt="">
                                </div>

                                <div class="pt-4">
                                    <div class="flex items-center  gap-2">
                                        <label class="text-sm font-semibold text-gray-800">Status</label>

                                        <p id="status" class="p-2  rounded text-sm ">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- Modal footer -->
                <div class=" bg-gray-300 rounded-b-lg">
                    <div class="flex items-center justify-between p-2">
                        <button type="button" data-modal-hide="purchases-modal" type="button"
                            class=" ms-3 text-sm font-medium
                      px-3 py-1 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                            Close
                        </button>

                        {{-- <button type="button" type="submit"
                            class=" me-3 text-sm font-medium
                                     px-4 py-2 text-white bg-customblue  rounded-3xl">
                            Done
                        </button> --}}
                    </div>
                </div>
            </x-slot>
        </x-modal>
        <x-modal id="payment-modal">
            <x-slot name="title">Bank Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">

                <form id="transactionImageForm" method="POST" action="/save-Inquiry-image"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="inquiry_id" id="inquiry_id" value="">

                    <div class="md:pl-6">
                        <h3 class="text-lg font-semibold">Account Details</h3>
                        <p class="text-gray-700 mt-2">Send 20% Payment in the following Bank Account:</p>

                        <table class="w-full mt-2 border-collapse">
                            <tbody>
                                <tr>
                                    <td class="w-[25%] font-semibold py-2 pr-3">Account Name:</td>
                                    <td>
                                        <span class="px-2 py-1">HiBuy0</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-[25%] font-semibold py-2 pr-3">Account Number:</td>
                                    <td class="flex items-center gap-2">
                                        <span class="text-primary  px-2 py-1" id="accountNumber">1234567890</span>
                                        <button type="button" onclick="copyToClipboard()"
                                            class="bg-gray-200 px-2 py-1 text-sm rounded hover:bg-gray-300">
                                            Copy
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-[25%] font-semibold py-2 pr-3">Bank Name:</td>
                                    <td>
                                        <span class="px-2 py-1">Bank Name</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p class="mt-4 font-semibold">Attach screenshot after sending payment.</p>

                        <div class="flex items-center mt-2 gap-2">
                            <input type="file" name="transaction_image" class="border rounded px-2 text-sm"
                                accept="image/*">
                        </div>
                    </div>

                    <div class="mt-6 bg-gray-300 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <div></div>
                            <button type="submit" id="submitButton" class="px-6 py-2 text-white bg-primary rounded-3xl">
                                Done
                            </button>
                        </div>
                    </div>
                </form>

            </x-slot>
        </x-modal>

    </div>


@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // Copy to Clipboard
            window.copyToClipboard = function() {
                const accountNumber = $('#accountNumber').text().trim();
                navigator.clipboard.writeText(accountNumber).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Account number copied to clipboard!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Failed to copy account number.'
                    });
                });
            };

            // Set inquiry_id for payment modal
            $('.open-payment-modal').on('click', function() {
                const inquiryId = $(this).data('inquiry-id');
                $('#inquiry_id').val(inquiryId);
                console.log('Set inquiry_id:', inquiryId);
            });

            // Handle transaction image form submission
            $('#transactionImageForm').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const inquiryId = $('#inquiry_id').val();

                if (!inquiryId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Inquiry ID is missing. Please try again.'
                    });
                    return;
                }

                $('#submitButton').prop('disabled', true);

                $.ajax({
                    url: '/save-Inquiry-image',
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
                                text: 'Image uploaded successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#payment-modal').addClass('hidden');
                                form.reset();
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to upload image.'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: 'An error occurred while uploading the image: ' + (xhr
                                .responseJSON?.message || 'Unknown error')
                        });
                    },
                    complete: function() {
                        $('#submitButton').prop('disabled', false);
                    }
                });
            });

            // Handle view modal
            $(document).on('click', '.viewModalBtn', function() {
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
                            const status = data.status || 'N/A';
                            let statusClass = 'bg-gray-500'; // default

                            switch (status.toLowerCase()) {
                                case 'approved':
                                    statusClass = 'bg-green-500';
                                    break;
                                case 'pending':
                                    statusClass = 'bg-yellow-500';
                                    break;
                                case 'rejected':
                                    statusClass = 'bg-red-500';
                                    break;
                            }

                            const $statusElement = $('#status');

                            // Remove previous status classes
                            $statusElement.removeClass(
                                'bg-green-500 bg-yellow-500 bg-red-500 bg-gray-500');

                            // Add the new class and update the text
                            $statusElement
                                .addClass(statusClass)
                                .text(status);


                            // Update payment_ss image
                            const paymentSsPath = data.payment_ss ?
                                `/storage/${data.payment_ss}` :
                                `/asset/media_1.png`;
                            $('#payment_ss_image').attr('src', paymentSsPath);
                            // console.log('Payment SS Path:', paymentSsPath);

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
        });
    </script>

@endsection
