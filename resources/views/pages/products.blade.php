@extends('layout')
@section('title', 'Products')
@section('nav-title', 'Products')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Product List</h1>
                <button id="addModalBtn" data-modal-target="product-modal" data-modal-toggle="product-modal"
                    class="px-5 py-3 font-semibold text-white rounded-full bg-primary">Add New<b/utton>

        </div>

        <div class="my-5 px-5">
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="me-2">
                    <a href="#" class="border inline-block px-4 py-1 text-white bg-primary rounded-3xl active"
                        aria-current="page">All</a>
                </li>
                <li class="me-2">
                    <a href="#"
                        class="border inline-block px-4 py-1 rounded-3xl hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Boosted</a>
                </li>
            </ul>
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
                'Boosted',
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
                    <td><span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded">Boosted</span></td>
                    <td>4</td>
                    <td>4</td>
                    <td><span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span></td>
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

                            <button class="viewModalBtn" id="viewModalBtn" data-modal-target="view-product-modal"
                                data-modal-toggle="view-product-modal">
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


        <x-modal id="product-modal">
            <x-slot name="title">Add Product </x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="">
                    <div class="">
                        <form>
                            @csrf
                            <div class="p-4 md:p-5">
                                <div class="flex items-start gap-4">
                                    <!-- Main Image Upload -->
                                    <div class="w-1/3">
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
                                    <!-- Sub Images and Video Upload -->
                                    <div class="grid flex-1 grid-cols-2 gap-4 md:grid-cols-4">
                                        <div
                                            class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                        <div
                                            class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <input type="file" class="hidden">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                            </label>
                                        </div>
                                        <div
                                            class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                        <div class="">
                                            <div
                                                class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                                <label class="font-medium text-blue-600 cursor-pointer">
                                                    <div class="flex justify-center align-middle">
                                                        <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                    </div>
                                                    <span class="block">Video</span>
                                                    <input type="file" class="hidden">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="mt-6">
                                    <x-input type="text" name="title" id="title" label="Title"
                                        placeholder="Enter Title" />
                                </div>

                                <!-- Description -->
                                <div class="mt-4">
                                    <label class="block font-medium text-gray-700">Description</label>
                                    <textarea placeholder="Enter Description here " class="w-full h-32 p-2 mt-1 border border-gray-300 rounded-lg "></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <!-- Brand / Company -->
                                    <div>
                                        <x-input type="text" name="company" id="company" label="Brand / Company"
                                            placeholder="Enter Here" />
                                    </div>

                                    <!-- Category -->
                                    <div>
                                        <label class="block font-medium text-gray-700">Category</label>
                                        <select class="w-full p-2 mt-1 border border-gray-300 rounded-lg ">
                                            <option>Options</option>
                                        </select>
                                    </div>

                                    <!-- Available Stock -->
                                    <div>
                                        <label class="block font-medium text-gray-700">Available Stock</label>
                                        <input placeholder="Enter Here" type="text"
                                            class="w-full p-2 mt-1 border border-gray-300 rounded-lg ">
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <label class="block font-medium text-gray-700">Price</label>
                                        <input placeholder="Enter Here" type="text"
                                            class="w-full p-2 mt-1 border border-gray-300 rounded-lg ">
                                    </div>

                                    <!-- Discount -->
                                    <div>
                                        <label class="block font-medium text-gray-700">Discount %</label>
                                        <input placeholder="Enter Here" type="text"
                                            class="w-full p-2 mt-1 border border-gray-300 rounded-lg ">
                                    </div>
                                </div>

                                <!-- Color Family and Weight -->
                                <div class="grid grid-cols-1 gap-4 mt-4">
                                    <div class="flex items-center">
                                        <div class="flex-3">
                                            <label class="block font-medium text-gray-700">Color Family</label>
                                            <input type="text" id="counter-input1" data-input-counter
                                                placeholder="vgvhh"
                                                class="w-full p-2 mt-1 border border-gray-300 rounded-lg ">
                                        </div>
                                        <button type="button" id="increment-button"
                                            data-input-counter-increment="counter-input1"
                                            class="inline-flex items-center justify-center flex-shrink-0 mt-6 ml-2 border border-gray-300 rounded-md w-7 h-7 bg-customblue">
                                            <svg class="w-5 h-5 p-1 text-gray-900 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M9 1v16M1 9h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex-3">
                                            <label class="block font-medium text-gray-700">Weight</label>
                                            <input type="text" id="counter-input" data-input-counter
                                                placeholder="vgvhh" value="2" required
                                                class="w-full p-2 mt-1 border border-gray-300 rounded-lg">
                                        </div>
                                        <button type="button" id="increment-button"
                                            data-input-counter-increment="counter-input"
                                            class="inline-flex items-center justify-center flex-shrink-0 mt-6 ml-2 border border-gray-300 rounded-md w-7 h-7 bg-customblue">
                                            <svg class="w-5 h-5 p-1 text-gray-900 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M9 1v16M1 9h16" />
                                            </svg>
                                        </button>
                                    </div>
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
                </div>
            </x-slot>
        </x-modal>

        {{-- view product detail --}}
        <x-modal id="view-product-modal">
            <x-slot name="title">Details </x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="">
                    <div class="">
                        <form>
                            @csrf
                            <div class="md:p-5">
                                <div class="flex items-start gap-4">
                                    <!-- Main Image Upload -->
                                    <div class="w-1/3">
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
                                    <!-- Sub Images and Video Upload -->
                                    <div class="grid flex-1 grid-cols-2 gap-4 md:grid-cols-4">
                                        <div
                                            class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                        <div
                                            class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <input type="file" class="hidden">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                            </label>
                                        </div>
                                        <div
                                            class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                            <label class="font-medium text-blue-600 cursor-pointer">
                                                <div class="flex justify-center align-middle">
                                                    <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                </div>
                                                <span class="block">Upload</span>
                                                <input type="file" class="hidden">
                                            </label>
                                        </div>
                                        <div class="">
                                            <div
                                                class="flex items-center justify-center h-24 bg-gray-300 border-2 border-gray-300 rounded-sm">
                                                <label class="font-medium text-blue-600 cursor-pointer">
                                                    <div class="flex justify-center align-middle">
                                                        <img src="{{ asset('asset/Vector (1).svg') }}" alt="">
                                                    </div>
                                                    <span class="block">Video</span>
                                                    <input type="file" class="hidden">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="grid grid-cols-2 mt-6 gap-y-4">
                                    <div class="space-y-4 ">
                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Title</div>
                                            <div>1231231</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Rating</div>
                                            <div>4.5</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Brand</div>
                                            <div>store</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Stock</div>
                                            <div>1234</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Discount %</div>
                                            <div>31%</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Color Family</div>
                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">c1</span>
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">c2</span>
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">red</span>
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">green</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Weight</div>
                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">1.4kg</span>
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">4.5kg</span>
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">10kg</span>
                                                <span
                                                    class="px-3 py-1 text-sm border-2 border-gray-100 rounded-full">15kg</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Orders</div>
                                            <div>56</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Category</div>
                                            <div>1231231</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Old Price</div>
                                            <div>$78</div>
                                        </div>

                                        <div class="flex items-center gap-8 align-middle">
                                            <div class="text-sm text-gray-500">Price</div>
                                            <div>$34</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-center gap-8 mt-4 space-y-2">
                                    <div class="text-sm text-gray-500">Description</div>
                                    <div>
                                        <p class="text-sm">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In, iure minus error
                                            doloribus saepe natus.
                                        </p>
                                        <p class="text-sm font-medium">Lorem ipsum dolor sit amet</p>
                                        <p class="text-sm">
                                            consectetur adipisicing elit. In, iure minus error doloribus saepe natus. Lorem
                                            ipsum dolor sit amet consectetur adipisicing elit. In, iure minus error
                                            doloribus
                                            saepe natus. Lorem ipsum dolor sit amet consectetur adipisicing elit. In, iure
                                            minus
                                            error doloribus saepe natus.
                                        </p>
                                    </div>
                                </div>
                                <form class="max-w-sm mx-auto">

                                </form>
                                <div class="flex items-center mt-5 mb-4">
                                    <label class="mr-6 text-sm font-normal text-gray-600">Status</label>
                                    <div>
                                        <select id="countries"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected>Select the Status</option>
                                            <option value="US">approved</option>
                                            <option value="CA">Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="mt-6 bg-gray-300 rounded-b-xl">
                                <div class="flex items-center justify-between p-2">
                                    <button type="button"
                                        class="px-3 py-1.5 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                                        Close
                                    </button>
                                    {{-- <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-lg">
                                        Add
                                    </button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-slot>
        </x-modal>
    </div>


@endsection
