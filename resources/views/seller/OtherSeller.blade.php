@extends('layout')
@section('title', 'Products')
@section('nav-title', 'Products')

@section('content')

    <style>
        select:not([size]) {
            background-image: url("data:image/svg+xml,%3csvg aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 10 6'%3e %3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 4 4 4-4'/%3e %3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 0.75em 0.75em;
            padding-right: 2rem !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
    <div class="w-full pt-10 pb-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Other Seller Products</h2>
        </div>

        <div class="w-full border bg-[#F7F7F8] flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between">

            <!-- Left Section: Buttons + Search + Count -->
            <div class="flex flex-col gap-3 w-full md:flex-row md:items-center md:w-auto">

                <!-- View Buttons -->
                <div class="flex gap-2">
                    <button id="gridView" class="h-[35px] w-[35px] rounded-md bg-primary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-[75%]" viewBox="0 0 512 512">
                            <path
                                d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm88 64l0 64-88 0 0-64 88 0zm56 0l88 0 0 64-88 0 0-64zm240 0l0 64-88 0 0-64 88 0zM64 224l88 0 0 64-88 0 0-64zm232 0l0 64-88 0 0-64 88 0zm64 0l88 0 0 64-88 0 0-64zM152 352l0 64-88 0 0-64 88 0zm56 0l88 0 0 64-88 0 0-64zm240 0l0 64-88 0 0-64 88 0z" />
                        </svg>
                    </button>

                    <button id="rowView"
                        class="h-[35px] w-[35px] rounded-md bg-transparent border flex items-center justify-center">
                        <img src="{{ asset('asset/Icon.png') }}" class="h-[75%]" alt="Row View">
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative w-auto ">
                    <input type="search" name="search" id="search" placeholder="Search for products"
                        class="border border-gray-300 text-gray-900 focus:pl-8 pl-8 text-sm rounded-md focus:ring-primary focus:border-primary w-full h-[35px] p-2.5" />
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute top-1/2 left-2 transform -translate-y-1/2 h-[20px] fill-gray-600"
                        viewBox="0 0 512 512">
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                </div>

                <!-- Product Count -->
                <div>
                    <p id="productCount" class="text-customGrayColorDark text-sm sm:text-base">
                        We found {{ count($products) }} items for you!
                    </p>
                </div>

            </div>

            <!-- Right Section: Sort Dropdown -->
            <div class="flex w-full sm:w-auto justify-start lg:justify-end">
                <div
                    class="border border-gray-300 bg-white rounded-md w-full sm:w-[210px] h-[35px] px-3 flex items-center text-customGrayColorDark text-sm">
                    <p class="pr-2 whitespace-nowrap">Sort By :</p>
                    <select class="flex-1 border-none focus:ring-0 focus:border-0 bg-transparent text-sm" name="filter"
                        id="filter">
                        <option selected value="featured">Featured</option>
                        <option value="abc">ABC</option>
                    </select>
                </div>
            </div>

        </div>

        {{-- Filter s --}}

        <div class="px-5 pt-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <div>
                    {{-- <x-select label="Category" name="category" id="category">
                        <x-slot name="options">
                            <option selected disabled>Select Category</option>
                        </x-slot>
                    </x-select> --}}
                    <x-select label="Category" name="category" id="category">
                        <x-slot name="options">
                            <option selected disabled>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>

                </div>

                <div>
                    <x-select label="Price" name="price" id="price">
                        <x-slot name="options">
                            <option selected value="all">All Prices</option>
                            <option value="0-1000">Rs 0 - 1000</option>
                            <option value="1001-5000">Rs 1001 - 5000</option>
                            <option value="5001-10000">Rs 5001 - 10000</option>
                            <option value="10001+">Rs 10001+</option>
                        </x-slot>
                    </x-select>
                </div>

                <div>
                    <x-select label="Color" name="color" id="color">
                        <x-slot name="options">
                            <option selected disabled>Select Color</option>
                        </x-slot>
                    </x-select>
                </div>

                <div>
                    <x-select label="Weight" name="weight" id="weight">
                        <x-slot name="options">
                            <option selected disabled>Select Weight</option>
                        </x-slot>
                    </x-select>
                </div>
            </div>
        </div>
        {{-- Products --}}


        <div id="productContainer" class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 px-4 sm:px-6 mt-5">
            @foreach ($products as $product)
                @php
                    $images = json_decode($product->product_images, true);
                    $mainImage = isset($images[0]) ? str_replace('\/', '/', $images[0]) : 'asset/product.png';
                @endphp

                <div class="product flex flex-col border p-4 rounded-2xl bg-white hover:shadow-lg transition-all duration-300"
                    data-name="{{ strtolower($product->product_name ?? '') }}"
                    data-brand="{{ strtolower($product->product_brand ?? '') }}"
                    data-price="{{ $product->product_discounted_price }}">

                    <!-- Product Image -->
                    <div class="product-img w-full h-[220px] flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($mainImage) }}" alt="Product Image"
                            class="h-full object-contain rounded-xl" />
                    </div>

                    <!-- Product Info -->
                    <div class="product-info flex flex-col flex-1 mt-4">
                        <p class="text-gray-500 text-sm">{{ $product->product_brand }}</p>
                        <h2 class="title font-semibold line-clamp-2 text-base mt-1">{{ $product->product_name }}</h2>

                        <!-- Rating -->
                        <div class="rating flex justify-between items-center mt-2">
                            <p class="text-gray-400 text-xs">By Store #{{ $product->store_id }}</p>
                            <div class="flex items-center">
                                <img src="{{ asset('asset/emojione_star.png') }}" alt="Rating" class="h-4 w-4" />
                                <p class="text-gray-400 text-xs ml-1">(4.0)</p>
                            </div>
                        </div>

                        <!-- Price + Buy Button -->
                        <div class="buy-btn-container flex justify-between gap-1 items-center mt-4">
                            <p class="text-green-600 font-bold text-base">
                                Rs {{ number_format($product->product_discounted_price) }}
                            </p>
                            <button class="px-4 py-2 text-xs sm:text-sm text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors">
                                Buy
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#filter').select2('destroy');
        });
    </script>
    {{-- <script>
       $(document).ready(function() {
    $("#gridView").click(function() {
        // Switch to Grid View
        $("#productContainer").removeClass("flex flex-col").addClass("grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5");
        $(".product-img").removeClass("h-[220px]").addClass("w-full h-[220px]");
        $(".product").addClass("flex-col").removeClass("flex gap-5 items-center");
        $(".rating").addClass("justify-between flex").removeClass("flex-column");
        $(".buy-btn-container").addClass("flex justify-between").removeClass("flex-column");
        $(".title").removeClass("w-[300px]");
        $(".product-info").addClass("mt-3");
    });

    $("#rowView").click(function() {
        // Switch to Row View
        $("#productContainer").removeClass("grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5").addClass("flex flex-col gap-5");
        $(".product-img").removeClass("w-full").addClass("h-[220px] w-[300px]");
        $(".product").removeClass("flex-col").addClass("flex flex-col md:flex-row gap-2 items-center ");
        $(".rating").removeClass("justify-between flex").addClass("flex-column");
        $(".buy-btn-container").removeClass("flex justify-between").addClass("flex-column");
        $(".title").addClass("w-[300px]");
        $(".product-info").removeClass("mt-3");
    });
});

    //     $(document).ready(function() {
    //         $("#search").on("keyup", function() {
    //             let searchText = $(this).val().toLowerCase();

    //             $(".product").each(function() {
    //                 let productName = $(this).attr("data-name").toLowerCase();
    //                 if (productName.includes(searchText)) {
    //                     $(this).show();
    //                 } else {
    //                     $(this).hide();
    //                 }
    //             });
    //         });
    //     });
    //

    $(document).ready(function() {
    $("#search").on("keyup input", function() {
        let searchText = $(this).val().toLowerCase();

        $(".product").each(function() {
            let productName = ($(this).data("name") || "").toString().toLowerCase();
            let productBrand = ($(this).data("brand") || "").toString().toLowerCase();

            if (searchText === "") {
                $(this).show();
            } else {
                if (productName.includes(searchText) || productBrand.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });

        // 🧠 Count and show visible products
        let visibleCount = $(".product:visible").length;
        $("#productCount").text("We found " + visibleCount + " items for you!");
    });
});

    </script> --}}
    <script>
        $(document).ready(function() {
            // View toggle functions
            $("#gridView").click(function() {
                $("#productContainer").removeClass("flex flex-col").addClass("grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5");
                $(".product-img").removeClass("h-[220px]").addClass("w-full h-[220px]");
                $(".product").addClass("flex-col").removeClass("flex gap-5 items-center");
                $(".rating").addClass("justify-between flex").removeClass("flex-column");
                $(".buy-btn-container").addClass("flex justify-between").removeClass("flex-column");
                $(".title").removeClass("w-[300px]");
                $(".product-info").addClass("mt-3");
            });

            $("#rowView").click(function() {
                $("#productContainer").removeClass("grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5").addClass("flex flex-col gap-5");
                $(".product-img").removeClass("w-full").addClass("h-[220px] w-[300px]");
                $(".product").removeClass("flex-col").addClass("flex flex-col md:flex-row gap-2 items-center ");
                $(".rating").removeClass("justify-between flex").addClass("flex-column");
                $(".buy-btn-container").removeClass("flex justify-between").addClass("flex-column");
                $(".title").addClass("w-[300px]");
                $(".product-info").removeClass("mt-3");
            });

            // Function to filter products based on search and price
            function filterProducts() {
                let searchText = $("#search").val().toLowerCase();
                let priceRange = $("#price").val();

                let [minPrice, maxPrice] = priceRange === 'all' ? [0, Infinity] :
                    priceRange === '10001+' ? [10001, Infinity] :
                    priceRange.split('-').map(Number);

                $(".product").each(function() {
                    let productName = ($(this).data("name") || "").toString().toLowerCase();
                    let productBrand = ($(this).data("brand") || "").toString().toLowerCase();
                    let productPrice = parseFloat($(this).data("price")) || 0;

                    let matchesSearch = searchText === "" ||
                        productName.includes(searchText) ||
                        productBrand.includes(searchText);

                    let matchesPrice = productPrice >= minPrice &&
                        (maxPrice === Infinity ? true : productPrice <= maxPrice);

                    if (matchesSearch && matchesPrice) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Update product count
                let visibleCount = $(".product:visible").length;
                $("#productCount").text("We found " + visibleCount + " items for you!");
            }

            // Search handler
            $("#search").on("keyup input", function() {
                filterProducts();
            });

            // Price filter handler
            $("#price").on("change", function() {
                filterProducts();
            });
        });
    </script>
@endsection
