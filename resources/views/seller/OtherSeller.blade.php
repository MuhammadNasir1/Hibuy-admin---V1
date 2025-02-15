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

        <div class="flex items-center justify-between px-5 py-2 mt-5 bg-[#F7F7F8]">

            <div class="  flex gap-3 items-center">

                <button id="gridView" class="h-[35px] w-[35px] rounded-md bg-primary flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-[75%]" viewBox="0 0 512 512">
                        <path
                            d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm88 64l0 64-88 0 0-64 88 0zm56 0l88 0 0 64-88 0 0-64zm240 0l0 64-88 0 0-64 88 0zM64 224l88 0 0 64-88 0 0-64zm232 0l0 64-88 0 0-64 88 0zm64 0l88 0 0 64-88 0 0-64zM152 352l0 64-88 0 0-64 88 0zm56 0l88 0 0 64-88 0 0-64zm240 0l0 64-88 0 0-64 88 0z" />
                    </svg>
                </button>

                <button id="rowView"
                    class="h-[35px] w-[35px] rounded-md bg-transparent border flex items-center justify-center">
                    <img src="{{ asset('asset/Icon.png') }}" class="h-[75%]" alt="">
                </button>

                <div class="relative">
                    <input type="search" name="search" id="search" placeholder="Search for products"
                        class="border border-gray-300 text-gray-900 focus:pl-8  pl-8    text-sm rounded-md focus:ring-primary focus:border-primary  w-[300px] p-2.5" />
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="translate-y-[-30.5px] absolute pl-1.5   h-[20px] fill-gray-600" viewBox="0 0 512 512">
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                </div>

                <p class="text-customGrayColorDark">We found 29 items for you!</p>
            </div>

            <div
                class="border border-gray-300 bg-white      text-sm rounded-md focus:ring-primary focus:border-primary  w-[210px] p-2.5 text-customGrayColorDark flex items-center">
                <p class="pr-2"> Sort By :</p>
                <select style="padding: 0px 10px; cursor: pointer;" class="p-0 border-none " name="filter" id="filter"
                    class="filter px-5 cursor-pointer">
                    <option selected value="featured">Featured</option>
                    <option value="abc">ABC</option>
                </select>
            </div>
        </div>

        {{-- Filter s --}}

        <div class="px-5 pt-5">
            <div class="grid grid-cols-4 gap-5">
                <div>
                    <x-select label="Category" name="category" id="category">
                        <x-slot name="options">
                            <option selected disabled>Select Category</option>
                        </x-slot>
                    </x-select>
                </div>

                <div>
                    <x-select label="Price" name="price" id="price">
                        <x-slot name="options">
                            <option selected disabled>Select Price</option>
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

        <div id="productContainer" class="grid grid-cols-4 gap-5 px-5 mt-5">

            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>
            <div class="product border p-5 rounded-xl flex-column">
                <img src="{{ asset('asset/product.png') }}" alt="product" class="product-img w-full object-contain rounded-xl">
                <div class="flex-1 mt-3 product-info">
                    <p class="text-gray-600">Tech</p>
                    <h1 class="font-bold title">Airpods Pro Wireless Earbuds Bluetooth 5.0</h1>
                    <div class="flex justify-between rating mt-2">
                        <p class="text-gray-500">By TechDad</p>
                        <div class="flex items-center">
                            <img src="{{asset("asset/emojione_star.png") }}" class="h-[20px]" alt="">
                            <p class="text-gray-500 ml-1">(4.0)</p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 items-center buy-btn-container">
                        <p class="text-green-600 font-bold">$28.85</p>
                        <button class="px-6 py-2 text-sm text-white bg-primary rounded-xl">Buy</button>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#filter').select2('destroy');
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#gridView").click(function() {
                $("#productContainer").removeClass("flex flex-col").addClass("grid grid-cols-4 gap-5");
                $(".product-img").removeClass("h-[210px]").addClass("w-full");
                $(".product ").addClass("flex-column").removeClass("flex gap-5");
                $(".rating ").addClass("justify-between flex").removeClass("flex-column");
                $(".buy-btn-container ").addClass("flex justify-between").removeClass("flex-column");
                $(".title ").removeClass("w-[300px]")
                $(".product-info ").addClass("mt-3")
            });

            $("#rowView").click(function() {
                $("#productContainer").removeClass("grid grid-cols-4 gap-5").addClass("flex flex-col gap-5");
                $(".product-img").removeClass("w-full").addClass("h-[210px]");
                $(".product ").removeClass("flex-column").addClass("flex gap-5 items-center");
                $(".rating ").removeClass("justify-between flex").addClass("flex-column");
                $(".buy-btn-container ").removeClass("flex justify-between").addClass("flex-column");
                $(".title ").addClass("w-[300px]");
                $(".product-info ").removeClass("mt-3")
            });
        });
    </script>
@endsection
