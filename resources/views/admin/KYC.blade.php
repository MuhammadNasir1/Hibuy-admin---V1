@extends('layout')
@section('title', 'KYC')
@section('nav-title', 'KYC (Know Your Customer)')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium  ">KYC List</h1>
        </div>
        @php
            $headers = ['ID', 'Image', 'Name', 'Type', 'Submission Date', 'Steps Approved', 'Status', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @foreach ($sellers as $data)
                    <tr>
                        <td>1</td>
                        <td>
                            <img class="rounded-full w-11 h-11" src="{{ asset('asset/Ellipse 2.png') }}" alt="Jese image">
                        </td>
                        <td>{{ $data->user_name }}</td>
                        <td>{{ $data->user_role }}</td>
                        <td>{{ $data->submission_date }}</td>
                        <td>{{ $data->steps_progress }}</td>

                        <td><span
                                class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">{{ $data->status }}</span>
                        </td>
                        <td>
                            <span class='flex gap-4'>
                                <button class="viewModalBtn" data-modal-target="KYC-modal" data-modal-toggle="KYC-modal">
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


        <x-modal id="KYC-modal">
            <x-slot name="title">Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form>
                    @csrf

                    <div class="p-6 ">
                        <!-- Header -->

                        <div class="mb-3">
                            <button type="button" onclick="toggleDropdown()"
                                class="flex items-center justify-between w-full px-1 py-2 text-sm font-semibold text-left text-gray-700 transition duration-300 rounded-lg dropdownButton ">
                                <div class="flex items-center gap-2 ">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="20" height="20" rx="10" fill="#4B91E1" />
                                        <rect x="1" y="1" width="20" height="20" rx="10" stroke="white"
                                            stroke-width="2" />
                                        <path
                                            d="M11 5C14.3138 5 17 7.6862 17 11C17 14.3138 14.3138 17 11 17C7.6862 17 5 14.3138 5 11C5 7.6862 7.6862 5 11 5ZM11 6.2C9.72696 6.2 8.50606 6.70571 7.60589 7.60589C6.70571 8.50606 6.2 9.72696 6.2 11C6.2 12.273 6.70571 13.4939 7.60589 14.3941C8.50606 15.2943 9.72696 15.8 11 15.8C12.273 15.8 13.4939 15.2943 14.3941 14.3941C15.2943 13.4939 15.8 12.273 15.8 11C15.8 9.72696 15.2943 8.50606 14.3941 7.60589C13.4939 6.70571 12.273 6.2 11 6.2ZM11 7.4C11.147 7.40002 11.2888 7.45397 11.3986 7.55163C11.5084 7.64928 11.5786 7.78385 11.5958 7.9298L11.6 8V10.7516L13.2242 12.3758C13.3318 12.4838 13.3943 12.6287 13.3989 12.781C13.4036 12.9334 13.3501 13.0818 13.2493 13.1962C13.1484 13.3105 13.0079 13.3822 12.8561 13.3966C12.7044 13.4111 12.5528 13.3672 12.4322 13.274L12.3758 13.2242L10.5758 11.4242C10.4825 11.3309 10.4227 11.2094 10.4054 11.0786L10.4 11V8C10.4 7.84087 10.4632 7.68826 10.5757 7.57574C10.6883 7.46321 10.8409 7.4 11 7.4Z"
                                            fill="white" />
                                    </svg>
                                    <h1 class="text-lg font-semibold">Personal Information</h1>
                                </div>
                                <svg id=""
                                    class="w-5 h-5 transition-transform duration-300 transform dropdownArrow" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="" class="hidden mt-2 dropdownContent">
                                <div class="overflow-x-auto">
                                    <div class="flex justify-center mb-6">
                                        <div
                                            class="relative flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full cursor-pointer">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block text-sm">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Form Grid -->
                                    <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                                        <div><strong>Full Name:</strong> Full Name</div>
                                        <div><strong>Email:</strong> mail@gmail.com</div>
                                        <div><strong>Phone No:</strong> +923001234567</div>
                                        <div><strong>Address:</strong> Address </div>
                                        <div><strong>CNIC:</strong> 000000-0000000-0</div>
                                    </div>

                                    <!-- Document Images -->
                                    <div class="grid grid-cols-1 gap-4 mt-5 mb-6 md:grid-cols-2">
                                        <!-- Front Image -->
                                        <div>
                                            <label class="block mb-1 text-sm font-bold text-gray-700">Front Image</label>
                                            <div
                                                class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                                <label class="font-medium text-blue-600 cursor-pointer">
                                                    <div class="flex justify-center align-middle">
                                                        <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                    </div>
                                                    <span class="block">Upload</span>
                                                    <input type="file" class="hidden">
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Back Image -->
                                        <div>
                                            <label class="block mb-1 text-sm font-bold text-gray-700">Back Image</label>
                                            <div
                                                class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                                <label class="font-medium text-blue-600 cursor-pointer">
                                                    <div class="flex justify-center align-middle">
                                                        <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                    </div>
                                                    <span class="block">Upload</span>
                                                    <input type="file" class="hidden">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex justify-end gap-2 mb-6">
                                        <button class="px-4 py-2 border rounded-full hover:bg-gray-50">Reject</button>
                                        <button
                                            class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-600">Approve</button>
                                    </div>

                                    <!-- Reason Input -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-gray-700">Reason</label>
                                        <input type="text" placeholder="Enter here"
                                            class="w-full p-2 mb-2 border rounded-md">
                                        <div class="flex justify-end mt-4">
                                            <button
                                                class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-600">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img class="w-full px-8" src="{{ asset('asset/Line 49.png') }}" alt="">
                    </div>
                    <div class="p-6 ">
                        <!-- Header -->

                        <div class="mb-3">
                            <button type="button" onclick="toggleDropdown()"
                                class="flex items-center justify-between w-full px-1 py-2 text-sm font-semibold text-left text-gray-700 transition duration-300 rounded-lg dropdownButton ">
                                <div class="flex items-center gap-2 ">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="20" height="20" rx="10" fill="#FFAE42" />
                                        <rect x="1" y="1" width="20" height="20" rx="10" stroke="white"
                                            stroke-width="2" />
                                        <path
                                            d="M11.7691 5.12765L16.881 13.9814C16.959 14.1164 17 14.2696 17 14.4255C17 14.5814 16.959 14.7345 16.881 14.8695C16.8031 15.0045 16.691 15.1167 16.556 15.1946C16.421 15.2725 16.2678 15.3136 16.1119 15.3136H5.88808C5.73219 15.3136 5.57904 15.2725 5.44404 15.1946C5.30903 15.1167 5.19692 15.0045 5.11898 14.8695C5.04103 14.7345 5 14.5814 5 14.4255C5 14.2696 5.04104 14.1164 5.11898 13.9814L10.2309 5.12765C10.5725 4.53558 11.4269 4.53558 11.7691 5.12765ZM11 6.16376L6.40081 14.1295H15.5992L11 6.16376ZM11 12.1448C11.157 12.1448 11.3076 12.2072 11.4187 12.3183C11.5297 12.4293 11.5921 12.5799 11.5921 12.7369C11.5921 12.8939 11.5297 13.0445 11.4187 13.1556C11.3076 13.2666 11.157 13.329 11 13.329C10.843 13.329 10.6924 13.2666 10.5813 13.1556C10.4703 13.0445 10.4079 12.8939 10.4079 12.7369C10.4079 12.5799 10.4703 12.4293 10.5813 12.3183C10.6924 12.2072 10.843 12.1448 11 12.1448ZM11 8.00036C11.157 8.00036 11.3076 8.06274 11.4187 8.17377C11.5297 8.28481 11.5921 8.4354 11.5921 8.59243V10.9607C11.5921 11.1177 11.5297 11.2683 11.4187 11.3794C11.3076 11.4904 11.157 11.5528 11 11.5528C10.843 11.5528 10.6924 11.4904 10.5813 11.3794C10.4703 11.2683 10.4079 11.1177 10.4079 10.9607V8.59243C10.4079 8.4354 10.4703 8.28481 10.5813 8.17377C10.6924 8.06274 10.843 8.00036 11 8.00036Z"
                                            fill="white" />
                                    </svg>
                                    <h1 class="text-lg font-semibold">Store Information</h1>
                                </div>
                                <svg id=""
                                    class="w-5 h-5 transition-transform duration-300 transform dropdownArrow"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="" class="hidden mt-2 dropdownContent">
                                <div class="overflow-x-auto">
                                    <div class="flex justify-center mb-6">
                                        <div
                                            class="relative flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full cursor-pointer">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block text-sm">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Form Grid -->
                                    <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                                        <div><strong>Store Name:</strong> Name Here</div>
                                        <div><strong>Type:</strong> individual</div>
                                        <div><strong>Phone No:</strong> +923001234567</div>
                                        <div><strong>Email:</strong> mail@gmail.com </div>
                                        <div><strong>Country:</strong> Pakistan</div>
                                        <div><strong>Province:</strong> Punjab</div>
                                        <div><strong>City:</strong> Faisalabad</div>
                                        <div><strong>Zipcode :</strong> 000000</div>
                                        <div><strong>Pin Location:</strong> Location Here</div>
                                        <div><strong>Address:</strong>Address 123, City, Province
                                            Pakistan</div>
                                    </div>
                                </div>

                                <div class="flex items-center mt-3 text-gray-700 md:col-span-4 grid-cols-l">
                                    <strong class="">Category</strong>
                                    <div class="grid items-center grid-cols-4 gap-4 ms-5">
                                        <div class="px-3 py-1 text-center text-[1.3vw] text-gray-500 border rounded-full">
                                            Category1</div>
                                        <div class="px-3 py-1 text-center text-[1.3vw] text-gray-500 border rounded-full">
                                            Category2</div>
                                        <div class="px-3 py-1 text-center text-[1.3vw] text-gray-500 border rounded-full">
                                            Category3</div>
                                        <div class="px-3 py-1 text-center text-[1.3vw] text-gray-500 border rounded-full">
                                            Category4</div>
                                    </div>
                                </div>
                                <!-- Document Images -->
                                <div class="grid grid-cols-1 gap-4 mt-5 mb-6 md:grid-cols-2">
                                    <!-- Front Image -->
                                    <div>
                                        <label class="block mb-1 text-sm font-bold text-gray-700">Shop Image</label>
                                        <div
                                            class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Back Image -->
                                    <div>
                                        <label class="block mb-1 text-sm font-bold text-gray-700">Shop Video</label>
                                        <div
                                            class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-2 mb-6">
                                    <button class="px-4 py-2 border rounded-full hover:bg-gray-50">Reject</button>
                                    <button
                                        class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-600">Approve</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img class="w-full px-8" src="{{ asset('asset/Line 49.png') }}" alt="">
                    </div>
                    <div class="p-6 ">
                        <!-- Header -->

                        <div class="mb-3">
                            <button type="button" onclick="toggleDropdown()"
                                class="flex items-center justify-between w-full px-1 py-2 text-sm font-semibold text-left text-gray-700 transition duration-300 rounded-lg dropdownButton ">
                                <div class="flex items-center gap-2 ">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="20" height="20" rx="10" fill="#29B126" />
                                        <rect x="1" y="1" width="20" height="20" rx="10" stroke="white"
                                            stroke-width="2" />
                                        <path
                                            d="M10.7488 12.6446C10.3583 13.0352 9.72503 13.0352 9.3345 12.6446L8.1129 11.4227C7.89993 11.2097 7.89995 10.8644 8.11294 10.6514C8.32593 10.4384 8.67124 10.4384 8.88426 10.6513L10.0417 11.8083L12.7408 9.10863C12.954 8.89547 13.2995 8.89545 13.5127 9.1086C13.7258 9.32171 13.7258 9.66725 13.5127 9.88038L10.7488 12.6446Z"
                                            fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5 11C5 7.68636 7.68636 5 11 5C14.3136 5 17 7.68636 17 11C17 14.3136 14.3136 17 11 17C7.68636 17 5 14.3136 5 11ZM11 15.9091C10.3553 15.9091 9.71697 15.7821 9.12137 15.5354C8.52577 15.2887 7.9846 14.9271 7.52875 14.4713C7.0729 14.0154 6.7113 13.4742 6.46459 12.8786C6.21789 12.283 6.09091 11.6447 6.09091 11C6.09091 10.3553 6.21789 9.71697 6.46459 9.12137C6.7113 8.52577 7.0729 7.9846 7.52875 7.52875C7.9846 7.0729 8.52577 6.7113 9.12137 6.46459C9.71697 6.21789 10.3553 6.09091 11 6.09091C12.302 6.09091 13.5506 6.60812 14.4713 7.52875C15.3919 8.44938 15.9091 9.69803 15.9091 11C15.9091 12.302 15.3919 13.5506 14.4713 14.4713C13.5506 15.3919 12.302 15.9091 11 15.9091Z"
                                            fill="white" />
                                    </svg>
                                    <h1 class="text-lg font-semibold">Address Information</h1>
                                </div>
                                <svg id=""
                                    class="w-5 h-5 transition-transform duration-300 transform dropdownArrow"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="" class="hidden mt-2 dropdownContent">
                                <div class="overflow-x-auto">
                                    <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                                        <div><strong>Country:</strong> Pakistan</div>
                                        <div><strong>Province :</strong> Punjab</div>
                                        <div><strong>City:</strong> Faisalabad</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 mt-5 mb-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-1 text-sm font-bold text-gray-700">Shop / Home Bill</label>
                                        <div
                                            class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Back Image -->
                                    <div>
                                        <label class="block mb-1 text-sm font-bold text-gray-700">Shop Video</label>
                                        <div
                                            class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-2 mb-6">
                                    <button class="px-4 py-2 border rounded-full hover:bg-gray-50">Reject</button>
                                    <button
                                        class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-600">Approve</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img class="w-full px-8" src="{{ asset('asset/Line 49.png') }}" alt="">
                    </div>
                    <div class="p-6 ">
                        <!-- Header -->

                        <div class="mb-3">
                            <button type="button" onclick="toggleDropdown()"
                                class="flex items-center justify-between w-full px-1 py-2 text-sm font-semibold text-left text-gray-700 transition duration-300 rounded-lg dropdownButton ">
                                <div class="flex items-center gap-2 ">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="20" height="20" rx="10" fill="#4B91E1" />
                                        <rect x="1" y="1" width="20" height="20" rx="10" stroke="white"
                                            stroke-width="2" />
                                        <path
                                            d="M11 5C14.3138 5 17 7.6862 17 11C17 14.3138 14.3138 17 11 17C7.6862 17 5 14.3138 5 11C5 7.6862 7.6862 5 11 5ZM11 6.2C9.72696 6.2 8.50606 6.70571 7.60589 7.60589C6.70571 8.50606 6.2 9.72696 6.2 11C6.2 12.273 6.70571 13.4939 7.60589 14.3941C8.50606 15.2943 9.72696 15.8 11 15.8C12.273 15.8 13.4939 15.2943 14.3941 14.3941C15.2943 13.4939 15.8 12.273 15.8 11C15.8 9.72696 15.2943 8.50606 14.3941 7.60589C13.4939 6.70571 12.273 6.2 11 6.2ZM11 7.4C11.147 7.40002 11.2888 7.45397 11.3986 7.55163C11.5084 7.64928 11.5786 7.78385 11.5958 7.9298L11.6 8V10.7516L13.2242 12.3758C13.3318 12.4838 13.3943 12.6287 13.3989 12.781C13.4036 12.9334 13.3501 13.0818 13.2493 13.1962C13.1484 13.3105 13.0079 13.3822 12.8561 13.3966C12.7044 13.4111 12.5528 13.3672 12.4322 13.274L12.3758 13.2242L10.5758 11.4242C10.4825 11.3309 10.4227 11.2094 10.4054 11.0786L10.4 11V8C10.4 7.84087 10.4632 7.68826 10.5757 7.57574C10.6883 7.46321 10.8409 7.4 11 7.4Z"
                                            fill="white" />
                                    </svg>
                                    <h1 class="text-lg font-semibold">Business Information</h1>
                                </div>
                                <svg id=""
                                    class="w-5 h-5 transition-transform duration-300 transform dropdownArrow"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="" class="hidden mt-2 dropdownContent">
                                <div class="overflow-x-auto">
                                    <div class="flex justify-center mb-6">
                                        <div
                                            class="relative flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full cursor-pointer">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block text-sm">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Form Grid -->
                                    <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                                        <div><strong>Business Name:</strong>Name Here</div>
                                        <div><strong>Owner Name:</strong>Name Here</div>
                                        <div><strong>Phone No:</strong> +923001234567</div>
                                        <div><strong>Reg. No:</strong>1234567890</div>
                                        <div><strong>Tax. No. :</strong> 1234567890</div>
                                        <div><strong>Pin Location:</strong> Location Here</div>
                                        <div><strong>Address:</strong>Address 123, City, Province
                                            Pakistan</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 mt-5 mb-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-1 text-sm font-bold text-gray-700">Location Here</label>
                                        <div
                                            class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-bold text-gray-700">Stamp</label>
                                        <div
                                            class="relative flex items-center justify-center bg-gray-200 rounded-lg aspect-video">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-2 mb-6">
                                    <button class="px-4 py-2 border rounded-full hover:bg-gray-50">Reject</button>
                                    <button
                                        class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-600">Approve</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 bg-gray-300 rounded-b-xl">
                        <div class="flex items-center justify-between p-2">
                            <button type="button"
                                class="px-3 py-1.5 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                                Close
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
        function viewData() {

            $('.viewModalBtn').click(function() {
                $('#view-modal').addClass('flex').removeClass('hidden');
                $('#dTitle').text($(this).attr('mediaTitle'));
                $('#dAuthor').text($(this).attr('mediaAuthor'));
                $('#dCategory').text($(this).attr('mediaCategory'));
                $('#dDate').text($(this).attr('mediaDate'));
                $('#dDescription').text($(this).attr('mediaDescription'));
                $('#dImage').attr('src', $(this).attr('mediaImage'));

            });

        }
        viewData()

        function updateDatafun() {
            viewData()
            $('.updateDataBtn').click(function() {
                $('#blog-modal').removeClass("hidden").addClass('flex');

                let mediaDetails = $(this).siblings('.viewModalBtn');;
                $('#updateId').val(mediaDetails.attr('mediaId'));
                $('#mediaTitle').val(mediaDetails.attr('mediaTitle'));
                $('#mediaTitle').val(mediaDetails.attr('mediaTitle'));
                $('#mediaAuthor').val(mediaDetails.attr('mediaAuthor'));
                $('#categoryId').val(mediaDetails.attr('mediaCategoryId')).trigger('change');
                $('#mediaDescription').val(mediaDetails.attr('mediaDescription'));
                let fileImg = $('#blog-modal .file-preview');
                fileImg.removeClass('hidden').attr('src', mediaDetails.attr('mediaImage'));


                $('#blog-modal #modalTitle').text("Update Blog");
                $('#blog-modal #btnText').text("Update");

            });
        }
        updateDatafun();
        $('#addModalBtn').click(function() {
            $('#postDataForm')[0].reset();
            $('#categoryId').trigger('change');
            $('#updateId').val('');
            $('#blog-modal #modalTitle').text("Add Post");
            $('#blog-modal #btnText').text("Preview");
            let fileImg = $('#blog-modal .file-preview');
            fileImg.addClass('hidden');

        })


        // Listen for the custom form submission response event
        $(document).on("formSubmissionResponse", function(event, response, Alert, SuccessAlert, WarningAlert) {
            // console.log(response);
            if (response.success) {

                $('.modalCloseBtn').click();
            } else {}
        });
    </script>
    <script>
        const dropdownButtons = document.getElementsByClassName('dropdownButton');
        const dropdownContents = document.getElementsByClassName('dropdownContent');
        const dropdownArrows = document.getElementsByClassName('dropdownArrow');

        for (let i = 0; i < dropdownButtons.length; i++) {
            dropdownButtons[i].addEventListener('click', () => {
                dropdownContents[i].classList.toggle('hidden');
                dropdownArrows[i].classList.toggle('rotate-180');
            });
        }
    </script>
@endsection
