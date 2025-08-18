@extends('layout')
@section('title', 'Return Orders')
@section('nav-title', 'Return Orders')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="px-5">
            <h2 class="text-2xl font-medium ">
                Return Orders
            </h2>
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
                'Return Reason',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">




            </x-slot>
        </x-table>


        <x-modal id="returnorders-modal">
            <x-slot name="title">Return Order Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>

            <x-slot name="body">
                <div class="p-6 space-y-4"> <!-- Added padding here -->
                    <!-- Customer Details -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-sm text-gray-700">
                            <tbody>
                                <tr class="">
                                    <td class="p-3 font-semibold">Status:</td>
                                    <td class="p-3"><span class="font-bold text-orange-600">Return</span></td>
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
                                <div class="p-4 mt-2 rounded-lg shadow bg-gray-50">
                                    <h3 class="font-bold text-gray-700 text-base md:text-lg">Items Details</h3>

                                    <!-- Responsive table wrapper -->
                                    <div class="w-full overflow-x-auto">
                                        <table class="w-full min-w-[600px] mt-2 text-sm md:text-base text-gray-700 border">
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
                                    </div>

                                    <!-- Totals -->
                                    <div class="mt-4 text-sm md:text-base text-gray-700 space-y-1">
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
