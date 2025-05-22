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

        <div
            class="w-full border bg-[#F7F7F8] flex flex-col gap-3 px-4 py-2 lg:flex-row lg:items-center lg:justify-between">

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
                    <x-select label="Category" name="category" id="category">
                        <x-slot name="options">
                            <option selected value="all">All Categories</option>
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
                    data-price="{{ $product->product_discounted_price ?? '' }}"
                    data-category="{{ $product->product_category ?? '' }}">

                    <!-- Product Image -->
                    <div class="product-img w-full h-[220px] flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($mainImage) }}" alt="Product Image" class="h-full object-contain rounded-xl" />
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
                            {{-- <button data-modal-target="view-modal" data-modal-toggle="view-modal"
                                class="px-4 py-2 text-xs sm:text-sm text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors">
                                Buy
                            </button> --}}
                            <button data-modal-target="view-modal" data-modal-toggle="view-modal"
                                class="viewModalBtn px-4 py-2 text-xs sm:text-sm text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors"
                                viewproducturl="/view-product/{{ $product->product_id }}">
                                Buy
                            </button>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <x-modal id="view-modal">
            <x-slot name="title">Product Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="">
                    <div class="">
                        <div class="md:p-5">
                            <div class="flex items-start gap-4">
                                <!-- Main Image -->
                                <div class="w-1/3">
                                    <div class="h-48 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                        <img id="main-image" src="" alt="Main Product Image"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <!-- Sub Images -->
                                <div class="grid flex-1 grid-cols-2 gap-4 md:grid-cols-4">
                                    <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                        <img id="sub-image-1" src="" alt="Sub Image 1"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                        <img id="sub-image-2" src="" alt="Sub Image 2"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                        <img id="sub-image-3" src="" alt="Sub Image 3"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-24 bg-gray-100 border-2 border-gray-200 rounded-sm overflow-hidden">
                                        <img id="sub-image-4" src="" alt="Sub Image 4"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Content -->
                            <div class="p-6 bg-white rounded-lg shadow-lg">
                                <!-- Title -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-5">
                                        <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Title</div>
                                            <div id="product_name"
                                                class="text-base font-semibold text-gray-700 capitalize">
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Rating</div>
                                            <div id="product_rating"
                                                class="text-base font-semibold text-gray-700 capitalize">4.0</div>
                                        </div>

                                        <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Brand</div>
                                            <div id="brand_name" class="text-base text-gray-700 capitalize"></div>
                                        </div>

                                        {{-- <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Stock</div>
                                            <div id="stock" class="text-base text-gray-700 capitalize"></div>
                                        </div> --}}
                                        <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Price</div>
                                            <div id="product_price" class="text-base text-gray-700 font-medium">
                                            </div>
                                        </div>


                                    </div>

                                    <div class="space-y-5">
                                        {{-- <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Orders</div>
                                            <div id="Orders" class="text-base text-gray-700 capitalize">56</div>
                                        </div> --}}

                                        <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Category</div>
                                            <div id="product_category" class="text-base text-gray-700 ">
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-6">
                                            <div class="w-32 text-sm font-medium text-gray-600">Whole Sale</div>
                                            <div id="whole-sale" class="text-base font-semibold text-gray-700"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Variations -->
                                <div class="mt-6">
                                    <div id="product_variations"\>
                                        <!-- Variations will be dynamically inserted here -->
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mt-6">
                                    <div class="flex flex-col gap-2">
                                        <div class="text-sm font-medium text-gray-600">Description</div>
                                        <div id="product_description"
                                            class="text-base text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-md capitalize">

                                        </div>
                                    </div>
                                </div>
                                <form id="statusForm" class="">
                                    @csrf

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 mt-4">
                                        <div class="flex items-center gap-6">
                                            <label class="block text-sm  font-medium text-gray-600">Purchase
                                                <br>Stock</label>
                                            <input placeholder="Enter Here" type="text" id="purchase_stock"
                                                class="w-[200px] p-2 mt-1 border border-gray-700 rounded-lg ">
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-6 align-middle">
                                                <div class="text-sm text-gray-600">Price</div>
                                                {{-- <div  class="text-gray-700">$100 (20%: 20)</div> --}}
                                                <div id="price_formula" class="text-gray-700">$0 (0%: 0)</div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex w-full gap-14 mt-2">
                                        <label class="block text-sm  font-medium text-gray-600">Note
                                        </label>
                                        <input placeholder="Enter Here" type="text"
                                            class="w-full border border-gray-700 rounded-lg  ">
                                    </div>
                            </div>
                            <div class=" bg-gray-300 rounded-b-lg">
                                <div class="flex items-center justify-between p-2">
                                    <button type="button" data-modal-hide="view-modal" type="button"
                                        class=" ms-3 text-sm font-medium
                      px-3 py-1 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                                        Close
                                    </button>

                                    <button type="button" type="submit"
                                        class=" me-3 text-sm font-medium
                                     px-4 py-2 text-white bg-primary  rounded-3xl">
                                        Send Inquiry
                                    </button>
                                </div>

                                </form>

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
            $("#gridView").click(function() {
                $("#productContainer").removeClass("flex flex-col").addClass(
                    "grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5");
                $(".product-img").removeClass("h-[220px]").addClass("w-full h-[220px]");
                $(".product").addClass("flex-col").removeClass("flex gap-5 items-center");
                $(".rating").addClass("justify-between flex").removeClass("flex-column");
                $(".buy-btn-container").addClass("flex justify-between").removeClass("flex-column");
                $(".title").removeClass("w-[300px]");
                $(".product-info").addClass("mt-3");
            });

            $("#rowView").click(function() {
                $("#productContainer").removeClass(
                    "grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5").addClass(
                    "flex flex-col gap-5");
                $(".product-img").removeClass("w-full").addClass("h-[220px] w-[300px]");
                $(".product").removeClass("flex-col").addClass(
                    "flex flex-col md:flex-row gap-2 items-center ");
                $(".rating").removeClass("justify-between flex").addClass("flex-column");
                $(".buy-btn-container").removeClass("flex justify-between").addClass("flex-column");
                $(".title").addClass("w-[300px]");
                $(".product-info").removeClass("mt-3");
            });

            function filterProducts() {
                let searchText = $("#search").val().toLowerCase();
                let priceRange = $("#price").val();
                let selectedCategory = $("#category").val();

                let [minPrice, maxPrice] = priceRange === 'all' ? [0, Infinity] :
                    priceRange === '10001+' ? [10001, Infinity] :
                    priceRange.split('-').map(Number);

                $(".product").each(function() {
                    let productName = ($(this).data("name") || "").toString().toLowerCase();
                    let productBrand = ($(this).data("brand") || "").toString().toLowerCase();
                    let productPrice = parseFloat($(this).data("price")) || 0;
                    let productCategory = $(this).data("category");

                    let matchesSearch = searchText === "" ||
                        productName.includes(searchText) ||
                        productBrand.includes(searchText);

                    let matchesPrice = productPrice >= minPrice &&
                        (maxPrice === Infinity ? true : productPrice <= maxPrice);

                    let matchesCategory = selectedCategory === "all" ||
                        productCategory == selectedCategory;

                    if (matchesSearch && matchesPrice && matchesCategory) {
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

            // Category filter handler
            $("#category").on("change", function() {
                filterProducts();
            });
        });
    </script>
    <script>
        let currentPrice = 0;
        let availableStock = 0; // Track total stock

        $(document).ready(function() {
            $(".viewModalBtn").on("click", function() {
                const url = $(this).attr("viewproducturl");

                if (!url) {
                    alert("Invalid product URL!");
                    return;
                }

                // Clear old data
                $("#product_variations").html("");
                $("#main-image, #sub-image-1, #sub-image-2, #sub-image-3, #sub-image-4").attr("src", "");
                $("#price_formula").text("Rs 0 (0%: Rs 0)");
                currentPrice = 0;
                availableStock = 0;
                $("#purchase_stock").val("");

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        console.log(response);

                        if (response.success) {
                            const product = response.product;

                            // Basic product info
                            $("#product_name").text(product.product_name);
                            $("#brand_name").text(product.product_brand);
                            $("#product_category").text(product.category_name);
                            $("#product_price").text("Rs " + product.product_price);
                            $("#product_description").text(product.product_description);
                            $("#whole-sale").text("Rs " + product.product_discounted_price);

                            // Set price and calculate discount
                            currentPrice = parseFloat(product.product_price);
                            updateDiscountDisplay(currentPrice);

                            // Images
                            const images = product.product_images || [];
                            $("#main-image").attr("src", images[0] || "");
                            $("#sub-image-1").attr("src", images[1] || "");
                            $("#sub-image-2").attr("src", images[2] || "");
                            $("#sub-image-3").attr("src", images[3] || "");
                            $("#sub-image-4").attr("src", images[4] || "");

                            // Variations
                            const variationsGrouped = {};
                            product.product_variation.forEach(variation => {
                                const key = variation.parent_option_name;
                                if (!variationsGrouped[key]) variationsGrouped[
                            key] = [];
                                variationsGrouped[key].push(variation);
                            });

                            // Sum total stock
                            availableStock = product.product_variation.reduce((total,
                                variation) => {
                                    return total + parseInt(variation.parentstock || 0);
                                }, 0);

                            let html = "";
                            for (let key in variationsGrouped) {
                                html += `
                                <div class="flex items-center gap-6 mb-4">
                                    <div class="w-32 text-sm font-medium text-gray-600">${key}</div>
                                    <div class="flex flex-wrap gap-3">
                            `;
                                variationsGrouped[key].forEach(v => {
                                    html += `<span class="px-4 py-1 text-sm text-gray-700 bg-gray-100 rounded-full border border-gray-200">
                                    ${v.parentname} <span class="text-xs text-gray-500">(Stock: ${v.parentstock})</span>
                                </span>`;
                                });
                                html += "</div></div>";
                            }

                            // Child variations
                            const children = product.product_variation[0]?.children || [];
                            if (children.length) {
                                const childOption = children[0].child_option_name || "Options";
                                html += `
                                <div class="flex items-center gap-6 mb-4">
                                    <div class="w-32 text-sm font-medium text-gray-600">${childOption}</div>
                                    <div class="flex flex-wrap gap-3">
                            `;
                                children.forEach(child => {
                                    html +=
                                        `<span class="px-4 py-1 text-sm text-gray-700 bg-gray-100 rounded-full border border-gray-200">${child.name}</span>`;
                                });
                                html += "</div></div>";
                            }

                            $("#product_variations").html(html);

                        } else {
                            alert("Product not found.");
                        }
                    },
                    error: function() {
                        alert("Error fetching product data.");
                    }
                });
            });

            // Stock input validation
            $("#purchase_stock").on("input", function() {
                let stock = parseInt($(this).val()) || 0;

                if (stock > availableStock) {
                    alert(`Only ${availableStock} units available in stock.`);
                    $(this).val(availableStock); // Reset to max available
                    stock = availableStock;
                }

                if (currentPrice > 0) {
                    updateDiscountDisplay(currentPrice, stock);
                }
            });

            function updateDiscountDisplay(price, stock = null) {
                const enteredStock = stock !== null ? stock : (parseInt($("#purchase_stock").val()) || 0);
                const totalPrice = price * enteredStock;
                const discountPercent = 20;
                const discountAmount = totalPrice > 0 ? ((discountPercent / 100) * totalPrice).toFixed(2) : 0;

                $("#price_formula").text(`Rs ${totalPrice} (${discountPercent}%: Rs ${discountAmount})`);
            }
        });
    </script>



@endsection
