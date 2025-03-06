@extends('layout')
@section('title', 'Add Product')
@section('nav-title', 'Add Product')
@section('content')
    <style>
        .variant-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 500px;
            background: #fff;
            font-family: Arial, sans-serif;
            margin-bottom: 15px;
        }

        .input-group {
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        /* input {
                                                                        width: 100%;
                                                                        padding: 8px;
                                                                        border: 1px solid #ddd;
                                                                        border-radius: 5px;
                                                                    } */

        .error {
            color: red;
            font-size: 12px;
            display: none;
        }

        .delete-btn {
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
        }

        .done-btn {
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .variant-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .add-option-btn {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .add-option-btn img {
            width: 16px;
            height: 16px;
            margin-right: 6px;
        }
    </style>
    <div class="w-full pt-10 min-h-[86vh] px-5  rounded-lg custom-shadow">
        <h3 class="text-[20px] font-medium">Add Product</h3>

        <div>
            <form>
                @csrf
                <div class="md:py-5">
                    <div class=" mt-5">
                        <x-input type="text" label="Title" placeholder="Title Here" id="title" name="title" />
                    </div>
                    <div class=" mt-5">
                        <x-textarea type="text" label="Description" placeholder="Description Here" required
                            id="description" name="description" />
                    </div>
                    <div class=" mt-5 grid lg:grid-cols-3 md:grid-cols-1 sm:grid-cols-2  gap-5">
                        <div>
                            <x-input type="text" label="Brand / Company" placeholder="Brand / Company" id="company"
                                name="company" />
                        </div>
                        <div>
                            <x-select type="text" label="Category" placeholder="Category Here" id="category"
                                name="category">
                                <x-slot name="options">
                                    <option disabled selected>Select Category</option>
                                </x-slot>
                            </x-select>

                        </div>
                        <div>
                            <x-input type="number" label="Purchase Price" placeholder="Price Here" id="purchase_price"
                                name="purchase_price" />
                        </div>
                        <div>
                            <x-input type="number" label="Product Price" placeholder="Price Here" id="product_price"
                                name="product_price" />
                        </div>
                        <div>
                            <x-input type="number" label="Discount (%)" placeholder="Discount Here" id="discount"
                                name="discount" />
                        </div>
                        <div>
                            <x-input type="number" label="Discounted Price" placeholder="Discounted Price Here"
                                id="discounted_price" name="discounted_price" />
                        </div>
                    </div>

                    <!-- Button -->
                    {{-- <div class="flex justify-between  items-center px-5"> --}}
                    {{-- <h3 class="text-xl font-medium  pt-4">Attributes</h3>
                        <button id="viewModalBtn" data-modal-target="productattribute-modal"
                            data-modal-toggle="productattribute-modal"
                            class="px-6 py-2 mt-5 text-white bg-primary rounded-3xl">Add Attribute</button> --}}
                    {{-- </div> --}}

                    {{-- Next Section --}}
                    <div class="grid lg:grid-cols-3">
                        <div class="variant-box w-full mt-5">
                            <div class="variant-title">Variants</div>
                            <button type="button" id="add-option-btn" class="add-option-btn">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/plus-math.png" alt="plus icon">
                                Add options like size or color
                            </button>
                        </div>
                    </div>
                    <div id="options-container" class="flex"></div>


                    <table class="w-full table-auto mt-5 border">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="py-3">Image</th>
                                <th class="py-3">Variant</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Available Stock</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody"></tbody>
                    </table>
            </form>
        </div>
    </div>

    {{-- <x-modal id="productattribute-modal">
        <x-slot name="title">Add Attribute</x-slot>
        <x-slot name="modal_width">max-w-4xl</x-slot>
        <x-slot name="body">
            <form id="attribute-form">
                @csrf
                <div class="md:py-5 space-y-4">
                    <div class="px-5">
                        <!-- Labels -->
                        <div>
                            <label class="text-gray-700 font-semibold">Attribute</label>
                            <span></span>
                            <input type="text" name="attribute"
                                class=" border border-gray-300 text-gray-900 my-3  text-sm rounded-md focus:ring-primary focus:border-primary block w-full p-2.5"
                                placeholder="Enter attribute">
                        </div>

                        <label class="text-gray-700 font-semibold">Values</label>
                        <div id="values-container" class="flex flex-wrap mt-3 gap-2 items-center">
                            <div class="flex items-center gap-2 default-value">
                                <input type="text" name="values[]"
                                    class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-primary focus:border-primary block p-2.5 w-26 values-input"
                                    placeholder="Enter value">
                                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md remove-value">
                                    -
                                </button>
                            </div>
                            <button type="button" id="add-value-btn" class="bg-blue-500 text-white px-3 py-2 rounded-md">
                                +
                            </button>
                        </div>

                    </div>
                </div>
                </div>

                <!-- Buttons -->
                <div class="mt-6 bg-gray-300 rounded-b-lg">
                    <div class="flex items-center justify-between p-2">
                        <div></div>
                        <button type="button" class="px-6 py-2 text-white bg-primary rounded-3xl">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal> --}}


@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let options = [];

            $("#add-option-btn").click(function() {
                if (options.length >= 3) {
                    alert("You can add a maximum of three options.");
                    return;
                }

                let optionIndex = options.length;
                options.push({
                    name: "",
                    values: []
                });

                let optionHtml = `
        <div class="variant-box mr-5 border rounded-lg bg-gray-100 mb-4" data-option-index="${optionIndex}">
            <div class="input-group mb-2">
                <label>Option name</label>
                <input type="text" class="option-name bg-gray-50 border text-sm rounded-lg w-full p-2.5" placeholder="Add Name">
            </div>
            <div class="input-group">
                <label>Option values</label>
                <div class="values-container">
                    <div class="value-item flex items-center mb-2">
                        <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" placeholder="Add Value">
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <button class="delete-btn bg-red-700 text-white px-4 py-2 rounded">Delete</button>
                <button type="button" class="done-btn bg-gray-800 text-white px-4 py-2 rounded">Done</button>
            </div>
        </div>`;

                $("#options-container").append(optionHtml);
            });

            $(document).on("input", ".option-value", function() {
                let valuesContainer = $(this).closest(".values-container");
                let lastInput = valuesContainer.find(".option-value").last();

                if (lastInput.val().trim() !== "") {
                    let valueHtml = `
            <div class="value-item flex items-center mb-2">
                <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" placeholder="Add Value">
                <button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>
            </div>`;
                    valuesContainer.append(valueHtml);
                }
            });

            $(document).on("click", ".remove-value-btn", function() {
                $(this).closest(".value-item").remove();
            });

            $(document).on("click", ".done-btn", function() {
                let container = $(this).closest(".variant-box");
                let optionIndex = container.data("option-index");
                let optionName = container.find(".option-name").val().trim();
                let values = container.find(".option-value").map(function() {
                    return $(this).val().trim();
                }).get();

                if (!optionName || values.length === 0 || values.some(v => v === "")) {
                    alert("Option name and values are required.");
                    return;
                }

                options[optionIndex] = {
                    name: optionName,
                    values: values
                };
                generateVariants();
            });

            function generateVariants() {
                let tableBody = $("#variantTableBody");
                tableBody.empty();

                let variants = cartesianProduct(options.map(opt => opt.values));

                variants.forEach(variant => {
                    let variantRow = `
            <tr>
                <td class="py-2 px-4">${variant.join(" - ")}</td>
                <td class="py-2 px-4"><input type="number" class="price-input border rounded p-1" placeholder="Price"></td>
                <td class="py-2 px-4"><input type="number" class="stock-input border rounded p-1" placeholder="Stock"></td>
            </tr>`;
                    tableBody.append(variantRow);
                });
            }

            function cartesianProduct(arr) {
                return arr.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())), [
                    []
                ]);
            }
        });






        $(document).ready(function() {
            $("#product_price, #discount").on("input", function() {
                let price = parseFloat($("#product_price").val()) || 0;
                let discount = parseFloat($("#discount").val()) || 0;

                if (price > 0 && discount >= 0) {
                    let discountedPrice = price - (price * (discount / 100));
                    $("#discounted_price").val(discountedPrice.toFixed(2)); // 2 decimal places
                } else {
                    $("#discounted_price").val('');
                }
            });
        });
    </script>



@endsection
