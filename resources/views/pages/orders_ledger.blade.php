@extends('layout')
@section('title', 'Orders')
@section('nav-title', 'Orders')
@section('content')
    <style>
        @media print {
            @page {
                size: A4 portrait;
                /* A4 size */
                margin: 10mm;
                /* adjust margins */
            }

            body {
                overflow: visible !important;
                font-size: 8px !important;
            }

            html,
            body {
                height: auto !important;
            }

            .printArea {
                overflow: visible !important;
                max-height: none !important;
                height: auto !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                /* ensure equal width distribution */
            }

            td,
            th {
                padding: 2px 4px !important;
                word-wrap: break-word !important;
            }

            /* Hide Sr. No column (first) and Action column (last) */
            table th:first-child,
            table td:first-child,
            table th:last-child,
            table td:last-child {
                display: none !important;
            }

            /* Remove buttons, search, pagination etc. */
            button,
            .no-print,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none !important;
            }
        }
    </style>



    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">

        <div class="px-5">
            <form method="GET" action="{{ url()->current() }}" class="w-full">
                <div class="flex flex-col lg:flex-row lg:flex-nowrap items-end gap-4">

                    {{-- Store (Admin only) --}}
                    @if (session('user_details.user_role') === 'admin')
                        <div class="flex flex-col w-full lg:w-auto min-w-[200px]">
                            <label for="store_id" class="text-sm font-medium text-gray-700">Select Store</label>
                            <select name="store_id" id="store_id"
                                class="mt-1 h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Select Store</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->store_id }}"
                                        {{ request('store_id') == $store->store_id ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Order Status --}}
                    <div class="flex flex-col w-full lg:w-auto min-w-[150px]">
                        <label for="order_status" class="text-sm font-medium text-gray-700">Order Status</label>
                        <select id="order_status" name="order_status"
                            class="mt-1 h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select Status</option>
                            <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Shipped
                            </option>
                            <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>
                                Delivered</option>
                            <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled</option>
                            <option value="returned" {{ request('order_status') == 'returned' ? 'selected' : '' }}>Returned
                            </option>
                        </select>
                    </div>

                    {{-- From Date --}}
                    <div class="flex flex-col w-full lg:w-auto min-w-[100px]">
                        <label for="from_date" class="text-sm font-medium text-gray-700">From</label>
                        <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}"
                            class="mt-1 h-10 w-full rounded-md border px-3 text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- To Date --}}
                    <div class="flex flex-col w-full lg:w-auto min-w-[100px]">
                        <label for="to_date" class="text-sm font-medium text-gray-700">To</label>
                        <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}"
                            class="mt-1 h-10 w-full rounded-md border px-3 text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Apply --}}
                    <div class="flex flex-col w-full lg:w-auto">
                        <label class="text-sm font-medium invisible">Apply</label>
                        <button type="submit" id="apply_date_filter"
                            class="h-10 w-full lg:w-auto bg-primary text-white px-4 rounded-md text-sm">
                            Apply Filter
                        </button>
                    </div>

                    {{-- Clear --}}
                    @if (request('from_date') || request('to_date') || request('order_status') || request('store_id'))
                        <div class="flex flex-col w-full lg:w-auto">
                            <label class="text-sm font-medium invisible">Clear</label>
                            <a href="{{ url()->current() }}"
                                class="h-10 inline-flex items-center justify-center px-4 rounded-md text-sm text-red-600 border border-red-200 hover:bg-red-50">
                                Clear
                            </a>
                        </div>
                    @endif

                </div>
            </form>


            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5 mt-5 printArea">
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <h3 class="text-gray-500 text-sm font-medium">
                        {{ request('order_status') ? ucwords(str_replace('_', ' ', request('order_status'))) . ' Orders' : 'Total Orders' }}
                    </h3>
                    <p class="text-2xl font-bold text-primary">{{ count($orders) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <h3 class="text-gray-500 text-sm font-medium">Total Amount</h3>
                    <p class="text-2xl font-bold text-green-600">
                        Rs {{ number_format($orders->sum('grand_total'), 2) }}
                        | Rs {{ $totalNet = $orders->sum('net_total') }}
                    </p>
                </div>
            </div>
            <h2 class="text-2xl font-medium ">
                Orders List
            </h2>
            <div class="my-5 flex justify-between">
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
                <button onclick="printDiv()" class="inline-block px-4 py-1 text-white bg-primary rounded-3xl">
                    Print
                </button>
            </div>
        </div>
        <div class="printArea">
            @php
                $headers = [
                    'Sr.',
                    'ID / Track Number',
                    'Customer / Phone',
                    session('user_details.user_role') == 'admin' ? 'Seller / Phone' : 'Rider',
                    'Bill Amount',
                    '5% Less',
                    'Date',
                    'Delivery Status',
                    'Status',
                    'Action',
                ];
            @endphp

            <x-table :headers="$headers">
                <x-slot name="tablebody">
                    @php $displayCounter = 1; @endphp
                    @foreach ($orders as $order)
                        @php
                            $calculatedTotal = 0;
                            foreach ($order->order_items as $item) {
                                $calculatedTotal += $item['quantity'] * $item['price'];
                            }
                            $deliveryFee = (float) $order->delivery_fee;
                            $grandTotal = $calculatedTotal + $deliveryFee;
                            $orderDate = \Carbon\Carbon::parse($order->order_date)->format('Y-m-d');
                        @endphp

                        <tr class="order-row border-b hover:bg-gray-100 transition" data-order-date="{{ $orderDate }}"
                            data-order-amount="{{ $grandTotal }}">
                            <td class="px-4 py-2 text-center font-medium sr_no">{{ $displayCounter++ }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-gray-700 font-semibold">{{ $order->order_id }}</span> /
                                <span class="text-gray-500">{{ $order->tracking_number ?? 'Not Assigned' }}</span>
                            </td>
                            <td class="px-4 py-2">{{ ucwords($order->customer_name) }} <br> <a
                                    href="tel:{{ $order->phone }}"
                                    class="font-semibold pt-1 text-blue-600">{{ $order->phone }}</span>
                            </td>

                            <td class="px-4 py-2">
                                @if (session('user_details.user_role') == 'admin')
                                    @if (!empty($order->seller_name_for_list))
                                        <span class="text-gray-700 font-medium">{{ $order->seller_name_for_list }}</span>
                                        <br>
                                        <a href="tel:{{ $order->seller_phone_for_list }}"
                                            class="text-blue-600 text-sm">{{ $order->seller_phone_for_list }}</a>
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                @else
                                    @if ($order->rider)
                                        <span class="text-gray-700 font-medium">{{ $order->rider->rider_name }}</span>
                                        @if ($order->rider->vehicle_type)
                                            <br><span
                                                class="text-xs text-gray-500">({{ $order->rider->vehicle_type }})</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">No rider assigned</span>
                                    @endif
                                @endif
                            </td>

                            <td class="px-4 py-2 font-semibold text-green-600">
                                Rs {{ number_format($grandTotal, 2) }}
                            </td>

                            <td class="px-4 py-2 font-semibold text-green-600">
                                Rs {{ number_format($order->net_total, 2) }}
                            </td>

                            <td class="px-4 py-2 order-date">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 text-xs font-semibold text-white rounded-md shadow bg-green-500">
                                    {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-white
                        {{ $order->status === 'Completed' ? 'bg-green-500' : 'bg-red-500' }}
                        rounded-md shadow">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center flex">
                                <button vieworderurl="/view-order/{{ $order->order_id }}"
                                    class="viewModalBtn p-2 rounded-md transition">
                                    <svg width="24" height="24" viewBox="0 0 37 36" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M28.0642 18.5C28.0642 18.126 27.8621 17.8812 27.4579 17.3896C25.9788 15.5938 22.7163 12.25 18.9288 12.25C15.1413 12.25 11.8788 15.5938 10.3996 17.3896C9.99542 17.8812 9.79333 18.126 9.79333 18.5C9.79333 18.874 9.99542 19.1187 10.3996 19.6104C11.8788 21.4062 15.1413 24.75 18.9288 24.75C22.7163 24.75 25.9788 21.4062 27.4579 19.6104C27.8621 19.1187 28.0642 18.874 28.0642 18.5ZM18.9288 21.625C19.7576 21.625 20.5524 21.2958 21.1385 20.7097C21.7245 20.1237 22.0538 19.3288 22.0538 18.5C22.0538 17.6712 21.7245 16.8763 21.1385 16.2903C20.5524 15.7042 19.7576 15.375 18.9288 15.375C18.0999 15.375 17.3051 15.7042 16.719 16.2903C16.133 16.8763 15.8038 17.6712 15.8038 18.5C15.8038 19.3288 16.133 20.1237 16.719 20.7097C17.3051 21.2958 18.0999 21.625 18.9288 21.625Z"
                                            fill="url(#paint0_linear_872_5570)" />
                                        <circle opacity="0.1" cx="18.4287" cy="18" r="18"
                                            fill="url(#paint1_linear_872_5570)" />
                                        <defs>
                                            <linearGradient id="paint0_linear_872_5570" x1="18.9288" y1="12.25"
                                                x2="18.9288" y2="24.75" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#FCB376" />
                                                <stop offset="1" stop-color="#FE8A29" />
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_872_5570" x1="18.4287" y1="0"
                                                x2="18.4287" y2="36" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#FCB376" />
                                                <stop offset="1" stop-color="#FE8A29" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
        </div>


        <x-modal id="orders-modal">
            <x-slot name="title">Order Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>

            <x-slot name="body">
                <div class="p-6 space-y-4">
                    <!-- Order Details -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                            <tbody>
                                <tr>
                                    <td class="p-3 font-semibold">Status:</td>
                                    <td class="p-3"><span id="order-status" class="font-bold text-orange-600"></span>
                                    </td>
                                    <td class="p-3 font-semibold">Customer:</td>
                                    <td class="p-3" id="customer-name"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Track ID:</td>
                                    <td class="p-3" id="tracking-id"></td>
                                    <td class="p-3 font-semibold">Address:</td>
                                    <td class="p-3" id="customer-address"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Date:</td>
                                    <td class="p-3" id="order-date"></td>
                                    <td class="p-3 font-semibold">Number:</td>
                                    <td class="p-3" id="customer-phone"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Items:</td>
                                    <td class="p-3" id="total-items"></td>
                                    <td class="p-3 font-semibold">Total:</td>
                                    <td class="p-3" id="order-total"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Delivery Fee:</td>
                                    <td class="p-3" id="delivery-fee"></td>
                                    <td class="p-3 font-semibold">Grand Total:</td>
                                    <td class="p-3 font-bold text-green-600" id="grand-total"></td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <hr>
                                        <h3 class="font-bold pl-2 pt-3 text-xl text-primary">Rider Details</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Rider:</td>
                                    <td class="p-3" id="rider-name"></td>
                                    <td class="p-3 font-semibold">Vehicle:</td>
                                    <td class="p-3" id="rider-vehicle"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 font-semibold">Rider Phone:</td>
                                    <td class="p-3" id="rider-phone"></td>
                                    <td class="p-3 font-semibold">Rider Email:</td>
                                    <td class="p-3" id="rider-email"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Seller Details Section (Admin Only) -->
                    @if (session('user_details.user_role') == 'admin')
                        <div class="mb-3 pt-2">
                            <button id="sellerDropdownButton"
                                class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 font-semibold text-left bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                                <span>Seller Details</span>
                                <svg id="sellerDropdownArrow" class="w-5 h-5 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="sellerDropdownContent" class="mt-2 hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                                        <tbody>
                                            <tr>
                                                <td class="p-3 font-semibold">Seller Name:</td>
                                                <td class="p-3" id="seller-name"></td>
                                                <td class="p-3 font-semibold">Store Name:</td>
                                                <td class="p-3" id="store-name"></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold">Seller Email:</td>
                                                <td class="p-3" id="seller-email"></td>
                                                <td class="p-3 font-semibold">Store Address:</td>
                                                <td class="p-3" id="store-address"></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold">Seller Phone:</td>
                                                <td class="p-3" id="seller-phone"></td>
                                                <td class="p-3 font-semibold"></td>
                                                <td class="p-3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Order Items Table -->
                    <div class="mb-3 pt-2">
                        <button id="dropdownButton"
                            class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 font-semibold text-left bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                            <span>Product Details</span>
                            <svg id="dropdownArrow" class="w-5 h-5 transform transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div id="dropdownContent" class="mt-2 hidden">
                            <div class="overflow-x-auto">
                                <div class="p-4 mt-2 rounded-lg shadow bg-gray-50">
                                    <h3 class="font-bold text-gray-700 text-base md:text-lg">Items Details</h3>
                                    <div class="w-full overflow-x-auto">
                                        <table class="w-full min-w-[600px] mt-2 text-sm md:text-base text-gray-700 border">
                                            <thead>
                                                <tr class="bg-gray-200">
                                                    <th class="p-3 text-left">Image</th>
                                                    <th class="p-3 text-left">Product</th>
                                                    @if (session('user_details.user_role') == 'admin')
                                                        <th class="p-3 text-left">Status</th>
                                                        <th class="p-3 text-left">Video Prove</th>
                                                    @endif
                                                    <th class="p-3 text-center">Qty</th>
                                                    <th class="p-3 text-center">Weight / Size</th>
                                                    <th class="p-3 text-center">Variation</th>
                                                    <th class="p-3 text-center">U.Price</th>
                                                    <th class="p-3 text-center">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="order-items-body"></tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 text-sm md:text-base text-gray-700 space-y-1">
                                        <div class="flex justify-between"><span>Total Bill:</span> <span
                                                id="total-bill"></span></div>
                                        <div class="flex justify-between"><span>Delivery fee:</span> <span
                                                id="fee"></span></div>
                                        <div class="flex justify-between text-red-500">
                                            <span>Discount:</span> <span id="discount">-0</span>
                                        </div>
                                        <div class="flex justify-between mt-2 text-lg font-bold">
                                            <span>Total Amount:</span> <span id="final-total"></span>
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

    <button class="hidden" id="modal-btn" data-modal-target="orders-modal" data-modal-toggle="orders-modal"></button>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            const user = {
                user_role: "{{ session('user_details.user_role') }}"
            };

            // Stop video when modal closes
            const stopVideo = () => {
                const videos = document.querySelectorAll('#orders-modal video');
                videos.forEach(video => {
                    video.pause();
                    video.currentTime = 0;
                });
            };

            const modal = document.getElementById('orders-modal');
            if (modal) {
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach(mutation => {
                        if (mutation.attributeName === 'class' && modal.classList.contains(
                                'hidden')) {
                            stopVideo();
                        }
                    });
                });
                observer.observe(modal, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }

            $(".viewModalBtn").on("click", function() {
                let vieworderurl = $(this).attr("vieworderurl");
                if (!vieworderurl) {
                    alert("Invalid order URL!");
                    return;
                }

                let storeId = $("#store_id").val();
                if (storeId) {
                    // append ?store_id=xxx if not exists, else add &store_id=xxx
                    vieworderurl += (vieworderurl.includes("?") ? "&" : "?") + "store_id=" + storeId;
                }
                $("#modal-btn").click(); // Open modal

                $.ajax({
                    url: vieworderurl,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {
                            alert(response.error);
                            return;
                        }

                        let status = response.order_status;
                        let formattedStatus = status.split('_')
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                            .join(' ');

                        $("#order-status").text(formattedStatus);
                        $("#customer-name").text(response.customer_name.toUpperCase());
                        $("#tracking-id").text(response.tracking_id);
                        $("#customer-address").text(response.address);
                        $("#order-date").text(response.order_date);
                        $("#customer-phone").text(response.phone);
                        $("#total-items").text(response.order_items.length);

                        let itemsHtml = "";
                        let total = 0;
                        const fallbackImage = "{{ asset('asset/Ellipse 2.png') }}";

                        response.order_items.forEach((item) => {
                            const itemTotal = item.quantity * item.price;
                            total += itemTotal;

                            itemsHtml += `
                        <tr class="border-b">
                            <td class="p-3">
                                <img src="${item.product_image}" alt="${item.product_name}" class="w-16 h-16 object-cover" onerror="this.onerror=null; this.src='${fallbackImage}'">
                            </td>
                            <td class="p-3">${item.product_name}</td>
                            ${user.user_role == 'admin' ? `
                                                            <td class="p-3">${item?.delivery_status || 'N/A'}</td>
                                                            <td class="p-3">
                                                                ${item.status_video ? `
                                        <video controls class="w-28 h-16 rounded shadow">
                                            <source src="/storage/${item.status_video}" type="video/mp4">
                                        </video>` : 'No video'}
                                                            </td>` : ''}
                            <td class="p-3 text-center">${item.quantity}</td>
                            <td class="p-3 text-center">${item.order_weight ?? '0'} / ${item.order_size ?? '0'}</td>
                            <td class="p-3 text-center">
                                <div class="inline-block text-sm text-gray-700">
                                    ${item.parent_option?.name ? `<span class="font-medium">${item.parent_option.name}:</span> ${item.parent_option.value}` : ''}
                                    ${item.child_option?.name && item.child_option?.value && item.child_option.value !== 'N/A'
                                        ? `<br><span class="font-medium">${item.child_option.name}:</span> ${item.child_option.value}` : ''}
                                </div>
                            </td>
                            <td class="p-3 text-center">Rs ${item.price}</td>
                            <td class="p-3 text-center">Rs ${itemTotal.toFixed(2)}</td>
                        </tr>`;
                        });

                        $("#order-items-body").html(itemsHtml);

                        const deliveryFee = parseFloat(response.delivery_fee) || 0;
                        const grandTotal = total + deliveryFee;

                        $("#order-total").text("Rs " + total.toFixed(2));
                        $("#delivery-fee").text("Rs " + deliveryFee.toFixed(2));
                        $("#grand-total").text("Rs " + grandTotal.toFixed(2));
                        $("#total-bill").text("Rs " + total.toFixed(2));
                        $("#fee").text("Rs " + deliveryFee.toFixed(2));
                        $("#final-total").text("Rs " + grandTotal.toFixed(2));

                        if (response.rider_details) {
                            $("#rider-name").text(response.rider_details.rider_name);
                            $("#rider-vehicle").text(response.rider_details.vehicle_type ||
                                'Not specified');
                            $("#rider-phone").text(response.rider_details.phone ||
                                'Not provided');
                            $("#rider-email").text(response.rider_details.rider_email ||
                                'Not provided');
                        } else {
                            $("#rider-name").text('No rider assigned');
                            $("#rider-vehicle").text('N/A');
                            $("#rider-phone").text('N/A');
                            $("#rider-email").text('N/A');
                        }

                        if (user.user_role === 'admin' && response.order_items.length > 0) {
                            const firstItem = response.order_items[0];
                            if (firstItem.seller_info) {
                                $("#seller-name").text(firstItem.seller_info.seller_name ||
                                    'N/A');
                                $("#seller-email").text(firstItem.seller_info.seller_email ||
                                    'N/A');
                                $("#seller-phone").text(firstItem.seller_info.seller_phone ||
                                    'N/A');
                                $("#store-name").text(firstItem.seller_info.store_name ||
                                    'N/A');
                                $("#store-address").text(firstItem.seller_info.store_address ||
                                    'N/A');
                            }
                        }
                    },
                    error: function() {
                        alert("Failed to fetch order details. Please try again.");
                    }
                });
            });
        });

        // Dropdowns
        document.getElementById('dropdownButton').addEventListener('click', () => {
            dropdownContent.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });
        if (document.getElementById('sellerDropdownButton')) {
            sellerDropdownButton.addEventListener('click', () => {
                sellerDropdownContent.classList.toggle('hidden');
                sellerDropdownArrow.classList.toggle('rotate-180');
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applyFilterBtn = document.getElementById('apply_date_filter');

            applyFilterBtn.addEventListener('click', function() {
                const fromDate = document.getElementById('from_date').value;
                const toDate = document.getElementById('to_date').value;

                // Build URL with query parameters
                const url = new URL(window.location.href);

                if (fromDate) {
                    url.searchParams.set('from_date', fromDate);
                } else {
                    url.searchParams.delete('from_date');
                }

                if (toDate) {
                    url.searchParams.set('to_date', toDate);
                } else {
                    url.searchParams.delete('to_date');
                }

                // Reload the page with new filters
                window.location.href = url.toString();
            });

            // Optional: Submit on Enter key press
            document.getElementById('from_date').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyFilterBtn.click();
            });

            document.getElementById('to_date').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyFilterBtn.click();
            });
        });
    </script>
    <script>
        function printDiv() {
            // Get all elements with class 'printArea'
            let elements = document.getElementsByClassName('printArea');
            let printContents = '';

            // Collect HTML of each element
            for (let i = 0; i < elements.length; i++) {
                printContents += elements[i].outerHTML;
            }

            // Save original content
            let originalContents = document.body.innerHTML;

            // Replace with print content
            document.body.innerHTML = printContents;
            window.print();

            // Restore after print
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>


@endsection
