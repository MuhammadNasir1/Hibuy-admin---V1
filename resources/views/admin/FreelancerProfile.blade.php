@extends('layout')
@section('title', 'Freelancers Management')
@section('nav-title', 'Freelancers Management')
@section('content')
    <div class="w-full pt-10 pb-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div>
            <h3 class="mx-6 mt-3 text-2xl font-semibold">
                Freelancers Information
            </h3>
            <div class="h-[150px] border rounded-xl mx-6 mt-3 flex justify-center items-center">
                <div class="h-[80%] w-[95%] rounded-xl flex items-center justify-between">
                    <!-- Freelancers Information -->
                    <div class="flex items-center gap-5">
                        @php
                            $profilePath = $storeData['personal_info']['profile_picture'] ?? '';
                            $defaultProfile = asset('asset/pic.jpg');

                            $profileImage =
                                !empty($profilePath) && file_exists(public_path($profilePath))
                                    ? asset($profilePath)
                                    : $defaultProfile;
                        @endphp

                        <img src="{{ $profileImage }}" class="h-[80px] w-[80px] rounded-full" alt="">

                        <div>
                            <h3 class="text-lg font-semibold">{{ $storeData['personal_info']['full_name'] }} </h3>
                            {{-- <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                                {{ $storeData['personal_info']['phone_no'] }}
                            </p> --}}
                            <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                                {{ $storeData['personal_info']['email'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Button on the Right -->
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Manage
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="mx-6 mt-3 text-2xl font-semibold">
                Store Information
            </h3>
            <div
                class="h-[150px] shadow-xl bg-gradient-to-r from-[#4A90E2] rounded-xl mx-6 mt-3 via-green-300 to-[#FFCE31] flex justify-center items-center">
                <div class="h-[80%] w-[95%] bg-white rounded-xl flex items-center justify-between px-5">
                    <div class="flex items-center gap-5">
                        @php
                            $storeImagePath = $storeData['store_image'] ?? '';
                            $defaultStoreImage = asset('asset/pic.jpg');

                            $storeImage =
                                !empty($storeImagePath) && file_exists(public_path($storeImagePath))
                                    ? asset($storeImagePath)
                                    : $defaultStoreImage;
                        @endphp

                        <img src="{{ $storeImage }}" class="h-[80px] w-[80px] rounded-full" alt="">

                        <div>
                            <h3 class="text-lg font-semibold">{{ $storeData['store_name'] }}</h3>
                            {{-- <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                                <span>
                                    <svg class="h-[15px] fill-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M234.5 5.7c13.9-5 29.1-5 43.1 0l192 68.6C495 83.4 512 107.5 512 134.6l0 242.9c0 27-17 51.2-42.5 60.3l-192 68.6c-13.9 5-29.1 5-43.1 0l-192-68.6C17 428.6 0 404.5 0 377.4L0 134.6c0-27 17-51.2 42.5-60.3l192-68.6zM256 66L82.3 128 256 190l173.7-62L256 66zm32 368.6l160-57.1 0-188L288 246.6l0 188z" />
                                    </svg>
                                </span>70% positive seller rating
                            </p> --}}
                            <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                                <span>
                                    <svg class="h-[15px] fill-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M234.5 5.7c13.9-5 29.1-5 43.1 0l192 68.6C495 83.4 512 107.5 512 134.6l0 242.9c0 27-17 51.2-42.5 60.3l-192 68.6c-13.9 5-29.1 5-43.1 0l-192-68.6C17 428.6 0 404.5 0 377.4L0 134.6c0-27 17-51.2 42.5-60.3l192-68.6zM256 66L82.3 128 256 190l173.7-62L256 66zm32 368.6l160-57.1 0-188L288 246.6l0 188z" />
                                    </svg>
                                </span>
                                @if (!empty($storeData['store_tags']) && is_array($storeData['store_tags']))
                                    @foreach ($storeData['store_tags'] as $tag)
                                        <span
                                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $tag }}</span>
                                    @endforeach
                                @else
                                    <span>No tags available</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        View Profile
                    </button>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mx-6 mt-5 text-2xl font-semibold">
                Banners
            </h3>
        </div>
        <div class="mt-3 mx-6 shadow-xl rounded-xl min-h-[100px]">
            @php
                $bannerPath = $storeData['store_banner'] ?? '';
                $defaultBanner = asset('asset/banner.png');

                $bannerImage =
                    !empty($bannerPath) && file_exists(public_path($bannerPath)) ? asset($bannerPath) : $defaultBanner;
            @endphp

            <img src="{{ $bannerImage }}" alt="Banner Image" class="w-full object-cover rounded-xl">

        </div>

        <div class="flex justify-center px-6 gap-5 ">
            @foreach ($storeData['store_posts'] as $post)
                @php
                    $postImagePath = $post['image'] ?? '';
                    $defaultImage = asset('asset/post-1.png');

                    $bgImage =
                        !empty($postImagePath) && file_exists(public_path($postImagePath))
                            ? asset($postImagePath)
                            : $defaultImage;
                @endphp

                <div class="lg:h-[370px] h-[200px] lg:w-[370px] w-[200px] rounded-xl mt-5 shadow-xl"
                    style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center;">
                </div>
            @endforeach
        </div>



        <div id="accordion-collapse" class="p-5" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-1" class="border-b">
                <button type="button"
                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                    data-accordion-target="#accordion-collapse-body-1" aria-expanded="true"
                    aria-controls="accordion-collapse-body-1">
                    <span>Product List</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5 5 1 1 5" />
                    </svg>
                </button>
            </h2>
            <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                @php
                    $headers = [
                        'ID',
                        'Image',
                        'Title',
                        'Category',
                        'Discount %',
                        'Price',
                        'Boosted',
                        // 'Orders',
                        'Rating',
                        'Status',
                        'Action',
                    ];
                @endphp

                <x-table :headers="$headers">
                    <x-slot name="tablebody">
                        @foreach ($storeData['products'] as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @php
                                        $imagePath = $product['product_image'];
                                        $defaultImage = asset('asset/Ellipse 2.png');
                                        $finalImage =
                                            !empty($imagePath) && file_exists(public_path($imagePath))
                                                ? asset($imagePath)
                                                : $defaultImage;
                                    @endphp
                                    <img class="rounded-full w-11 h-11" src="{{ $finalImage }}" alt="Product image">
                                </td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['category_name'] }}</td>
                                <td>{{ $product['product_discount'] }} %</td>
                                <td class="whitespace-nowrap">Rs {{ $product['product_price'] }}</td>
                                <td>
                                    @if ($product['is_boosted'] == 1)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded">Boosted</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-white bg-gray-800 rounded">Regular</span>
                                    @endif
                                </td>
                                {{-- <td>500</td> --}}
                                <td>{{ $product['product_rating'] }}</td>
                                <td>
                                    @if ($product['product_status'] == 1)
                                        <span
                                            class=" px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span>
                                    @else
                                        <span
                                            class="whitespace-nowrap px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">Not
                                            Approved</span>
                                    @endif
                                </td>
                                <td>
                                    <span class='flex gap-4'>
                                        {{-- <button class="updateDataBtn">
                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                            xmlns='http://www.w3.org/2000/svg'>
                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188ZM19.5958 22.8672H23.5929C23.9829 22.8672 24.3 23.1885 24.3 23.5835C24.3 23.9794 23.9829 24.2999 23.5929 24.2999H19.5958C19.2059 24.2999 18.8887 23.9794 18.8887 23.5835C18.8887 23.1885 19.2059 22.8672 19.5958 22.8672Z'
                                                fill='#233A85' />
                                        </svg>
                                    </button> --}}
                                        {{-- <button class="deleteDataBtn" delId="" delUrl="" name="media_id">
                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                            xmlns='http://www.w3.org/2000/svg'>
                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                                fill='#D11A2A' />
                                        </svg>
                                    </button> --}}

                                        <button class="viewModalBtn" id="viewModalBtn"
                                            data-modal-target="view-product-modal" data-modal-toggle="view-product-modal">
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
                        @endforeach
                    </x-slot>
                </x-table>
            </div>

        </div>

    </div>

@endsection
