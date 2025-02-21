@extends('layout')
@section('title', 'Buyer Management')
@section('nav-title', 'Buyer Management')
@section('content')
    <div class="w-full pt-10 pb-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div>
            <h3 class="mx-6 mt-5 text-2xl font-semibold">
                Buyer Details
            </h3>
            <div class="h-[150px] border rounded-xl mx-6  mt-3 flex justify-center items-center">
                <div class="h-[80%] w-[95%] rounded-xl flex items-center justify-between">
                    <!-- Seller Information -->
                    <div class="flex items-center gap-5">
                        <img src="{{ asset('asset/pic.jpg') }}" class="h-[80px] w-[80px] rounded-full" alt="">
                        <div>
                            <h3 class="text-lg font-semibold">Awais Ansari</h3>
                            <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                                +92 300 1234567
                            </p>
                            <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                                email@gmail.com
                            </p>
                        </div>
                    </div>

                    <!-- Button on the Right -->
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Action
                    </button>
                </div>
            </div>

            <div>
                <div id="accordion-collapse2" class="px-5 pt-5 clear-both" data-accordion="collapse">
                    <h2 id="accordion-collapse2-heading-1" class="border-b">
                        <button type="button"
                            class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                            data-accordion-target="#accordion-collapse2-body-1" aria-controls="accordion-collapse2-body-1">
                            <span class="text-2xl font-semibold">Address Book</span>
                            <svg data-accordion-icon class="w-3 h-3 shrink-0" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5 5 1 1 5" />
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse2-body-1" class="hidden border-b"
                        aria-labelledby="accordion-collapse2-heading-1">
                        <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                            <table class="w-full text-left">
                                <tbody>
                                    <tr>
                                        <th class="w-[12%] px-2 py-2 text-lg text-gray-500 dark:text-gray-400">Address 1:
                                        </th>
                                        <td class="px-2 py-2">
                                            <span class="font-semibold">Noman Ahmad</span>
                                            <br>
                                            Thewebconcept Chenab Market, Madina Town Faisalabad
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="w-[12%] px-2 py-2 text-lg text-gray-500 dark:text-gray-400">Address 2:
                                        </th>
                                        <td class="px-2 py-2">
                                            <span class="font-semibold">Noman Ahmad</span>
                                            <br>
                                            Thewebconcept Chenab Market, Madina Town Faisalabad
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="grid grid-cols-3 gap-4 p-5">
            <div class="col-span-2">
                <div id="accordion-collapse" data-accordion="collapse">
                    <h2 id="accordion-collapse-heading-1" class="border-b">
                        <button type="button"
                            class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 bg-transparent border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                            data-accordion-target="#accordion-collapse-body-1" aria-expanded="true"
                            aria-controls="accordion-collapse-body-1">
                            <span class="text-2xl font-semibold text-gray-500">Product List</span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 text-gray-500 shrink-0" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5 5 1 1 5" />
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                        @php
                            $headers = ['ID', 'Image', 'Product', 'Quantity', 'Price', 'Rating', 'Status', 'Action'];
                        @endphp

                        <x-table :headers="$headers">
                            <x-slot name="tablebody">

                                <tr>
                                    <td>1</td>
                                    <td>
                                        <img class="rounded-full w-11 h-11" src="{{ asset('asset/Ellipse 2.png') }}"
                                            alt="Jese image">
                                    </td>
                                    <td>Product Title</td>
                                    <td>500</td>
                                    <td>RS150</td>
                                    <td>4</td>
                                    <td><span
                                            class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Ongoing</span>
                                    </td>
                                    <td>
                                        <span class='flex gap-4'>
                                            <button class="viewModalBtn" id="viewModalBtn"
                                                data-modal-target="view-product-modal"
                                                data-modal-toggle="view-product-modal">
                                                <svg width='37' height='36' viewBox='0 0 37 36' fill='none'
                                                    xmlns='http://www.w3.org/2000/svg'>
                                                    <path fill-rule='evenodd' clip-rule='evenodd'
                                                        d='M28.0642 18.5C28.0642 18.126 27.8621 17.8812 27.4579 17.3896C25.9788 15.5938 22.7163 12.25 18.9288 12.25C15.1413 12.25 11.8788 15.5938 10.3996 17.3896C9.99542 17.8812 9.79333 18.126 9.79333 18.5C9.79333 18.874 9.99542 19.1187 10.3996 19.6104C11.8788 21.4062 15.1413 24.75 18.9288 24.75C22.7163 24.75 25.9788 21.4062 27.4579 19.6104C27.8621 19.1187 28.0642 18.874 28.0642 18.5ZM18.9288 21.625C19.7576 21.625 20.5524 21.2958 21.1385 20.7097C21.7245 20.1237 22.0538 19.3288 22.0538 18.5C22.0538 17.6712 21.7245 16.8763 21.1385 16.2903C20.5524 15.7042 19.7576 15.375 18.9288 15.375C18.0999 15.375 17.3051 15.7042 16.719 16.2903C16.133 16.8763 15.8038 17.6712 15.8038 18.5C15.8038 19.3288 16.133 20.1237 16.719 20.7097C17.3051 21.2958 18.0999 21.625 18.9288 21.625Z'
                                                        fill='url(#paint0_linear_872_5570)' />
                                                    <circle opacity='0.1' cx='18.4287' cy='18' r='18'
                                                        fill='url(#paint1_linear_872_5570)' />
                                                    <defs>
                                                        <linearGradient id='paint0_linear_872_5570' x1='18.9288'
                                                            y1='12.25' x2='18.9288' y2='24.75'
                                                            gradientUnits='userSpaceOnUse'>
                                                            <stop stop-color='#FCB376' />
                                                            <stop offset='1' stop-color='#FE8A29' />
                                                        </linearGradient>
                                                        <linearGradient id='paint1_linear_872_5570' x1='18.4287'
                                                            y1='0' x2='18.4287' y2='36'
                                                            gradientUnits='userSpaceOnUse'>
                                                            <stop stop-color='#FCB376' />
                                                            <stop offset='1' stop-color='#FE8A29' />F
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                            </x-slot>
                        </x-table>
                    </div>

                </div>
            </div>
            <div class="col-span-1 p-4 border rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold mb-4">Queries</h3>

                <!-- Query Item -->
                <div class="flex justify-between px-3 py-2 border-b items-center">
                    <div class="w-[80%]">
                        <p class="text-sm text-gray-700">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        </p>
                        <span class="text-xs text-gray-500">Seller</span>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Pending</span>
                        <span class="text-xs text-gray-500 mt-1">20 Jan, 2024</span>
                    </div>
                </div>

                <!-- Query Item -->
                <div class="flex justify-between px-3 py-2 border-b items-center">
                    <div class="w-[80%]">
                        <p class="text-sm text-gray-700">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        </p>
                        <span class="text-xs text-gray-500">Freelancer</span>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded">Approved</span>
                        <span class="text-xs text-gray-500 mt-1">20 Jan, 2024</span>
                    </div>
                </div>

                <!-- Query Item -->
                <div class="flex justify-between px-3 py-2 border-b items-center">
                    <div class="w-[80%]">
                        <p class="text-sm text-gray-700">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        </p>
                        <span class="text-xs text-gray-500">Admin</span>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">Rejected</span>
                        <span class="text-xs text-gray-500 mt-1">20 Jan, 2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
