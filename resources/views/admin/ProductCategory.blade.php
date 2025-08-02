@extends('layout')
@section('title', 'Product Category')
@section('nav-title', 'Product Category')
@section('content')
    @php
        function renderCategoryOptions($categories, $depth = 0)
        {
            foreach ($categories as $category) {
                // Repeat '-' based on depth level
                $prefix = str_repeat('-', $depth * 1) . ' ';
                echo '<option value="' . $category->id . '">' . $prefix . e($category->name) . '</option>';

                if (!empty($category->children)) {
                    renderCategoryOptions($category->children, $depth + 1);
                }
            }
        }
    @endphp
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium">Product Category List</h1>
                <button id="addModalBtn" data-modal-target="productcategory-modal" data-modal-toggle="productcategory-modal"
                    class="px-3 py-2 font-semibold text-white rounded-full bg-primary">Add New</button>
        </div>
        @php
            $headers = ['ID', 'Image', 'Name', 'No. of Sub Category', 'No. of Products', 'Status', 'Action'];
        @endphp
        {{-- {{ dd($parentcategories) }} --}}
        <x-table :headers="$headers">
            <x-slot name="tablebody">

                @if (!empty($parentcategories))
                    @foreach ($parentcategories as $categorie)
                        @php
                            $imagePath = $categorie->image;
                            $defaultImage = asset('asset/Ellipse 2.png');
                            $finalImage = !empty($imagePath) && file_exists(public_path($imagePath))
                                ? asset($imagePath)
                                : $defaultImage;
                        @endphp

                        {{-- top-level row --}}
                        <tr>
                            <td style="padding: 8px 10px !important;" >{{ $loop->iteration }}</td>
                            <td style="padding: 8px 10px !important;">
                                <img class="rounded-full w-11 h-11" src="{{ $finalImage }}" alt="Product image">
                            </td>
                            <td class="capitalize">
                                @php
                                    $colors = [
                                        'bg-blue-500',
                                        'bg-indigo-500',
                                        'bg-purple-500',
                                        'bg-pink-500',
                                        'bg-yellow-500',
                                        'bg-green-500',
                                        'bg-red-500',
                                        'bg-orange-500'
                                    ];
                                    $badgeColor = $colors[0 % count($colors)];
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-semibold text-white rounded {{ $badgeColor }}">
                                    Lv1
                                </span>
                                <span class="ml-1">{{ $categorie->name }}</span>
                            </td style="padding: 8px 10px !important;">
                            <td style="padding: 8px 10px !important;">{{ $categorie->subcategory_count }}</td>
                            <td style="padding: 8px 10px !important;">{{ $categorie->product_count }}</td>
                            <td style="padding: 8px 10px !important;">
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span>
                            </td>
                            <td style="padding: 8px 10px !important;">
                                <span class='flex gap-4'>
                                    <button class="updateDataBtn" onclick="updateData({{ $categorie->id }})">
                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                            xmlns='http://www.w3.org/2000/svg'>
                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188ZM19.5958 22.8672H23.5929C23.9829 22.8672 24.3 23.1885 24.3 23.5835C24.3 23.9794 23.9829 24.2999 23.5929 24.2999H19.5958C19.2059 24.2999 18.8887 23.9794 18.8887 23.5835C18.8887 23.1885 19.2059 22.8672 19.5958 22.8672Z'
                                                fill='#233A85' />
                                        </svg>
                                    </button>
                                    <button class="" onclick="deleteCategory({{ $categorie->id }})">
                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                                fill='#D11A2A' />
                                        </svg>
                                    </button>

                                    <button class="viewModalBtn" data-id="{{ $categorie->id }}"
                                        data-modal-target="editproductcategory-modal"
                                        data-modal-toggle="editproductcategory-modal">
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
                                                    <stop offset='1' stop-color='#FE8A29' />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </button>
                                </span>
                            </td>
                        </tr>

                        {{-- check if has children, render recursively --}}
                        @if (!empty($categorie->children))
                            @php

                                $renderChildren = function($children, $depth = 1) use (&$renderChildren, $colors, $categorie) {
                                    foreach ($children as $child) {
                                        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
                                        $imagePath = $child->image;
                                        $parent_id = $child->parent_id;
                                        // echo $parent_id;
                                        $defaultImage = asset('asset/Ellipse 2.png');
                                        $finalImage = !empty($imagePath) && file_exists(public_path($imagePath))
                                            ? asset($imagePath)
                                            : $defaultImage;
                                        $badgeColor = $colors[$depth % count($colors)];
                                        $levelNumber = $depth + 1;
                            @endphp
                                        <tr>
                                            <td style="padding: 8px 10px !important;">—</td>
                                            <td style="padding: 8px 10px !important;">
                                                <img class="rounded-full w-11 h-11" src="{{ $finalImage }}" alt="Product image">
                                            </td>
                                            <td class="capitalize" style="padding: 8px 10px !important;">
                                                {!! $indent !!}
                                                <span class="px-2 py-0.5 text-xs font-semibold text-white rounded {{ $badgeColor }}">
                                                    Lv{{ $levelNumber }}
                                                </span>
                                                <span class="ml-1">{{ $child->name }}</span>
                                            </td>
                                            <td style="padding: 8px 10px !important;">{{ $child->subcategory_count }}</td>
                                            <td style="padding: 8px 10px !important;">{{ $child->product_count }}</td>
                                            <td style="padding: 8px 10px !important;">
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span>
                                            </td>
                                            <td style="padding: 8px 10px !important;">
                                                <span class='flex gap-4'>
                                                    <button class="updateDataBtn" onclick="updateData({{ $child->id }})">
                                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                                            xmlns='http://www.w3.org/2000/svg'>
                                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                                d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188ZM19.5958 22.8672H23.5929C23.9829 22.8672 24.3 23.1885 24.3 23.5835C24.3 23.9794 23.9829 24.2999 23.5929 24.2999H19.5958C19.2059 24.2999 18.8887 23.9794 18.8887 23.5835C18.8887 23.1885 19.2059 22.8672 19.5958 22.8672Z'
                                                                fill='#233A85' />
                                                        </svg>
                                                    </button>
                                                    <button class="" onclick="deleteSubCategory({{$parent_id}}, {{ $child->id }})">
                                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                                d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                                                fill='#D11A2A' />
                                                        </svg>
                                                    </button>

                                                    <button class="viewModalBtn" data-id="{{ $child->id }}"
                                                        data-modal-target="editproductcategory-modal"
                                                        data-modal-toggle="editproductcategory-modal">
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
                                                                    <stop offset='1' stop-color='#FE8A29' />
                                                                </linearGradient>
                                                            </defs>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </td>
                                        </tr>
                            @php
                                        if (!empty($child->children)) {
                                            $renderChildren($child->children, $depth + 1);
                                        }
                                    }
                                };
                                $renderChildren($categorie->children);
                            @endphp
                        @endif

                    @endforeach
                @else
                    {{-- <p class="text-gray-500">No categories available.</p> --}}
                @endif

            </x-slot>
        </x-table>



        <x-modal id="productcategory-modal" x-show="isOpen" x-cloak>
            <x-slot name="title">Product Category</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form action="{{ route('productCategory') }}" method="POST" id="categoryForm" class="categoryForm mb-2" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_type" id="category_type" value="products">

                    <div class="md:py-5">
                        <!-- Image Upload -->
                        <div class="px-4 sm:px-6 mt-5 w-full max-w-md mb-5 mx-auto">
                            <label class="block mb-2 text-sm font-medium text-center text-gray-700">Category Image</label>
                            <div class="flex flex-col sm:flex-row gap-4 items-center sm:items-start">
                                <img id="category-image-preview" src="" alt="Category Image"
                                     class="w-32 h-32 object-cover rounded hidden" />
                                <div class="w-full">
                                    <x-file-uploader type="text" label="Banner" placeholder="Banner Here" id="image" name="image" />
                                </div>
                            </div>
                        </div>

                        <!-- Category Name -->
                        <div class="px-6 mt-10">
                            <x-input type="text" label="Category Name" placeholder="Category name..." id="name" name="name" value="" />
                        </div>

                        <!-- Parent Category Select -->
                        <div class="px-6 mt-5">
                            <label for="parent_id" class="block mb-2 text-sm font-medium text-customBlack">Parent Category</label>
                            <select name="parent_id" id="parent_id"
                                    class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-primary focus:border-primary block p-2.5 w-full">
                                <option value="">-- None (Main Category) --</option>
                                @php renderCategoryOptions($parentcategories); @endphp
                            </select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 rounded-b-lg">
                        <div class="flex items-center justify-end p-2">
                            <button type="submit" id="submit" class="px-6 py-2 text-white bg-primary rounded-3xl">Submit</button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>



        <x-modal id="editproductcategory-modal">
            <x-slot name="title">View Product Category</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white rounded-lg shadow-md md:py-5">
                    <!-- Category Image -->
                    <div class="w-full">
                        <label class="block mb-2 text-lg font-semibold text-gray-700">Category Image</label>
                        <img id="categoryImage" class="object-cover w-full  border border-gray-300 rounded-lg shadow-md"
                            src="" alt="Category Image">
                    </div>

                    <!-- Category Name -->
                    <div class="w-full">
                        <label class="block mb-2 text-lg font-semibold text-gray-700">Category Name</label>
                        <div id="categoryName" class="text-xl font-bold text-gray-900 capitalize"></div>
                    </div>

                    <!-- Sub Categories -->
                    <div class="w-full">
                        <label class="block mb-2 text-lg font-semibold text-gray-700">Sub Categories</label>
                        <ul id="categorySubCategories" class="p-3 bg-gray-100 rounded-lg shadow-sm capitalize space-y-1"></ul>
                    </div>
                </div>

            </x-slot>
        </x-modal>
    </div>

@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {


            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Array to store tags
            let tags = [];

            // Function to create a tag element
            function createTagElement(tag) {
                let tagElement = $("<span>")
                    .addClass("bg-blue-500 text-white px-2 py-1 rounded flex items-center mr-2 mt-1")
                    .text(tag);

                let removeButton = $("<button>")
                    .html("×")
                    .addClass("ml-2 cursor-pointer text-white font-bold")
                    .click(function() {
                        tags = tags.filter(t => t !== tag);
                        tagElement.remove();
                        updateHiddenInput();
                    });

                tagElement.append(removeButton);
                $("#view-tag-container").append(tagElement);
            }

            // Function to update hidden input with tags
            function updateHiddenInput() {
                $("#tag-inputs").val(JSON.stringify(tags));
            }

            // Function to refresh CSRF token
            function refreshCsrfToken() {
                $.ajax({
                    url: '/refresh-csrf-token',
                    type: 'GET',
                    success: function(response) {
                        $('meta[name="csrf-token"]').attr('content', response.token);
                        $('input[name="_token"]').val(response.token);
                    },
                    error: function() {
                        console.error('Failed to refresh CSRF token');
                    }
                });
            }

            // Handle tag input
            $("#tag-input-field").keypress(function(event) {
                if (event.which === 13 && $(this).val().trim() !== "") {
                    event.preventDefault();
                    let newTag = $(this).val().trim();

                    if (!tags.includes(newTag)) {
                        tags.push(newTag);
                        createTagElement(newTag);
                        updateHiddenInput();
                    }

                    $(this).val("");
                }
            });

            // Open modal for adding new category
            $("#addModalBtn").on("click", function() {
                $("#submit").text("Submit");
                $("#categoryForm").attr("action", "/ProductCategory");
                $("#categoryForm")[0].reset();
                $("#view-tag-container").empty();
                $("#category-image-preview").addClass("hidden").attr("src", "");
                tags = [];
                updateHiddenInput();
                refreshCsrfToken();
                $('#productcategory-modal').removeClass('hidden').addClass(
                    'flex items-center justify-center bg-gray-900 bg-opacity-50');
            });

            // Open modal for updating category
            window.updateData = function(id) {
                // Update form action & button text
                $("#categoryForm").attr("action", "/ProductCategory/update/" + id);
                $("#submit").text("Update");

                // Reset fields
                $("#name").val("");
                $("#parent_id").val("");
                $("#category-image-preview").addClass("hidden").attr("src", "");
                $("#view-tag-container").empty();
                tags = [];
                updateHiddenInput();
                refreshCsrfToken();

                // Show modal
                $('#productcategory-modal')
                    .removeClass('hidden')
                    .addClass('flex items-center justify-center bg-gray-900 bg-opacity-50');

                // Fetch category data
                $.ajax({
                    url: '/ProductCategory/getforupdate/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log("AJAX Response:", response);

                        if (response.status === 'success') {
                            const data = response.data;

                            // Fill fields
                            $('#name').val(data.name);
                            // Set parent category if exists
                            if (data.parent_id) {
                                $('#parent_id').val(data.parent_id).trigger('change');
                            } else {
                                $('#parent_id').val("").trigger('change');
                            }

                            // Preview image
                            let imageUrl = data.image ? '/' + data.image : "{{ asset('asset/Ellipse 2.png') }}";
                            $('#category-image-preview').attr('src', imageUrl).removeClass('hidden');

                            // Subcategories
                            tags = [];
                            if (Array.isArray(data.sub_categories)) {
                                data.sub_categories.forEach(tag => createTagElement(tag));
                                tags = data.sub_categories;
                            } else if (typeof data.sub_categories === "string") {
                                try {
                                    let parsed = JSON.parse(data.sub_categories);
                                    parsed.forEach(tag => createTagElement(tag));
                                    tags = parsed;
                                } catch (e) {
                                    console.error("Error parsing sub_categories JSON:", e);
                                }
                            }

                            updateHiddenInput();
                        } else {
                            Swal.fire({ icon: "error", title: "Error", text: "Data not found!" });
                        }
                    },
                    error: function() {
                        Swal.fire({ icon: "error", title: "Error", text: "Error fetching data." });
                    }
                });
            }




            // View category details
           $(".viewModalBtn").on("click", function () {
    let categoryId = $(this).data("id");

    $.ajax({
        url: "/fetch-category/" + categoryId,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                // 1. Category name
                $("#categoryName").text(response.data.name);

                // 2. Category image
                let imageUrl = response.data.image
                    ? "{{ asset('') }}" + response.data.image
                    : "{{ asset('asset/Ellipse 2.png') }}";

                $("#categoryImage")
                    .attr("src", imageUrl)
                    .on("error", function () {
                        $(this).attr("src", "{{ asset('asset/Ellipse 2.png') }}");
                    });

                // 3. Recursive subcategory rendering
                let subCategories = response.data.sub_categories;

                function renderSubCategories(categories) {
                    if (!Array.isArray(categories) || categories.length === 0) return '';

                    let html = '<ul class="pl-4 list-disc">';
                    categories.forEach(function (cat) {
                        html += `<li class="py-1 font-medium text-gray-800">${cat.name}`;
                        if (cat.sub_categories && cat.sub_categories.length > 0) {
                            html += renderSubCategories(cat.sub_categories); // Recursive call
                        }
                        html += `</li>`;
                    });
                    html += '</ul>';
                    return html;
                }

                let subCategoryHtml = renderSubCategories(subCategories);
                $("#categorySubCategories").html(
                    subCategoryHtml || `<li class="py-1 italic text-gray-500">No subcategories</li>`
                );

                // 4. Show modal
                $("#editproductcategory-modal")
                    .removeClass("hidden")
                    .addClass("flex items-center justify-center bg-gray-900 bg-opacity-50");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Category not found!"
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Something went wrong!"
            });
        }
    });
});



            // Form submission
            $(".categoryForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#productcategory-modal").addClass("hidden");
                            $("#categoryForm")[0].reset();
                            $("#view-tag-container").empty();
                            $("#category-image-preview").addClass("hidden").attr("src", "");
                            tags = [];
                            updateHiddenInput();
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "Something went wrong"
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            Swal.fire({
                                icon: "error",
                                title: "Session Expired",
                                text: "Your session has expired. Please refresh the page and try again."
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            let message = xhr.responseJSON?.message || "An error occurred.";
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: message
                            });
                        }
                    }
                });
            });

            // Close modal
            $("[data-modal-hide]").click(function() {
                let modalId = $(this).attr("data-modal-hide");
                let modal = $("#" + modalId);
                modal.addClass("hidden").removeClass(
                    "flex items-center justify-center bg-gray-900 bg-opacity-50");

                modal.find("form").each(function() {
                    this.reset();
                    $(this).find("#view-tag-container").empty();
                    $(this).find("#category-image-preview").addClass("hidden").attr("src", "");
                    tags = [];
                    updateHiddenInput();
                });

                refreshCsrfToken();
            });
        });

        function deleteCategory(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the entire category!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/deleteProductCategory/' + id,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(res) {
                                Swal.fire('Deleted!', res.message, 'success').then(() => {
                                    location.reload(); // or remove DOM element directly
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message || 'Delete failed', 'error');
                            }
                        });
                    }
                });
            }
            function deleteSubCategory(parentId, childId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the selected subcategory!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/deleteProductCategory/' + parentId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                child_id: childId
                            },
                            success: function(res) {
                                Swal.fire('Deleted!', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message || 'Delete failed', 'error');
                            }
                        });
                    }
                });
            }

    </script>
