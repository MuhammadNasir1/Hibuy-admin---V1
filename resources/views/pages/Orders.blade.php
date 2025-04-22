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
                    {{-- <li class="me-2">
                        <a href="#"
                            class="inline-block border px-4 py-1 rounded-3xl hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Seller</a>
                    </li>
                    <li class="me-2">
                        <a href="#"
                            class="inline-block border px-4 py-1 rounded-3xl hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Freelancer</a>
                    </li> --}}
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
                'ID / Track Id',
                'Customer',
                'Phone Number',
                'Address',
                'Bill Amount',
                'Date',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @foreach ($orders as $order)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="px-4 py-2 text-center font-medium">{{ $order->order_id }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="text-gray-700 font-semibold">{{ $order->order_id }}</span> /
                            <span class="text-gray-500">{{ $order->tracking_id }}</span>
                        </td>
                        <td class="px-4 py-2">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2">{{ $order->phone }}</td>
                        <td class="px-4 py-2">{{ $order->address }}</td>
                        <td class="px-4 py-2 font-semibold text-green-600">Rs {{ number_format($order->grand_total, 2) }}
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</td>
                        <td class="px-4 py-2">
                            <span
                                class="px-3 py-1 text-xs font-semibold text-white
                        {{ $order->status === 'Completed' ? 'bg-green-500' : 'bg-red-500' }}
                        rounded-md shadow">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
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



        <x-modal id="orders-modal">
            <x-slot name="title">Order Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>

            <x-slot name="body">
                <div class="p-6 space-y-4">
                    <!-- Customer Details -->
                    <form id="statusForm" class="w-full">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-5 mb-4 items-end">
                            @if (session('user_details.user_role') == 'admin')
                                {{-- Order Status --}}
                                <div>
                                    <label for="order_status" class="block mb-1 text-sm font-normal text-gray-600">Order
                                        Status</label>
                                    <input type="hidden" id="edit_orderstatus_id" name="edit_orderstatus_id">
                                    <select id="order_status" name="order_status"
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

                                {{-- Courier Selection --}}
                                <div>
                                    <label for="courier_id"
                                        class="block mb-1 text-sm font-normal text-gray-600">Courier</label>
                                    <select id="courier_id" name="courier_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                        <option value="" selected>Select Courier</option>
                                    </select>
                                </div>

                                {{-- Tracking Number --}}
                                <div>
                                    <label for="tracking_number"
                                        class="block mb-1 text-sm font-normal text-gray-600">Tracking
                                        No.</label>
                                    <input type="text" id="tracking_number" name="tracking_number"
                                        placeholder="Enter tracking number"
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
                                <div>
                                    <label for="order_status_seller"
                                        class="block mb-1 text-sm font-normal text-gray-600">Delivery
                                        Status</label>
                                    <input type="hidden" id="editbyseller_orderstatus_id"
                                        name="editbyseller_orderstatus_id">
                                    <input type="hidden" id="edit_orderstatus_id" name="edit_orderstatus_id">
                                    <select id="order_status_seller" name="order_status_seller"
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

                                {{-- Submit Button --}}
                                <div class="flex justify-end">
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
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Items Table -->
                    <div class="mb-3 pt-2">
                        <button id="dropdownButton"
                            class="flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 font-semibold text-left bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                            <span>Details</span>
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
                                                    <th class="p-3 text-center">Qty</th>
                                                    <th class="p-3 text-center">U.Price</th>
                                                    <th class="p-3 text-center">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="order-items-body"></tbody>
                                        </table>
                                    </div>

                                    <!-- Total Calculation -->
                                    <div class="mt-4 text-sm md:text-base text-gray-700 space-y-1">
                                        <div class="flex justify-between"><span>Total Bill:</span> <span id="total-bill"></span></div>
                                        <div class="flex justify-between"><span>Delivery fee:</span> <span id="fee"></span></div>
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
            $(".viewModalBtn").on("click", function() {
                let vieworderurl = $(this).attr("vieworderurl"); // Get order details URL
                if (!vieworderurl) {
                    alert("Invalid order URL!");
                    return;
                }

                $("#modal-btn").click(); // Open modal

                // Run AJAX to fetch order details
                $.ajax({
                    url: vieworderurl,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        // console.log(response);

                        if (response.error) {
                            alert(response.error);
                            return;
                        }
                        // Assuming your API response is saved in a variable called response
                        const couriers = response
                            .couriers; // Array of { courier_id, courier_name }
                        const selectBox = document.getElementById('courier_id');

                        // // Clear existing options (optional)
                        // selectBox.innerHTML = '<option value="">Select Courier</option>';

                        // Loop through couriers and append options
                        couriers.forEach(courier => {
                            const option = document.createElement('option');
                            option.value = courier.courier_id;
                            option.textContent = courier.courier_name;
                            selectBox.appendChild(option);
                        });

                        // // Optional: Set selected courier if editing an existing order
                        if (response.selected_courier_id) {
                            selectBox.value = response.selected_courier_id;
                        }


                        // Populate order details
                        $('#tracking_number').val(response.tracking_number);
                        $('#order_status').val(response.order_status).change();
                        $("#order-status").text(response.order_status);
                        $("#edit_orderstatus_id").val(response.order_id);
                        $("#customer-name").text(response.customer_name);
                        $("#tracking-id").text(response.tracking_id);
                        $("#customer-address").text(response.address);
                        $("#order-date").text(response.order_date);
                        $("#customer-phone").text(response.phone);
                        $("#total-items").text(response.order_items.length);
                        $("#order-total").text("Rs " + response.total);
                        $("#delivery-fee").text("Rs " + response.delivery_fee);
                        $("#grand-total").text("Rs " + response.grand_total);

                        // Populate billing details
                        $("#total-bill").text("Rs " + response.total);
                        $("#fee").text("Rs " + response.delivery_fee);
                        $("#final-total").text("Rs " + response.grand_total);

                        // Populate order items table
                        let itemsHtml = "";
                        const fallbackImage = "{{ asset('asset/Ellipse 2.png') }}";
                        response.order_items.forEach((item) => {
                            console.log(item);
                            // Set order status if it's available in response
                            if (response.status) {
                                $("#order_status_seller").val(item.delivery_status)
                                    .change();
                                $("#editbyseller_orderstatus_id").val(item.product_id);
                            }
                            itemsHtml += `
                        <tr class="border-b">
                           <td class="p-3">
                            <img src="${item.product_image}" alt="${item.product_name}" class="w-16 h-16 object-cover" onerror="this.onerror=null; this.src='${fallbackImage}'"></td>
                            <td class="p-3">${item.product_name}</td>
                            <td class="p-3 text-center">${item.quantity}</td>
                            <td class="p-3 text-center">Rs ${item.price}</td>
                            <td class="p-3 text-center">Rs ${(item.quantity * item.price).toFixed(2)}</td>
                        </tr>`;
                        });

                        $("#order-items-body").html(itemsHtml);
                    },
                    error: function() {
                        alert("Failed to fetch order details. Please try again.");
                    },
                });
            });


            // JS (place in a script tag or external JS file)
            $('#submitStatus').on('click', function(e) {
                e.preventDefault();

                let formData = {
                    _token: $('input[name="_token"]').val(),
                    order_status: $('#order_status').val(),
                    courier_id: $('#courier_id').val(),
                    tracking_number: $('#tracking_number').val(),
                    order_id: $('#edit_orderstatus_id').val() // hidden input carrying order_id
                };

                $.ajax({
                    url: '{{ route('orders.update.status') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        alert(response.message); // You can use toast or modal instead
                        $("#order-status").text($('#order_status').val());
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Something went wrong.');
                        console.error(xhr.responseText);
                    }
                });
            });



            $('#submitStatusbyseller').on('click', function(e) {
                e.preventDefault();

                let formData = {
                    _token: $('input[name="_token"]').val(),
                    delivery_status: $('#order_status_seller').val(),
                    order_id: $('#edit_orderstatus_id').val(),
                    product_id: $('#editbyseller_orderstatus_id').val()
                };


                console.log(formData);

                $.ajax({
                    url: '{{ route('orders.update.status') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Something went wrong.');
                        console.error(xhr.responseText);
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
    </script>
@endsection
