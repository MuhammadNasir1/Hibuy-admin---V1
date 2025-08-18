@extends('layout')
@section('title', 'Orders')
@section('nav-title', 'Orders')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="px-5">
            <h2 class="text-2xl font-medium ">
                Orders List
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
                'ID / Track Number',
                'Customer / Phone',
                session('user_details.user_role') == 'admin' ? 'Seller / Phone' : 'Rider',
                'Bill Amount',
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
                    @endphp

                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="px-4 py-2 text-center font-medium">{{ $displayCounter++ }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="text-gray-700 font-semibold">{{ $order->order_id }}</span> /
                            <span class="text-gray-500">{{ $order->tracking_id ?? 'Not Assigned' }}</span>
                        </td>
                        <td class="px-4 py-2">{{ ucwords($order->customer_name) }} <br> <a href="tel:{{ $order->phone }}"
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
                                        <br><span class="text-xs text-gray-500">({{ $order->rider->vehicle_type }})</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">No rider assigned</span>
                                @endif
                            @endif
                        </td>

                        {{-- Show Calculated Grand Total --}}
                        <td class="px-4 py-2 font-semibold text-green-600">
                            Rs {{ number_format($grandTotal, 2) }}
                        </td>

                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}
                        </td>
                        <td class="px-4 py-2">
                            <span
                                class="px-3 py-1 text-xs font-semibold text-white
                                rounded-md shadow
                                @switch($order->order_status)
                                    @case('shipped') bg-blue-500 @break
                                    @case('delivered') bg-green-500 @break
                                    @case('cancelled') bg-red-500 @break
                                    @case('returned') bg-purple-500 @break
                                    @default bg-yellow-500
                                @endswitch
                                ">
                                {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                        </td>

                        <td class="px-4 py-2">
                            <span
                                class="px-3 py-1 text-xs font-semibold text-white
                                rounded-md shadow
                                @switch($order->status)
                                    @case('pending') bg-yellow-500 @break
                                    @case('on the way') bg-blue-500 @break
                                    @case('completed') bg-green-500 @break
                                    @case('cancelled') bg-red-500 @break
                                    @case('returned') bg-purple-500 @break
                                    @default bg-gray-500
                                @endswitch
                                ">
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
                            <a href="{{ route('print.slip', $order->order_id) }}" id="printBtn" class=" p-2 rounded-md"
                                target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 fill-primary">
                                    <path
                                        d="M128 128C128 92.7 156.7 64 192 64L405.5 64C422.5 64 438.8 70.7 450.8 82.7L493.3 125.2C505.3 137.2 512 153.5 512 170.5L512 208L128 208L128 128zM64 320C64 284.7 92.7 256 128 256L512 256C547.3 256 576 284.7 576 320L576 416C576 433.7 561.7 448 544 448L512 448L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 448L96 448C78.3 448 64 433.7 64 416L64 320zM192 480L192 512L448 512L448 416L192 416L192 480zM520 336C520 322.7 509.3 312 496 312C482.7 312 472 322.7 472 336C472 349.3 482.7 360 496 360C509.3 360 520 349.3 520 336z" />
                                </svg>
                            </a>
                            @if (session('user_details.user_role') === 'admin')
                                <a href="#" id=""
                                    class="paidBtn p-2 rounded-md transition-colors duration-200
                                 {{ $order->paid ? 'bg-gray-400 text-white cursor-not-allowed' : 'bg-primary text-white' }}"
                                    data-order-id="{{ $order->order_id }}" data-order-status="{{ $order->order_status }}"
                                    {{ $order->paid ? 'disabled' : '' }}>
                                    {{ $order->paid ? 'Paid' : 'Pay' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach


            </x-slot>
        </x-table>



        <x-modal id="orders-modal">
            <x-slot name="title">Order Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>

            <x-slot name="body">
                <div class="p-6 space-y-4">
                    <!-- Customer Details -->
                    <form id="statusForm" class="w-full">
                        @csrf
                        <div
                            class="{{ session('user_details.user_role') == 'admin' ? 'grid grid-cols-1 md:grid-cols-4 gap-4 mt-5 mb-4 items-end' : '' }}">
                            @if (session('user_details.user_role') == 'admin')
                                {{-- Order Status --}}

                                <div>
                                    <label for="order_status" class="block mb-1 text-sm font-normal text-gray-600">Order
                                        Status</label>
                                    <input type="hidden" id="edit_orderstatus_id" name="edit_orderstatus_id">
                                    <select id="order_status" name="order_status" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                        <option value="" selected>Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="returned">Returned</option>
                                    </select>
                                </div>

                                {{-- Rider Selection --}}
                                <div>
                                    <label for="rider_id"
                                        class="block mb-1 text-sm font-normal text-gray-600">Rider</label>
                                    <select id="rider_id" name="rider_id" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                        <option value="" selected>Select Rider</option>
                                    </select>
                                </div>

                                {{-- Tracking Number --}}
                                <div>
                                    <label for="tracking_number"
                                        class="block mb-1 text-sm font-normal text-gray-600">Tracking
                                        No.</label>
                                    <input type="text" id="tracking_number" name="tracking_number"
                                        placeholder="Enter tracking number" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                </div>

                                {{-- Submit Button --}}
                                <div class="flex justify-end">
                                    <button type="submit" id="submitStatus"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Submit
                                    </button>
                                </div>
                            @else
                                <div class=" gap-2 w-full">

                                    <!-- File Input for Video Upload -->
                                    <div class="flex gap-2 w-full">
                                        <div class="flex flex-col mb-3">
                                            <!-- Video Preview -->
                                            <video id="videoPreview" controls class="h-[150px] mr-3 hidden">
                                                <source id="videoSource" src="" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <label for="videoInput"
                                                class="block mb-1 text-sm mt-3 font-normal text-gray-600">Upload Video (Max
                                                20MB)</label>
                                            <input type="file" id="videoInput" name="status_video" accept="video/*"
                                                class="block" value="">

                                        </div>

                                        <div class=" flex-col gap-5 mb-3 w-full">
                                            <div>
                                                <label for="weight_admin"
                                                    class=" mb-1 text-sm font-normal text-gray-600">Weight
                                                    (kg)</label>
                                                <input type="number" id="weight_admin" name="weight_admin"
                                                    step="0.01" min="0" placeholder="Enter weight in kg"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                            </div>
                                            {{-- Size feild here --}}
                                            <div>
                                                <label for="size_admin"
                                                    class=" mb-1 text-sm font-normal text-gray-600">Size</label>
                                                <input type="text" id="size_admin" name="size_admin"
                                                    placeholder="Enter size"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                            </div>

                                            <div class="w-full col-span-1">
                                                <label for="order_status_seller"
                                                    class="block mb-1 text-sm font-normal text-gray-600">Delivery
                                                    Status</label>
                                                <input type="hidden" id="editbyseller_orderstatus_id"
                                                    name="editbyseller_orderstatus_id">
                                                <input type="hidden" id="edit_orderstatus_id"
                                                    name="edit_orderstatus_id">
                                                <select id="order_status_seller" name="order_status_seller"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                                    <option value="" selected>Select Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="processing">Processing</option>
                                                    <option value="ready">Ready to Pick</option>
                                                    <option value="delivered">Delivered</option>
                                                    <option value="cancelled">Cancelled</option>
                                                    <option value="returned">Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- weight feild herre --}}
                                </div>

                                {{-- Submit Button --}}
                                <div class="flex justify-end col-span-1">
                                    <button type="submit" id="submitStatusbyseller"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Submit
                                    </button>
                                </div>
                            @endif


                        </div>

                    </form>
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
                    {{-- @if (session('user_details.user_role') == 'admin')
                        <div class="mb-3 pt-2">
                            <button id="sellerDropdownButton"
                                class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 font-semibold text-left bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                                <span>Seller Details</span>
                                <svg id="sellerDropdownArrow" class="w-5 h-5 transform transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
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
                    @endif --}}

                    <!-- Order Items Table -->
                    <div class="mb-3 pt-2">
                        <button id="dropdownButton"
                            class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 font-semibold text-left bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                            <span>Product Details</span>
                            <svg id="dropdownArrow" class="w-5 h-5 transform transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
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
                                                    @if (session('user_details.user_role') === 'admin')
                                                        <th class="p-3 text-left">Store / Seller</th>
                                                    @endif
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

                                    <!-- Total Calculation -->
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

            // Function to stop all videos in the modal
            const stopVideo = () => {
                const videos = document.querySelectorAll('#orders-modal video');
                videos.forEach(video => {
                    video.pause();
                    video.currentTime = 0; // Optional: Reset to start
                });
            };
            // Detect modal close for Flowbite
            const modal = document.getElementById('orders-modal');
            const closeButtons = document.querySelectorAll('[data-modal-toggle="orders-modal"]');
            // Observe changes to modal's class (e.g., 'hidden' added by Flowbite)
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

                        if (user.user_role === 'admin') {
                            const riders = response.riders;
                            const selectBox = document.getElementById('rider_id');
                            selectBox.innerHTML =
                                '<option value="" selected>Select Rider</option>';
                            riders.forEach(rider => {
                                const option = document.createElement('option');
                                option.value = rider.id;
                                option.textContent =
                                    `${rider.rider_name} (${rider.vehicle_type || 'No Vehicle'})`;
                                selectBox.appendChild(option);
                            });
                            if (response.selected_rider_id) {
                                selectBox.value = response.selected_rider_id;
                            }
                        }

                        $('#tracking_number').val(response.tracking_number);
                        if (response.order_status === 'order_placed' || response
                            .order_status === 'pending') {
                            $('#order_status').val('processing').change();
                        } else {
                            $('#order_status').val(response.order_status).change();
                        }
                        let status = response.order_status; // e.g., "order_placed"
                        let formattedStatus = status
                            .split('_') // ["order", "placed"]
                            .map(word => word.charAt(0).toUpperCase() + word.slice(
                                1)) // ["Order", "Placed"]
                            .join(' '); // "Order Placed"

                        $("#order-status").text(formattedStatus);
                        $("#edit_orderstatus_id").val(response.order_id);
                        $("#customer-name").text(response.customer_name.toUpperCase());
                        $("#tracking-id").text(response.tracking_id);
                        $("#customer-address").text(response.address);
                        $("#order-date").text(response.order_date);
                        $("#customer-phone").text(response.phone);
                        $("#total-items").text(response.order_items.length);
                        $("#weight_admin").val(response.order_items[0].order_weight);
                        $("#size_admin").val(response.order_items[0].order_size);

                        let itemsHtml = "";
                        let total = 0;
                        const fallbackImage = "{{ asset('asset/Ellipse 2.png') }}";

                        response.order_items.forEach((item) => {
                            const itemTotal = item.quantity * item.price;
                            total += itemTotal;

                            if (response.status) {
                                $("#order_status_seller").val(item.delivery_status)
                                    .change();
                                $("#editbyseller_orderstatus_id").val(item.product_id);

                                if (user.user_role !== 'admin') {
                                    if (item.status_video) {
                                        const videoUrl =
                                            `/storage/${item.status_video}`;
                                        $("#videoSource").attr("src", videoUrl);
                                        $("#videoPreview").removeClass("hidden")[0]
                                            .load();
                                    } else {
                                        $("#videoPreview").addClass("hidden");
                                        $("#videoSource").attr("src", "");
                                    }
                                } else {
                                    $("#videoPreview").addClass("hidden");
                                    $("#videoSource").attr("src", "");
                                }
                            }

                            itemsHtml += `
                             <tr class="border-b">
                                    <td class="p-3">
                                        <img src="${item.product_image}" alt="${item.product_name}" class="w-16 h-16 object-cover" onerror="this.onerror=null; this.src='${fallbackImage}'">
                                    </td>
                                    <td class="p-3">${item.product_name}</td>

                                    ${user.user_role == 'admin' ? `

                                                    <td class="p-3">${item.seller_info.store_name} / ${item.seller_info.seller_name}</td>

                                                      <td class="p-3">${item?.delivery_status || 'N/A'}</td>
                                                      <td class="p-3">
                                                        ${item.status_video ? `
                                                <video controls class="w-28 h-16 rounded shadow">
                                                    <source src="/storage/${item.status_video}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>` : 'No video'}

                                                     </td>
                                                     ` : ''}

                                    <td class="p-3 text-center">${item.quantity}</td>
                                    <td class="p-3 text-center">${item.order_weight ?? '0'} / ${item.order_size ?? '0'} </td>

                                    <td class="p-3 text-center">
                                        <div class="inline-block text-sm text-gray-700">
                                            ${item.parent_option?.name && item.parent_option?.value
                                                ? `<span class="font-medium text-gray-800">${item.parent_option.name}:</span> ${item.parent_option.value}`
                                                : ''
                                            }
                                            ${item.child_option?.name && item.child_option?.value && item.child_option.value !== 'N/A'
                                                ? `<br><span class="font-medium text-gray-800">${item.child_option.name}:</span> ${item.child_option.value}`
                                                : ''
                                            }
                                        </div>
                                    </td>

                                    <td class="p-3 text-center">Rs ${item.price}</td>
                                    <td class="p-3 text-center">Rs ${itemTotal.toFixed(2)}</td>
                                </tr>`;
                        });

                        $("#order-items-body").html(itemsHtml);

                        // Delivery and grand total
                        const deliveryFee = parseFloat(response.delivery_fee) || 0;
                        const grandTotal = total + deliveryFee;

                        // Display totals
                        $("#order-total").text("Rs " + total.toFixed(2));
                        $("#delivery-fee").text("Rs " + deliveryFee.toFixed(2));
                        $("#grand-total").text("Rs " + grandTotal.toFixed(2));

                        // If you are using duplicate total fields in other parts of modal/view
                        $("#total-bill").text("Rs " + total.toFixed(2));
                        $("#fee").text("Rs " + deliveryFee.toFixed(2));
                        $("#final-total").text("Rs " + grandTotal.toFixed(2));

                        // Populate rider details
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

                        // Populate seller details (Admin only)
                        if (user.user_role === 'admin' && response.order_items && response
                            .order_items.length > 0) {
                            // Get seller info from the first item (assuming all items are from the same seller)
                            const firstItem = response.order_items[0];
                            console.log('First item seller info:', firstItem
                                .seller_info); // Debug log

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
                            } else {
                                $("#seller-name").text('N/A');
                                $("#seller-email").text('N/A');
                                $("#seller-phone").text('N/A');
                                $("#store-name").text('N/A');
                                $("#store-address").text('N/A');
                            }
                        } else if (user.user_role === 'admin') {
                            // Clear seller details if no items
                            $("#seller-name").text('N/A');
                            $("#seller-email").text('N/A');
                            $("#seller-phone").text('N/A');
                            $("#store-name").text('N/A');
                            $("#store-address").text('N/A');
                        }
                    },
                    error: function() {
                        alert("Failed to fetch order details. Please try again.");
                    }
                });
            });

            $('#submitStatus').on('click', function(e) {
                e.preventDefault();
                let formData = {
                    _token: $('input[name="_token"]').val(),
                    order_status: $('#order_status').val(),
                    rider_id: $('#rider_id').val(),
                    tracking_number: $('#tracking_number').val(),
                    order_id: $('#edit_orderstatus_id').val()
                };
                $.ajax({
                    url: '{{ route('orders.update.status') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        }).then(() => {
                            $("#order-status").text($('#order_status').val());
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Something went wrong. Please try again.';
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: errorMessage,
                            timer: 4000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                    }
                });
            });

            $('#submitStatusbyseller').on('click', function(e) {
                e.preventDefault();
                const videoFile = $('#videoInput')[0].files[0];
                if (videoFile && videoFile.size > 20 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large',
                        text: 'Please upload a video smaller than 20MB.',
                        timer: 4000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                    return;
                }
                let formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('delivery_status', $('#order_status_seller').val());
                formData.append('order_id', $('#edit_orderstatus_id').val());
                formData.append('product_id', $('#editbyseller_orderstatus_id').val());
                formData.append('weight_admin', $('#weight_admin').val());
                formData.append('size_admin', $('#size_admin').val());
                if (videoFile) {
                    formData.append('status_video', videoFile);
                }
                Swal.fire({
                    title: 'Uploading...',
                    text: 'Please wait while your data is being submitted.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: '{{ route('orders.update.status') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message ||
                                'Order status updated successfully!',
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON?.message ||
                                'Something went wrong. Please try again!',
                            timer: 4000,
                            showConfirmButton: false,
                            timerProgressBar: true,
                        });
                    }
                });
            });


            $('.paidBtn').on('click', function(e) {
                e.preventDefault();

                const orderId = $(this).data('order-id');
                const orderStatus = $(this).data('order-status');
                const btn = $(this);

                // Check if the button is disabled or order status is not delivered
                if (!orderId || btn.hasClass('cursor-not-allowed') || orderStatus !== 'delivered') {
                    if (orderStatus !== 'delivered') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Status',
                            text: 'Payment can only be processed if orders status "Delivered".',
                            confirmButtonText: 'OK'
                        });
                    }
                    return; // Do nothing if already paid, invalid, or status is not delivered
                }

                $.ajax({
                    url: `/orders/${orderId}/mark-paid`,
                    type: 'POST',
                    data: JSON.stringify({
                        paid: true
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                btn.text('Paid')
                                    .removeClass('bg-primary')
                                    .addClass('bg-gray-400 cursor-not-allowed')
                                    .prop('disabled', true);
                            });
                        } else {
                            // If already paid, show info instead of error
                            Swal.fire({
                                icon: 'info',
                                title: 'Already Paid',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });

                            btn.text('Paid')
                                .removeClass('bg-primary')
                                .addClass('bg-gray-400 cursor-not-allowed')
                                .prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the paid status. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });


        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownContent = document.getElementById('dropdownContent');
        const dropdownArrow = document.getElementById('dropdownArrow');

        dropdownButton.addEventListener('click', () => {
            dropdownContent.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });

        // Seller Details Dropdown
        const sellerDropdownButton = document.getElementById('sellerDropdownButton');
        const sellerDropdownContent = document.getElementById('sellerDropdownContent');
        const sellerDropdownArrow = document.getElementById('sellerDropdownArrow');

        if (sellerDropdownButton) {
            sellerDropdownButton.addEventListener('click', () => {
                sellerDropdownContent.classList.toggle('hidden');
                sellerDropdownArrow.classList.toggle('rotate-180');
            });
        }

        // Add event listener for video input, but only if it exists
        const videoInput = document.getElementById('videoInput');
        if (videoInput) {
            videoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const videoPreview = document.getElementById('videoPreview');
                    const videoSource = document.getElementById('videoSource');

                    const fileURL = URL.createObjectURL(file);
                    videoSource.src = fileURL;
                    videoPreview.load();
                    videoPreview.classList.remove('hidden');
                }
            });
        }
    </script>
@endsection
