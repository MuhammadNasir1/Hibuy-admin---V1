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

        <style>

        /* Dropzone Container */
        .dropzone {
            border: 2px dashed #6e776e !important;
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: background 0.3s ease-in-out, border-color 0.3s ease-in-out;
        }

        /* Hover Effect */
        .dropzone:hover {
            background: #e0f7fa;
            border-color: #00796b;
        }

        /* Dropzone Message */
        .dropzone .dz-message {
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }

        /* Styling Uploaded Files Preview */
        .dropzone .dz-preview {
            border-radius: 5px;
            background: white;
            padding: 10px;
            margin: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Hide Default Thumbnail */
        .dropzone .dz-image {
            display: none;
        }

        /* Custom Remove File Button */
        .dropzone .dz-remove {
            color: red;
            font-weight: bold;
            cursor: pointer;
            text-decoration: underline;
        }

        /* Upload Progress Bar */
        /* .dz-progress {
                                                                                            background: green !important;
                                                                                            height: 5px !important;
                                                                                            border-radius: 3px;
                                                                                        } */

        /* Styling for a Specific Dropzone */
        #my-dropzone {
            border: 3px dotted blue;
            background-color: lightyellow;
        }
    </style>
    <div class="w-full pt-10 min-h-[86vh] px-5  rounded-lg custom-shadow">
        <h3 class="text-[20px] font-medium">Add Product</h3>
        <div>
            <form id="productForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="md:py-5">
                    <div class="mt-5">
                        <label for="images" class="block text-sm font-medium text-gray-700">Upload Product Images</label>
                        <div id="dropzone" class="dropzone p-0" style="min-height: auto !important;"></div>
                        <!-- Dropzone container -->
                        <input  type="hidden" name="product_images" id="product_images">
                        <input value="{{ $products ? $products->product_id : '' }}" type="hidden" name="product_edit_id" id="product_edit_id">
                    </div>
                    <div class=" mt-5">
                        <x-input value="{{ $products ? $products->product_name : '' }}" type="text" label="Title"
                            placeholder="Title Here" id="title" name="title" />
                    </div>
                    <div class=" mt-5">
                        <x-textarea type="text" label="Description" placeholder="Description Here" required
                            id="description" name="description" :value="$products->product_description ?? ''" />
                    </div>
                    <div class="mt-5 grid lg:grid-cols-4 md:grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input value="{{ $products ? $products->product_brand : '' }}" type="text"
                                label="Brand / Company" placeholder="Brand / Company" id="company" name="company" />
                        </div>
                        <div>
                            <x-select type="text" label="Category" placeholder="Category Here" id="category"
                                name="category">
                                <x-slot name="options">
                                    <option disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($products->product_category) && $products->product_category == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>

                        <div>
                            <x-select type="text" label="Sub Category" placeholder="Sub Category Here" id="sub_category"
                                name="sub_category">
                                <x-slot name="options">
                                    <option disabled selected>Select Sub Category</option>
                                    @if (isset($products->product_subcategory))
                                        <option selected value="{{ $products->product_subcategory }}">{{ $products->product_subcategory }}
                                        </option>
                                    @endif
                                </x-slot>
                            </x-select>
                        </div>
                        <div>
                            <x-input value="{{ $products ? $products->purchase_price : '' }}" type="number" label="Purchase Price" placeholder="Price Here"
                                id="purchase_price" name="purchase_price" />
                        </div>
                        <div>
                            <x-input value="{{ $products ? $products->product_price : '' }}" type="number" label="Product Price" placeholder="Price Here"
                                id="product_price" name="product_price" />
                        </div>
                        <div>
                            <x-input value="{{ $products ? $products->product_discount : '' }}" type="number" label="Discount (%)" placeholder="Discount Here"
                                id="discount" name="discount" />
                        </div>
                        <div>
                            <x-input value="{{ $products ? $products->product_discounted_price : '' }}" type="number" label="Discounted Price"
                                placeholder="Discounted Price Here" id="discounted_price" name="discounted_price" />
                        </div>
                    </div>
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
                    <!-- Buttons -->
                    <div class="mt-6 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <div></div>
                            <button type="submit" id="add_product_btn" class="px-6 py-2 text-white bg-primary rounded-3xl">
                                Submit
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let options = [];

            $("#add-option-btn").click(function() {
                if (options.length > 1) {
                    alert("You can only add two options: Parent and Child.");
                    return;
                }

                let optionIndex = options.length;
                options.push({
                    name: "",
                    values: []
                });

                let optionHtml = `
        <div class="variant-box border rounded-lg bg-gray-100 mr-4 p-4" data-option-index="${optionIndex}">
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
            <div class="option-display-container mt-4"></div>
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

            $(document).on("click", ".delete-btn", function() {
                let container = $(this).closest(".variant-box");
                let optionIndex = container.data("option-index");

                options.splice(optionIndex, 1);
                container.remove();

                $(".variant-box").each(function(index) {
                    $(this).attr("data-option-index", index);
                });

                generateVariants();
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

                let optionDisplayHtml = `
    <div class="option-display p-2 bg-gray-200 rounded mt-2">
        <strong>${optionName}</strong>
        <div class="option-values mt-2 flex gap-2">
            ${values.map(value => `<span class="px-3 py-1 bg-gray-300 rounded">${value}</span>`).join('')}
        </div>
    </div>`;

                container.find(".option-display-container").html(optionDisplayHtml);
                container.find(".input-group, .values-container, .done-btn").hide();

                // Add hidden inputs for option name and values
                let hiddenInputsHtml = `
        <input type="hidden" name="options[${optionIndex}][name]" value="${optionName}">
        ${values.map(value => `<input type="hidden" name="options[${optionIndex}][values][]" value="${value}">`).join('')}
    `;

                container.append(hiddenInputsHtml);

                generateVariants();
            });

            function generateVariants() {
                let tableBody = $("#variantTableBody");
                tableBody.empty();

                if (options.length === 0) return; // No variants to display

                let parentValues = options[0].values;
                let parentOptionName = options[0].name;
                let childValues = options.length > 1 ? options[1].values : []; // Child only if available
                let childOptionName = options.length > 1 ? options[1].name : "";

                parentValues.forEach((parentValue, parentIndex) => {
                    let parentRow = `
<tr class="parent-row bg-gray-100 border-b border-gray-300">
    <td class="py-3 px-4 font-semibold text-gray-800 flex items-center space-x-2">
        ${childValues.length > 0 ? `<button type="button" class="toggle-child cursor-pointer text-gray-600 hover:text-gray-800 transition" data-target="#child-rows-${parentIndex}">▼</button>` : ""}
        <span>${parentValue}</span>
        ${childValues.length > 0 ? `<span class="text-sm text-gray-500">(${childValues.length} variants)</span>` : ""}
    </td>
    <td class="py-3 px-4">
        <input type="hidden" name="variants[${parentIndex}][parentname]" value="${parentValue}">
        <input type="hidden" name="variants[${parentIndex}][parent_option_name]" value="${parentOptionName}">
        <input type="file" name="variants[${parentIndex}][parentimage]" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
    </td>
    <td class="py-3 px-4">
        <input type="number" id="parent-price-${parentIndex}" name="variants[${parentIndex}][parentprice]" class="price-input w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="Rs 0.00">
    </td>
    <td class="py-3 px-4">
        <input type="number" id="parent-stock-${parentIndex}" name="variants[${parentIndex}][parentstock]" class="stock-input w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="0">
    </td>
</tr>`;

                    tableBody.append(parentRow);

                    // If child variants exist, generate child rows
                    if (childValues.length > 0) {
                        let childRowContainer = `<tbody id="child-rows-${parentIndex}" class="hidden">`;
                        childValues.forEach((childValue, childIndex) => {
                            childRowContainer += `
<tr class="child-row bg-white border-b border-gray-300">
    <td class="py-3 px-4 pl-12 text-gray-700 flex items-center space-x-2">
        <span>${childValue}</span>
        <input type="hidden" name="variants[${parentIndex}][children][${childIndex}][name]" value="${childValue}">
        <input type="hidden" name="variants[${parentIndex}][children][${childIndex}][child_option_name]" value="${childOptionName}">
    </td>
    <td class="py-3 px-4">
        <input type="file" name="variants[${parentIndex}][children][${childIndex}][image]" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
    </td>
    <td class="py-3 px-4">
        <input type="number" name="variants[${parentIndex}][children][${childIndex}][price]" class="child-price-${parentIndex} w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="Rs 0.00">
    </td>
    <td class="py-3 px-4">
        <input type="number" name="variants[${parentIndex}][children][${childIndex}][stock]" class="child-stock-${parentIndex} w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="0">
    </td>
</tr>`;
                        });

                        childRowContainer += `</tbody>`;
                        tableBody.append(childRowContainer);
                    }
                });

                // Ensure child row toggle works
                $(".toggle-child").off("click").on("click", function() {
                    let target = $(this).data("target");
                    $(target).toggleClass("hidden");
                    $(this).text($(target).hasClass("hidden") ? "▼" : "▲");
                });

                // Sync parent price with children prices if children exist
                if (options.length > 1) {
                    $(".price-input").on("input", function() {
                        let parentIndex = $(this).attr("id").split("-").pop();
                        let parentPrice = $(this).val();
                        $(`.child-price-${parentIndex}`).val(parentPrice);
                    });

                    // Sync parent stock with children stock if children exist
                    $(".stock-input").on("input", function() {
                        let parentIndex = $(this).attr("id").split("-").pop();
                        let parentStock = $(this).val();
                        $(`.child-stock-${parentIndex}`).val(parentStock);
                    });
                }
            }






            $(document).on("click", ".parent-row", function() {
                let parentIndex = $(this).data("parent-index");
                $(`.child-row[data-parent-index="${parentIndex}"]`).toggleClass("hidden");
            });

            $(document).on("change", ".image-upload", function(event) {
                let file = event.target.files[0];
                let previewContainer = $(this).siblings(".image-preview");

                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.html(
                            `<img src="${e.target.result}" class="w-16 h-16 object-cover rounded">`);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });










        $(document).ready(function() {
            $('#category').on('change', function() {
                let categoryId = $(this).val();
                let subCategoryDropdown = $('#sub_category');

                // Clear previous options
                subCategoryDropdown.html('<option disabled selected>Loading...</option>');

                $.ajax({
                    url: `/get-subcategories/${categoryId}`,
                    type: 'GET',
                    success: function(response) {
                        subCategoryDropdown.html(
                            '<option disabled selected>Select Sub Category</option>');

                        if (response.success && response.sub_categories.length > 0) {
                            response.sub_categories.forEach(subCategory => {
                                subCategoryDropdown.append(
                                    `<option value="${subCategory}">${subCategory}</option>`
                                );
                            });
                        } else {
                            subCategoryDropdown.html(
                                '<option disabled>No Subcategories Found</option>');
                        }
                    },
                    error: function() {
                        subCategoryDropdown.html(
                            '<option disabled>Error fetching data</option>');
                    }
                });
            });

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


        Dropzone.autoDiscover = false;

        var filenames = []; // Array to store filenames

        var myDropzone = new Dropzone("#dropzone", {
            url: "{{ route('upload.images') }}", // Your Laravel route
            paramName: "product_images[]", // Ensure it's an array for multiple files
            maxFiles: 10, // Allow multiple files (max 10)
            acceptedFiles: "image/*", // Accept only image files
            addRemoveLinks: true,
            maxFilesize: 2, // Max file size 2 MB per image
            chunking: false, // Disable chunking

            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },

            init: function() {
                var submitButton = document.getElementById("add_product_btn");

                // Disable button before upload
                submitButton.disabled = true;

                this.on("addedfile", function() {
                    submitButton.disabled = true; // Disable when file is added
                });

                this.on("success", function(file, response) {
                    if (Array.isArray(response)) {
                        filenames = filenames.concat(response); // Merge response array properly
                    } else {
                        filenames.push(response);
                    }

                    document.getElementById("product_images").value = JSON.stringify(filenames);
                    submitButton.disabled = false;
                });

                this.on("removedfile", function(file) {
                    // Remove filename from array when file is removed
                    filenames = filenames.filter(name => name !== file.name);

                    // Update hidden input
                    document.getElementById("product_images").value = JSON.stringify(filenames);

                    // Disable button if no files left
                    submitButton.disabled = filenames.length === 0;
                });

                this.on("error", function() {
                    submitButton.disabled = true; // Keep disabled on error
                });
            }
        });
    </script>



@endsection
