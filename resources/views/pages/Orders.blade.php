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
                            class="inline-block border px-4 py-1 rounded-3xl hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Seller</a>
                    </li>
                    <li class="me-2">
                        <a href="#"
                            class="inline-block border px-4 py-1 rounded-3xl hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Freelancer</a>
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
                            <button class="viewModalBtn p-2 rounded-md  transition"
                                data-modal-target="orders-modal" data-modal-toggle="orders-modal">
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
                <div class="p-6 space-y-4"> <!-- Added padding here -->
                    <!-- Customer Details -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                            <tbody>
                                <tr class="">
                                    <td class="p-3 font-semibold">Status:</td>
                                    <td class="p-3"><span class="font-bold text-orange-600">Onway</span></td>
                                    <td class="p-3 font-semibold">Customer:</td>
                                    <td class="p-3">Ali Hassan</td>
                                </tr>
                                <tr class="">
                                    <td class="p-3 font-semibold">Track ID:</td>
                                    <td class="p-3">1231231</td>
                                    <td class="p-3 font-semibold">Address:</td>
                                    <td class="p-3">123 street, city</td>
                                </tr>
                                <tr class="">
                                    <td class="p-3 font-semibold">Date:</td>
                                    <td class="p-3">10-20-2024</td>
                                    <td class="p-3 font-semibold">Number:</td>
                                    <td class="p-3">3499494594</td>
                                </tr>
                                <tr class="">
                                    <td class="p-3 font-semibold">Items:</td>
                                    <td class="p-3">2</td>
                                    <td class="p-3 font-semibold">Email:</td>
                                    <td class="p-3">Email@gmail.com</td>
                                </tr>
                                <tr class="">
                                    <td class="p-3 font-semibold">Amount:</td>
                                    <td class="p-3">$34</td>
                                    <td class="p-3 font-semibold">Reason:</td>
                                    <td class="p-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <!-- Item Details Dropdown -->
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
                                <div class="p-4 mt-2 rounded-lg shadow bg-gray-50"> <!-- Added padding here -->
                                    <h3 class="font-bold text-gray-700">Items Details</h3>
                                    <table class="w-full mt-2 text-sm text-gray-700 border">
                                        <thead>
                                            <tr class="bg-gray-200">
                                                <th class="p-3 text-left">Product</th>
                                                <th class="p-3 text-center">Qty</th>
                                                <th class="p-3 text-center">U.Price</th>
                                                <th class="p-3 text-center">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b">
                                                <td class="p-3">name</td>
                                                <td class="p-3 text-center">1</td>
                                                <td class="p-3 text-center">500.0</td>
                                                <td class="p-3 text-center">500.0</td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="p-3">name</td>
                                                <td class="p-3 text-center">2</td>
                                                <td class="p-3 text-center">50.0</td>
                                                <td class="p-3 text-center">100.0</td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="p-3">name</td>
                                                <td class="p-3 text-center">5</td>
                                                <td class="p-3 text-center">10.0</td>
                                                <td class="p-3 text-center">100.0</td>
                                            </tr>
                                            <tr class="border-b">
                                                <td class="p-3">name</td>
                                                <td class="p-3 text-center">1</td>
                                                <td class="p-3 text-center">200.0</td>
                                                <td class="p-3 text-center">200.0</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3">name</td>
                                                <td class="p-3 text-center">2</td>
                                                <td class="p-3 text-center">75.0</td>
                                                <td class="p-3 text-center">150.0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-4 text-sm text-gray-700">
                                        <div class="flex justify-between"><span>Total Bill:</span> <span>1,050</span></div>
                                        <div class="flex justify-between"><span>Delivery fee:</span> <span>50</span></div>
                                        <div class="flex justify-between text-red-500">
                                            <span>Discount:</span> <span>-100</span>
                                        </div>
                                        <div class="flex justify-between mt-2 text-lg font-bold">
                                            <span>Total Amount:</span> <span>1,000</span>
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
@endsection

@section('js')
    <script>
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownContent = document.getElementById('dropdownContent');
        const dropdownArrow = document.getElementById('dropdownArrow');

        dropdownButton.addEventListener('click', () => {
            dropdownContent.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });
    </script>
@endsection
