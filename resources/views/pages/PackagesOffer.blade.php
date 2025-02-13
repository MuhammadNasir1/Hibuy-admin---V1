@extends('layout')
@section('title', 'Packages Offer')
@section('nav-title', 'Packages Offer')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-3xl font-bold ">Packages Offer</h1>
                <button id="addModalBtn" data-modal-target="packages-modal" data-modal-toggle="packages-modal"
                    class="px-5 py-3 font-semibold text-white rounded-full bg-primary">Add New</button>

        </div>
        @php
            $headers = [
                'ID',
                'Image',
                'Title',
                'Category',
                'price',
                'Listed on',
                'Seller',
                'Orders',
                'Rating',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                <tr>
                    <td>1</td>
                    <td>
                        <img class="rounded-full w-11 h-11" src="{{ asset('asset/Ellipse 2.png') }}" alt="Jese image">
                    </td>
                    <td>Product Title</td>
                    <td>Clothes</td>
                    <td>RS150</td>
                    <td>Jan 2, 2024</td>
                    <td>Noman Ahmad</td>
                    <td>4</td>
                    <td>4</td>
                    <td>Approved</td>
                    <td>
                        <span class='flex gap-4'>
                            <button class="updateDataBtn">
                                <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                    xmlns='http://www.w3.org/2000/svg'>
                                    <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                    <path fill-rule='evenodd' clip-rule='evenodd'
                                        d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188ZM19.5958 22.8672H23.5929C23.9829 22.8672 24.3 23.1885 24.3 23.5835C24.3 23.9794 23.9829 24.2999 23.5929 24.2999H19.5958C19.2059 24.2999 18.8887 23.9794 18.8887 23.5835C18.8887 23.1885 19.2059 22.8672 19.5958 22.8672Z'
                                        fill='#233A85' />
                                </svg>
                            </button>
                            <button class="deleteDataBtn" delId="" delUrl="" name="media_id">
                                <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                    xmlns='http://www.w3.org/2000/svg'>
                                    <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                    <path fill-rule='evenodd' clip-rule='evenodd'
                                        d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                        fill='#D11A2A' />
                                </svg>
                            </button>

                            <button id="updatePackagesBtn" data-modal-target="view-packages-modal" data-modal-toggle="view-packages-modal"
                                class="viewModalBtn">
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


            </x-slot>
        </x-table>


        <x-modal id="packages-modal">
            <x-slot name="title">Add Packages</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div id="pkg-container" class="p-6 bg-white rounded-lg ">
                    <div class="flex gap-2 border-b">
                        <button id="product-pkg-btn"
                            class="px-6 py-2 font-medium text-customblue bg-customblue rounded-t-2xl ">
                            Products Pkg
                        </button>
                        <button id="custom-pkg-btn" class="px-6 py-2 font-medium text-customblue rounded-t-2xl ">
                            Custom Pkg
                        </button>
                    </div>
                </div>
                <div id="product-pkg-form" class="pkg-form">
                    <form>
                        @csrf
                        <div class="max-w-4xl px-6 mx-auto">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-3 ">
                                    <div
                                        class="flex items-center justify-center w-full md:h-full h-[150px] border-2 border-gray-300  rounded-lg bg-gray-300">
                                        <label class="font-medium text-blue-600 cursor-pointer">
                                            <div class="flex justify-center align-middle">
                                                <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                            </div>
                                            <span class="block">Upload</span>
                                            <input type="file" class="hidden">
                                        </label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 col-span-9 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Title</label>
                                        <input type="text" placeholder="Enter here"
                                            class="sm:w-full w-[82vw] px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                            <option>Options</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-12">
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea placeholder="Enter Description here"
                                        class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue"></textarea>
                                </div>

                                <!-- Stock and Discount -->
                                <div class="col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Available Stock</label>
                                    <input type="text" placeholder="Enter here"
                                        class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                </div>
                                <div class="col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Discount %</label>
                                    <input type="text" placeholder="Enter here"
                                        class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                </div>
                            </div>

                            <!-- Select Product Button -->
                            <div class="flex justify-end mt-4">
                                <button class="px-6 py-2 text-white bg-customblue rounded-3xl hover:bg-blue-600">Select
                                    Product</button>
                            </div>

                            <!-- Table -->
                            <div class="mt-6 overflow-y-scroll border-2 border-gray-300 ">
                                <table class="w-full text-left border-collapse">
                                    <div class="p-3 font-bold text-gray-500">
                                        <h1>Item Details</h1>
                                    </div>
                                    <thead>
                                        <tr class="text-gray-500 ">
                                            <th class="px-4 py-2">Image</th>
                                            <th class="px-4 py-2">Product Name</th>
                                            <th class="px-4 py-2">Qty</th>
                                            <th class="px-4 py-2">U.Price</th>
                                            <th class="px-4 py-2">Subtotal</th>
                                            <th class="px-4 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Row 1 -->
                                        <tr>
                                            <td class="px-4 py-2"><span class="text-gray-500"><img
                                                        src="{{ asset('asset/image 634.png') }}" alt=""></span>
                                            </td>
                                            <td class="px-4 py-2">Item</td>
                                            <td class="px-4 py-2">
                                                <input type="number" value="1"
                                                    class="w-16 text-center border border-gray-300 rounded-lg">
                                            </td>
                                            <td class="px-4 py-2">100</td>
                                            <td class="px-4 py-2">1100</td>
                                            <td class="px-4 py-2">
                                                <button class="deleteDataBtn" delId="" delUrl="../deleteMedia"
                                                    name="media_id">
                                                    <svg width='36' height='36' viewBox='0 0 36 36'
                                                        fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                        <circle opacity='0.1' cx='18' cy='18' r='18'
                                                            fill='#D11A2A' />
                                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                                            d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                                            fill='#D11A2A' />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Row 2 -->
                                        <tr>
                                            <td class="px-4 py-2"><span class="text-gray-500"><img
                                                        src="{{ asset('asset/image 634.png') }}" alt=""></span>
                                            </td>
                                            <td class="px-4 py-2">Item</td>
                                            <td class="px-4 py-2">
                                                <input type="number" value="5"
                                                    class="w-16 text-center border border-gray-300 rounded-lg">
                                            </td>
                                            <td class="px-4 py-2">100</td>
                                            <td class="px-4 py-2">500</td>
                                            <td class="px-4 py-2">
                                                <button class="deleteDataBtn" delId="" delUrl="../deleteMedia"
                                                    name="media_id">
                                                    <svg width='36' height='36' viewBox='0 0 36 36'
                                                        fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                        <circle opacity='0.1' cx='18' cy='18' r='18'
                                                            fill='#D11A2A' />
                                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                                            d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                                            fill='#D11A2A' />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-6 bg-gray-300 rounded-b-xl">
                            <div class="flex items-center justify-between p-2">
                                <button type="button"
                                    class="px-3 py-1.5 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                                    Close
                                </button>
                                <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-lg">
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="">
                    <div id="custom-pkg-form" class="hidden pkg-form">
                        <form>
                            @csrf
                            <div class="max-w-4xl px-6 mx-auto bg-white rounded-lg">
                                <div class="grid grid-cols-12 gap-6">
                                    <!-- Upload Section -->
                                    <div class="col-span-12 md:col-span-3 ">
                                        <div
                                            class="flex items-center justify-center w-full md:h-full h-[150px] border-2 border-gray-300  rounded-lg bg-gray-300">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Input Fields -->
                                    <div class="grid grid-cols-1 col-span-9 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Title</label>
                                            <input type="text" placeholder="Enter here"
                                                class="sm:w-full w-[82vw] px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                                            <select
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                                <option>Options</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-span-12">
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea placeholder="Enter Description here"
                                            class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue"></textarea>
                                    </div>

                                    <!-- Stock and Discount -->
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Available Stock</label>
                                        <input type="text" placeholder="Enter here"
                                            class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                    </div>
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Price</label>
                                        <input type="text" placeholder="Enter here"
                                            class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                    </div>
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Discount %</label>
                                        <input type="text" placeholder="Enter here"
                                            class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                    </div>
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Discount %</label>
                                        <input type="text" placeholder="00.00"
                                            class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customblue">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 bg-gray-300 rounded-b-xl">
                                <div class="flex items-center justify-between p-2">
                                    <button type="button"
                                        class="px-3 py-1.5 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                                        Close
                                    </button>
                                    <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-lg">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-slot>
        </x-modal>

        <x-modal id="view-packages-modal">
            <x-slot name="title">Details </x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div>
                    <form>
                        @csrf

                        <div class="max-w-4xl px-6 mx-auto mt-4">
                            <div class="w-full md:w-1/3">
                                <div
                                    class="flex items-center justify-center h-48 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                    <label class="font-medium text-blue-600 cursor-pointer">
                                        <div class="flex justify-center align-middle">
                                            <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                        </div>
                                        <span class="block">Upload</span>
                                        <input type="file" class="hidden">
                                    </label>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                                <div><strong>Title:</strong> <span class="">1231231</span></div><br>
                                <div><strong>Rating:</strong> --</div>
                                <div><strong>Orders:</strong> --</div>
                                <div><strong>Category:</strong> 1231231</div>
                                <div><strong>Stock:</strong> 1234 </div>
                                <div><strong>Old Price:</strong> $78</div>
                                <div><strong>Discount % :</strong> 31%</div>
                                <div><strong>Price:</strong> $34</div>
                            </div>
                            <div class="flex justify-center gap-8 mt-4 space-y-2">
                                <strong class="text-sm text-gray-700 ">Description</strong>
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. In, iure minus
                                        error
                                        doloribus saepe natus.
                                    </p>
                                    <p class="text-sm font-medium text-gray-700">Lorem ipsum dolor sit amet</p>
                                    <p class="text-sm text-gray-700">
                                        consectetur adipisicing elit. In, iure minus error doloribus saepe natus.
                                        Lorem
                                        ipsum dolor sit amet consectetur adipisicing elit. In, iure minus error
                                        doloribus
                                        saepe natus. Lorem ipsum dolor sit amet consectetur adipisicing elit. In,
                                        iure
                                        minus
                                        error doloribus saepe natus.
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="button"
                                    class="flex items-center justify-between w-full px-4 py-2 mt-4 text-sm font-semibold text-left text-gray-700 transition duration-300 bg-gray-100 rounded-lg dropdownButton hover:bg-gray-200">
                                    <span>Details</span>
                                    <svg class="w-5 h-5 transition-transform duration-300 transform dropdownArrow"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden mt-2 dropdownContent">
                                    <div class="overflow-x-auto ">
                                        <table class="w-full mt-2 text-sm text-gray-700 border">
                                            <thead>
                                                <tr class="bg-gray-200">
                                                    <th class="p-2 text-left">img</th>
                                                    <th class="p-2 text-left">Product</th>
                                                    <th class="p-2 text-center">Qty</th>
                                                    <th class="p-2 text-center">U.Price</th>
                                                    <th class="p-2 text-center">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="border-b">
                                                    <td class="p-2"><img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ asset('asset/image 634.png') }}" alt=""></td>
                                                    <td class="p-2">name</td>
                                                    <td class="p-2 text-center">1</td>
                                                    <td class="p-2 text-center">500.0</td>
                                                    <td class="p-2 text-center">500.0</td>
                                                </tr>
                                                <tr class="border-b">
                                                    <td class="p-2"><img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ asset('asset/image 634.png') }}" alt=""></td>
                                                    <td class="p-2">name</td>
                                                    <td class="p-2 text-center">2</td>
                                                    <td class="p-2 text-center">50.0</td>
                                                    <td class="p-2 text-center">100.0</td>
                                                </tr>
                                                <tr class="border-b">
                                                    <td class="p-2"><img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ asset('asset/image 634.png') }}" alt=""></td>
                                                    <td class="p-2">name</td>
                                                    <td class="p-2 text-center">5</td>
                                                    <td class="p-2 text-center">10.0</td>
                                                    <td class="p-2 text-center">100.0</td>
                                                </tr>
                                                <tr class="border-b">
                                                    <td class="p-2"><img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ asset('asset/image 634.png') }}" alt=""></td>
                                                    <td class="p-2">name</td>
                                                    <td class="p-2 text-center">1</td>
                                                    <td class="p-2 text-center">200.0</td>
                                                    <td class="p-2 text-center">200.0</td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2"><img class="object-cover w-10 h-10 rounded-full"
                                                            src="{{ asset('asset/image 634.png') }}" alt=""></td>
                                                    <td class="p-2">name</td>
                                                    <td class="p-2 text-center">2</td>
                                                    <td class="p-2 text-center">75.0</td>
                                                    <td class="p-2 text-center">150.0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 text-sm text-gray-700">
                                        <div class="flex justify-between"><span>Total Bill:</span>
                                            <span>1,050</span>
                                        </div>
                                        <div class="flex justify-between"><span>Delivery fee:</span>
                                            <span>50</span>
                                        </div>
                                        <div class="flex justify-between text-red-500"><span>Discount:</span>
                                            <span>-100</span>
                                        </div>
                                        <div class="flex justify-between mt-2 text-lg font-bold">
                                            <span>Total Amount:</span> <span>1,000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-1/3 mb-4">
                                <label class="text-sm text-gray-600">Status</label>
                                <select class="w-full p-2 mt-1 border rounded">
                                    <option selected>Status</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mt-6 bg-gray-300 rounded-b-xl">
                    <div class="flex items-center justify-between p-2">
                        <button type="button"
                            class="px-3 py-1.5 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                            Close
                        </button>
                    </div>
                </div>
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
        // JavaScript to toggle between forms
        const productPkgBtn = document.getElementById("product-pkg-btn");
        const customPkgBtn = document.getElementById("custom-pkg-btn");
        const productPkgForm = document.getElementById("product-pkg-form");
        const customPkgForm = document.getElementById("custom-pkg-form");

        productPkgBtn.addEventListener("click", () => {
            // Show product package form and hide custom package form
            productPkgForm.classList.remove("hidden");
            customPkgForm.classList.add("hidden");

            // Set active styles for the product package button
            productPkgBtn.classList.add("bg-customblue", "text-white");
            productPkgBtn.classList.remove("text-customblue");

            // Reset styles for the custom package button
            customPkgBtn.classList.remove("bg-customblue", "text-white");
            customPkgBtn.classList.add("text-customblue");
        });

        customPkgBtn.addEventListener("click", () => {
            // Show custom package form and hide product package form
            customPkgForm.classList.remove("hidden");
            productPkgForm.classList.add("hidden");

            // Set active styles for the custom package button
            customPkgBtn.classList.add("bg-customblue", "text-white");
            customPkgBtn.classList.remove("text-customblue");

            // Reset styles for the product package button
            productPkgBtn.classList.remove("bg-customblue", "text-white");
            productPkgBtn.classList.add("text-customblue");
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
