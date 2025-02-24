@extends('layout')
@section('title', 'Add Product')
@section('nav-title', 'Add Product')
@section('content')
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
                                id="discounted_price" name="discounted_price"/>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="flex justify-between  items-center px-5">
                        <h3 class="text-xl font-medium  pt-4">Attributes</h3>
                        <button id="viewModalBtn" data-modal-target="productattribute-modal"
                            data-modal-toggle="productattribute-modal"
                            class="px-6 py-2 mt-5 text-white bg-primary rounded-3xl">Add Attribute</button>
                    </div>

                    {{-- Next Section --}}

                    <div class="rounded-xl border min-h-[200px] mt-5 p-5">
                        <!-- Dynamic Attributes Section -->
                        <div id="attributes-container">
                            <div class="attribute-group" data-attribute="Size">
                                <h3>Size</h3>
                                <div class="attribute-options">
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Small">Small</div>
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Medium">Medium</div>
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Large">Large</div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="attribute-group" data-attribute="Color">
                                <h3>Color</h3>
                                <div class="attribute-options">
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Green">Green</div>
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Orange">Orange</div>
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Yellow">Yellow</div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="attribute-group" data-attribute="Material">
                                <h3>Material</h3>
                                <div class="attribute-options">
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Loan">Loan</div>
                                    <div class="attribute-item px-6 py-1 text-sm cursor-pointer bg-gray-200 rounded-3xl inline-block mt-3 mr-5"
                                        data-value="Cotton">Cotton</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- <h3 class="text-[20px] font-medium my-5">Variants</h3> --}}
                    <!-- Button -->
                    <div class="flex justify-between  items-center px-5 py-5">
                        <h3 class="text-xl font-medium  ">Variants</h3>
                        <button id="addVariants" class="px-6 py-2 mt-4 text-white bg-primary rounded-3xl">Add
                            Variant</button>
                    </div>
                    {{-- Table --}}

                    <table class="w-full table-auto mt-5 border">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="py-3">Image</th>
                                <th class="py-3">Variant</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Available Stock</th>
                                <th class="py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody"></tbody>
                    </table>

                    <!-- Button -->
                    <div class="flex justify-end">
                        <button type="button" class="px-6 py-2 mt-5 text-white bg-primary rounded-3xl">
                            Add Product
                        </button>
                    </div>
            </form>
        </div>
    </div>

    <x-modal id="productattribute-modal">
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
                            <button type="button" id="add-value-btn"
                                class="bg-blue-500 text-white px-3 py-2 rounded-md">
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
    </x-modal>


@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Function to check if table is empty and show default message
            function checkEmptyTable() {
                if ($("#variantTableBody tr").length === 0) {
                    $("#variantTableBody").html(`
                    <tr id="defaultRow">
                        <td colspan="5" class="text-center text-gray-500 py-3">Variant will display here</td>
                    </tr>
                `);
                }
            }

            // Initialize table with default message
            checkEmptyTable();

            // Attribute Selection
            $(document).on("click", ".attribute-item", function() {
                let parent = $(this).closest(".attribute-group");
                parent.find(".attribute-item").removeClass("bg-primary text-white").addClass(
                    "bg-gray-200");
                $(this).removeClass("bg-gray-200").addClass("bg-primary text-white");
            });

            // Add Variant to Table
            $("#addVariants").click(function(e) {
                e.preventDefault();
                let selectedAttributes = [];
                $(".attribute-group").each(function() {
                    let selectedOption = $(this).find(".bg-primary");
                    if (selectedOption.length) {
                        selectedAttributes.push(selectedOption.data("value"));
                    }
                });

                if (selectedAttributes.length !== $(".attribute-group").length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Selection',
                        text: 'Please select all attributes before adding a variant!',
                    });
                    return;
                }

                let variantText = selectedAttributes.join(" / ");
                if ($(`#variantTableBody tr:contains('${variantText}')`).length !== 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Variant',
                        text: 'This variant already exists!',
                    });
                    return;
                }

                // Remove default row if it exists
                $("#defaultRow").remove();

                $("#variantTableBody").append(`
                <tr class="bg-gray-100 text-center border-b">
                    <td class="py-3">
                        <div class="relative flex items-center justify-center w-full h-full">
                            <label class="flex flex-col items-center justify-center w-[50px] h-[50px] border-2 border-gray-300 rounded-lg cursor-pointer file-upload-label">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 file-upload-content">
                                    <img src="{{ asset('asset/image-uploader.png') }}" class="w-[20px]" alt="image">
                                </div>
                                <input type="file" class="hidden file-input" name="image" accept="image/*" />
                                <img class="absolute top-0 left-0 hidden object-contain w-[50px] h-[50px] rounded-lg file-preview bg-customOrangeDark" />
                            </label>
                        </div>
                    </td>
                    <td class="py-3">
                        <p>${variantText}</p>
                    </td>
                    <td class="py-3">
                        <input type="text" class="border text-gray-900 text-sm rounded-md p-2.5 w-[80%]" placeholder="Price">
                    </td>
                    <td class="py-3">
                        <input type="text" class="border text-gray-900 text-sm rounded-md p-2.5 w-[80%]" placeholder="Stock">
                    </td>
                    <td class="py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-8 cursor-pointer text-primary mx-auto delete-row" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </td>
                </tr>
            `);
            });

            // Delete Variant Row
            $(document).on("click", ".delete-row", function() {
                $(this).closest("tr").remove();
                checkEmptyTable(); // Check if table is empty after deletion
            });
            $(document).ready(function(e) {
                $('#viewModalBtn').click(function(e) {
                    e.preventDefault();
                })
            })
        });


        $(document).ready(function() {
            $("#add-value-btn").click(function(e) {
                e.preventDefault();
                $(this).before(`
            <div class="flex items-center gap-2 new-value">
                <input type="text" name="values[]" class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-primary focus:border-primary block p-2.5 w-26 values-input" placeholder="Enter value">
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md remove-value">
                    -
                </button>
            </div>
        `);
            });

            // Remove value input (including the default field)
            $(document).on("click", ".remove-value", function() {
                if ($("#values-container .new-value").length > 0 || !$(this).closest(".default-value")
                    .length) {
                    $(this).closest("div").remove();
                }
            });
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
