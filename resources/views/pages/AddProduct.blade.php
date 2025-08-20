@extends('layout')
@section('title', 'Add Product')
@section('nav-title', 'Add Product')
@section('content')
    <style>
        .variant-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: auto;
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

        /* Styling for a Specific Dropzone */
        #my-dropzone {
            border: 3px dotted blue;
            background-color: lightyellow;

        }

        .boost-checkmark {
            display: none;
        }

        .peer:checked~div .boost-checkmark {
            display: inline-block;
        }

        .boost-checkmark {
            color: white;
            font-size: 1.25rem;
        }
    </style>
    <div class="w-full pt-10 min-h-[86vh] px-5  rounded-lg custom-shadow">

        <form id="productForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex justify-between items-center">
                <h3 class="text-[20px] font-medium">Add Product</h3>
            </div>

            <div>

                <div class="md:py-5">
                    <div class="mt-5">
                        <label for="images" class="block text-sm font-medium text-gray-700">
                            Upload Product Images
                        </label>
                        <p class="text-xs text-red-500 mt-1">
                            Please upload images of resolution 1080 × 1080 pixels for best quality.
                        </p>

                        <div id="dropzone" class="dropzone p-0" style="min-height: auto !important;"></div>
                        <!-- Dropzone container -->
                        <input type="hidden" name="product_images" id="product_images">
                        <input value="{{ $products ? $products->product_id : '' }}" type="hidden" name="product_edit_id"
                            id="product_edit_id">
                    </div>

                    <div class=" mt-5">
                        <x-input value="{{ $products ? $products->product_name : '' }}" type="text" label="Title"
                            placeholder="Title Here" id="title" name="title" />
                    </div>
                    <div class=" mt-5">
                        <x-textarea type="text" label="Description" placeholder="Description Here" required
                            id="description" name="description" :value="$products->product_description ?? ''" />
                    </div>
                    <div class="mt-5 flex flex-wrap gap-4 items-end">
                        <div>
                            <x-input value="{{ $products ? $products->product_brand : '' }}" type="text"
                                label="Brand / Company" placeholder="Brand / Company" id="company" name="company" />
                        </div>

                        <div id="category-selects" class="flex gap-4">
                            <div class="dynamic-subcategory">
                                <label class="block mb-1 text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" id="category_id"
                                    class="dynamic-category block w-full rounded border-gray-300">
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if (!empty($categoryIds) && isset($categoryIds[0]) && $categoryIds[0] == $category->id) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div>
                            <x-input value="{{ $products ? $products->purchase_price : '' }}" type="number"
                                label="Purchase Price" placeholder="Price Here" id="purchase_price" name="purchase_price" />
                        </div>

                        <div>
                            <x-input value="{{ $products ? $products->product_price : '' }}" type="number"
                                label="Product Price" placeholder="Price Here" id="product_price" name="product_price" />
                        </div>

                        <div>
                            <x-input value="{{ $products ? $products->product_discount : '' }}" type="number"
                                label="Discount (%)" placeholder="Discount Here" id="discount" name="discount" />
                        </div>

                        <div>
                            <x-input value="{{ $products ? $products->product_discounted_price : '' }}" type="number"
                                label="Discounted Price" placeholder="Discounted Price Here" id="discounted_price"
                                name="discounted_price" />
                        </div>

                        {{-- These are for product size --}}
                        <div>
                            <x-input value="{{ $products ? $products->weight : '' }}" type="number" step="0.01"
                                label="Estimated Weight (kg)" required placeholder="Weight in kg" id="weight"
                                name="weight" />
                        </div>

                        <div>
                            <x-input value="{{ $products ? $products->length : '' }}" type="number" step="0.01"
                                label="Estimated Length (inches)" required placeholder="Length in inches" id="length"
                                name="length" />
                        </div>

                        <div>
                            <x-input value="{{ $products ? $products->width : '' }}" type="number" step="0.01"
                                label="Estimated Width (inches)" required placeholder="Width in inches" id="width"
                                name="width" />
                        </div>

                        <div>
                            <x-input value="{{ $products ? $products->height : '' }}" type="number" step="0.01"
                                label="Estimated Height (inches)" required placeholder="Height in inches" id="height"
                                name="height" />
                        </div>

                        <div id="vehicleType-selects" class="flex gap-4">
                            <div class="dynamic-vehicleType">
                                <label class="block mb-1 text-sm font-medium text-gray-700">Vehicle Type
                                    {{ $products ? $products->vehicle_type_id : '' }}</label>
                                <select name="vehicleType" id="vehicleType" required
                                    class="dynamic-vehicleType block w-full rounded border-gray-300">
                                    <option value="" disabled {{ empty($products) ? 'selected' : '' }}>Select
                                        Vehicle Type</option>

                                    @if (!empty($products) && !empty($vehicleTypes))
                                        @foreach ($vehicleTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ !empty($products->vehicle_type_id) && $products->vehicle_type_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->vehicle_type }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Next Section --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3">
                        <div class="variant-box mt-5 ">
                            <div class="variant-title">Variants</div>
                            <button type="button" id="add-option-btn" class="add-option-btn">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/plus-math.png" alt="plus icon">
                                Add options like size or color
                            </button>
                        </div>
                    </div>
                    <div id="options-container" class="flex flex-col md:flex-row"></div>

                    <div class="overflow-x-auto mt-5 border rounded-lg">
                        <table class="w-full table-auto min-w-[600px]">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="py-3 px-4 text-left">Image</th>
                                    <th class="py-3 px-4 text-left">Variant</th>
                                    <th class="py-3 px-4 text-left">Price</th>
                                    <th class="py-3 px-4 text-left">Available Stock</th>
                                </tr>
                            </thead>
                            <tbody id="variantTableBody"></tbody>
                        </table>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <div></div>
                            <button type="submit" id="add_product_btn"
                                class="px-6 py-2 text-white bg-primary rounded-3xl">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    </div>

@endsection

@section('js')
    <!-- Pass product variation data to JavaScript -->
    <script>
        const productVariation = {!! $products->product_variation ?? '[]' !!};
        const baseUrl = '{{ asset('') }}';

        $(document).ready(function() {
            let options = [];

            // Function to initialize options from product_variation
            function initializeVariants() {
                if (productVariation.length === 0) return;

                let parentOptionName = productVariation[0].parent_option_name;
                let parentValues = productVariation.map(v => v.parentname);
                let childOptionName = productVariation[0].children[0]?.child_option_name || "";
                let childValues = childOptionName ? [...new Set(productVariation.flatMap(v => v.children.map(c => c
                    .name)))] : [];

                options.push({
                    name: parentOptionName,
                    values: parentValues
                });
                if (childOptionName) {
                    options.push({
                        name: childOptionName,
                        values: childValues
                    });
                }

                options.forEach((option, index) => {
                    let optionHtml = `
                <div class="variant-box border rounded-lg bg-gray-100 mr-4 p-4" data-option-index="${index}">
                    <div class="option-display-container mt-4">
                        <div class="option-display p-2 bg-gray-200 rounded mt-2">
                            <strong>${option.name}</strong>
                            <div class="option-values mt-2 flex gap-2">
                                ${option.values.map(value => `<span class="px-3 py-1 bg-gray-300 rounded">${value}</span>`).join('')}
                            </div>
                        </div>
                    </div>
                    <div class="edit-container hidden">
                        <div class="input-group mb-2">
                            <label>Option name</label>
                            <input type="text" class="option-name bg-gray-50 border text-sm rounded-lg w-full p-2.5" value="${option.name}">
                        </div>
                        <div class="input-group">
                            <label>Option values</label>
                            <div class="values-container">
                                ${option.values.map(value => `
                                                                                                                                                                                                <div class="value-item flex items-center mb-2">
                                                                                                                                                                                                    <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" value="${value}">
                                                                                                                                                                                                    <button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>
                                                                                                                                                                                                </div>`).join('')}
                                <div class="value-item flex items-center mb-2">
                                    <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" placeholder="Add Value">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-4">
                        <button class="delete-btn bg-red-700 text-white px-4 py-2 rounded">Delete</button>
                        <button type="button" class="edit-btn bg-blue-600 text-white px-4 py-2 rounded">Edit</button>
                    </div>
                    <input type="hidden" name="options[${index}][name]" value="${option.name}">
                    ${option.values.map(value => `<input type="hidden" name="options[${index}][values][]" value="${value}">`).join('')}
                </div>`;
                    $("#options-container").append(optionHtml);
                });

                generateVariants(true);
            }

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
                <div class="option-display-container mt-4 hidden"></div>
                <div class="edit-container">
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
                </div>
                <div class="flex justify-between mt-4">
                    <button class="delete-btn bg-red-700 text-white px-4 py-2 rounded">Delete</button>
                    <button type="button" class="done-btn bg-gray-800 text-white px-4 py-2 rounded">Done</button>
                    <button type="button" class="edit-btn bg-blue-600 text-white px-4 py-2 rounded hidden">Edit</button>
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

            $(document).on("click", ".delete-btn", function() {
                let container = $(this).closest(".variant-box");
                let optionIndex = container.data("option-index");

                options.splice(optionIndex, 1);

                if (optionIndex === 0 && productVariation.length > 0) {
                    productVariation.length = 0;
                } else if (optionIndex === 1 && productVariation.length > 0) {
                    productVariation.forEach(variant => {
                        variant.children = [];
                    });
                }

                container.remove();
                $(".variant-box").each(function(index) {
                    $(this).attr("data-option-index", index);
                });

                generateVariants(productVariation.length > 0);
            });

            $(document).on("click", ".edit-btn", function() {
                let container = $(this).closest(".variant-box");
                let optionIndex = container.data("option-index");

                if (container.find(".edit-container").hasClass("hidden")) {
                    // Switch to edit mode
                    container.find(".option-display-container").addClass("hidden");
                    container.find(".edit-container").removeClass("hidden");
                    container.find(".edit-btn").text("Done").removeClass("bg-blue-600").addClass(
                        "bg-gray-800");
                    container.find(".delete-btn").removeClass("hidden");
                    container.find(".done-btn").addClass("hidden");

                    // Ensure the last empty field has a remove button if it’s not the only field
                    let valuesContainer = container.find(".values-container");
                    let valueItems = valuesContainer.find(".value-item");
                    let lastValueItem = valueItems.last();
                    if (valueItems.length > 1 && !lastValueItem.find(".remove-value-btn").length) {
                        lastValueItem.append(`
                    <button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>
                `);
                    }
                } else {
                    // Switch to display mode and save changes
                    let optionName = container.find(".option-name").val().trim();
                    let values = container.find(".option-value").map(function() {
                        return $(this).val().trim();
                    }).get().filter(v => v !== "");

                    if (!optionName || values.length === 0) {
                        alert("Option name and at least one value are required.");
                        return;
                    }

                    // Update options array
                    options[optionIndex] = {
                        name: optionName,
                        values: values
                    };

                    // Update hidden inputs
                    container.find("input[type=hidden]").remove();
                    container.append(`
                <input type="hidden" name="options[${optionIndex}][name]" value="${optionName}">
                ${values.map(value => `<input type="hidden" name="options[${optionIndex}][values][]" value="${value}">`).join('')}
            `);

                    // Update display
                    let optionDisplayHtml = `
                <div class="option-display p-2 bg-gray-200 rounded mt-2">
                    <strong>${optionName}</strong>
                    <div class="option-values mt-2 flex gap-2">
                        ${values.map(value => `<span class="px-3 py-1 bg-gray-300 rounded">${value}</span>`).join('')}
                    </div>
                </div>`;
                    container.find(".option-display-container").html(optionDisplayHtml).removeClass(
                        "hidden");
                    container.find(".edit-container").addClass("hidden");
                    container.find(".edit-btn").text("Edit").removeClass("bg-gray-800").addClass(
                        "bg-blue-600");
                    container.find(".done-btn").addClass("hidden");
                    container.find(".delete-btn").removeClass("hidden");

                    // Update edit-container for next edit
                    container.find(".edit-container .values-container").html(`
                ${values.map(value => `
                                                                                                                                                                                <div class="value-item flex items-center mb-2">
                                                                                                                                                                                    <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" value="${value}">
                                                                                                                                                                                    <button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>
                                                                                                                                                                                </div>`).join('')}
                <div class="value-item flex items-center mb-2">
                    <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" placeholder="Add Value">
                    ${values.length > 0 ? `<button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>` : ''}
                </div>
            `);
                    container.find(".edit-container .option-name").val(optionName);

                    // Regenerate variants
                    generateVariants(productVariation.length > 0);
                }
            });

            $(document).on("click", ".done-btn", function() {
                let container = $(this).closest(".variant-box");
                let optionIndex = container.data("option-index");
                let optionName = container.find(".option-name").val().trim();
                let values = container.find(".option-value").map(function() {
                    return $(this).val().trim();
                }).get().filter(v => v !== "");

                if (!optionName || values.length === 0) {
                    alert("Option name and at least one value are required.");
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
                container.find(".option-display-container").html(optionDisplayHtml).removeClass("hidden");
                container.find(".edit-container").addClass("hidden");
                container.find(".done-btn").addClass("hidden");
                container.find(".edit-btn").removeClass("hidden");

                let hiddenInputsHtml = `
            <input type="hidden" name="options[${optionIndex}][name]" value="${optionName}">
            ${values.map(value => `<input type="hidden" name="options[${optionIndex}][values][]" value="${value}">`).join('')}
        `;
                container.append(hiddenInputsHtml);

                // Update edit-container for next edit
                container.find(".edit-container .values-container").html(`
            ${values.map(value => `
                                                                                                                                                                            <div class="value-item flex items-center mb-2">
                                                                                                                                                                                <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" value="${value}">
                                                                                                                                                                                <button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>
                                                                                                                                                                            </div>`).join('')}
            <div class="value-item flex items-center mb-2">
                <input type="text" class="option-value bg-gray-50 border text-sm rounded-lg w-full p-2.5" placeholder="Add Value">
                ${values.length > 0 ? `<button class="remove-value-btn bg-red-600 px-2 py-1 ml-2 rounded text-white">-</button>` : ''}
            </div>
        `);
                container.find(".edit-container .option-name").val(optionName);

                generateVariants();
            });

            function generateVariants(isEdit = false) {
                let tableBody = $("#variantTableBody");
                tableBody.empty();

                if (options.length === 0) return;

                let parentValues = options[0].values;
                let parentOptionName = options[0].name;
                let childValues = options.length > 1 ? options[1].values : [];
                let childOptionName = options.length > 1 ? options[1].name : "";

                parentValues.forEach((parentValue, parentIndex) => {
                    let variantData = isEdit ? productVariation.find(v => v.parentname === parentValue) :
                        null;
                    let parentPrice = variantData?.parentprice || "";
                    let parentStock = variantData?.parentstock || "";
                    let parentImage = variantData?.parentimage ? `${baseUrl}${variantData.parentimage}` :
                        "";

                    let parentRow = `
                <tr class="parent-row bg-gray-100 border-b border-gray-300" data-parent-index="${parentIndex}">
                    <td class="py-3 px-4">
                        <input type="hidden" name="variants[${parentIndex}][parentname]" value="${parentValue}">
                        <input type="hidden" name="variants[${parentIndex}][parent_option_name]" value="${parentOptionName}">
                        <input type="file" name="variants[${parentIndex}][parentimage]" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white image-upload">
                        ${parentImage ? `<div class="image-preview mt-2"><img src="${parentImage}" class="w-16 h-16 object-cover rounded"></div>` : ""}
                    </td>
                    <td class="py-3 px-4 font-semibold text-gray-800 flex items-center space-x-2">
                        ${childValues.length > 0 ? `<button type="button" class="toggle-child cursor-pointer text-gray-600 hover:text-gray-800 transition" data-target="#child-rows-${parentIndex}">▼</button>` : ""}
                        <span>${parentValue}</span>
                        ${childValues.length > 0 ? `<span class="text-sm text-gray-500">(${childValues.length} variants)</span>` : ""}
                    </td>
                    <td class="py-3 px-4">
                        <input type="number" id="parent-price-${parentIndex}" name="variants[${parentIndex}][parentprice]" value="${parentPrice}" class="price-input w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="Rs 0.00">
                    </td>
                    <td class="py-3 px-4">
                        <input type="number" id="parent-stock-${parentIndex}" name="variants[${parentIndex}][parentstock]" value="${parentStock}" class="stock-input w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="0">
                        <input type="hidden" name="variants[${parentIndex}][parenttotalstock]" id="parent-total-stock-${parentIndex}" value="${parentStock}">
                    </td>
                </tr>`;
                    tableBody.append(parentRow);

                    if (childValues.length > 0) {
                        let childRowContainer = `<tbody id="child-rows-${parentIndex}" class="hidden">`;
                        childValues.forEach((childValue, childIndex) => {
                            let childData = variantData?.children.find(c => c.name ===
                                childValue) || {};
                            let childPrice = childData.price || "";
                            let childStock = childData.stock || "";
                            let childImage = childData.image ? `${baseUrl}${childData.image}` : "";

                            childRowContainer += `
                        <tr class="child-row bg-white border-b border-gray-300" data-parent-index="${parentIndex}" data-child-index="${childIndex}">
                            <td class="py-3 px-4">
                                <input type="hidden" name="variants[${parentIndex}][children][${childIndex}][name]" value="${childValue}">
                                <input type="hidden" name="variants[${parentIndex}][children][${childIndex}][child_option_name]" value="${childOptionName}">
                                <input type="file" name="variants[${parentIndex}][children][${childIndex}][image]" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white image-upload">
                                ${childImage ? `<div class="image-preview mt-2"><img src="${childImage}" class="w-16 h-16 object-cover rounded"></div>` : ""}
                            </td>
                            <td class="py-3 px-4 pl-12 text-gray-700 flex items-center space-x-2">
                                <span>${childValue}</span>
                            </td>
                            <td class="py-3 px-4">
                                <input type="number" name="variants[${parentIndex}][children][${childIndex}][price]" value="${childPrice}" class="child-price-${parentIndex} w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="Rs 0.00">
                            </td>
                            <td class="py-3 px-4">
                                <input type="number" name="variants[${parentIndex}][children][${childIndex}][stock]" value="${childStock}" class="child-stock-${parentIndex} w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700" placeholder="0">

                                <input type="hidden" name="variants[${parentIndex}][children][${childIndex}][stock_total]" id="stock-total-hidden-${parentIndex}-${childIndex}" value="${childStock}">
                            </td>

                        </tr>`;
                        });
                        childRowContainer += `</tbody>`;
                        tableBody.append(childRowContainer);
                    }
                });

                $(".toggle-child").off("click").on("click", function() {
                    let target = $(this).data("target");
                    $(target).toggleClass("hidden");
                    $(this).text($(target).hasClass("hidden") ? "▼" : "▲");
                });

                if (options.length > 1) {
                    $(".price-input").on("input", function() {
                        let parentIndex = $(this).attr("id").split("-").pop();
                        let parentPrice = $(this).val();
                        $(`.child-price-${parentIndex}`).val(parentPrice);
                    });

                    $(".stock-input").on("input", function() {
                        let parentIndex = $(this).attr("id").split("-").pop();
                        let parentStock = $(this).val();
                        $(`.child-stock-${parentIndex}`).val(parentStock);
                    });
                }
            }

            $(document).on("change", ".image-upload", function(event) {
                let file = event.target.files[0];
                let previewContainer = $(this).siblings(".image-preview") || $(this).after(
                    '<div class="image-preview mt-2"></div>').siblings(".image-preview");

                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.html(
                            `<img src="${e.target.result}" class="w-16 h-16 object-cover rounded">`);
                    };
                    reader.readAsDataURL(file);
                }
            });

            if (productVariation.length > 0) {
                initializeVariants();
            }


            $("#description").on("input", function() {
                let maxLength = 150;
                let currentLength = $(this).val().length;

                if (currentLength > maxLength) {
                    // Trim the extra characters
                    $(this).val($(this).val().substring(0, maxLength));

                    // SweetAlert message
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit Exceeded',
                        text: 'Description can only be up to 150 characters.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
    @if ($products && $products->product_images)
        <script>
            // Pass the existing images to JavaScript
            window.existingImages = @json(json_decode($products->product_images, true));
        </script>
    @endif
    <script>
        $(document).ready(function() {
            // Blade passes the selected category IDs for edit; empty [] when adding new
            const selectedCategoryIds = {!! json_encode($categoryIds ?? []) !!};

            $(document).ready(function() {
                if (selectedCategoryIds.length > 0) {
                    loadCategoriesRecursively(1, selectedCategoryIds);
                }
            });

            // On change: dynamically load subcategories
            $(document).on('change', '.dynamic-category', function() {
                let selectedCategoryId = $(this).val();
                let level = $('#category-selects .dynamic-category').length;
                $(this).closest('div').nextAll('.dynamic-subcategory').remove();
                if (selectedCategoryId) {
                    loadSubcategories(selectedCategoryId, level);
                }
            });

            function loadSubcategories(parentId, level, preselectedId = null) {
                $.ajax({
                    url: `/get-subcategories/${parentId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.sub_categories.length > 0) {
                            let fieldName = (level === 1) ? 'subcategory_id' :
                                (level === 2) ? 'sub_subcategory_id' :
                                `category_level_${level}`;
                            let newSelectHtml = `
                    <div class="dynamic-subcategory ml-2">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Sub Category</label>
                        <select name="${fieldName}" class="dynamic-category block w-full rounded border-gray-300">
                            <option value="" disabled ${!preselectedId ? 'selected' : ''}>Select Sub Category</option>
                            ${response.sub_categories.map(sub =>
                                `<option value="${sub.id}" ${preselectedId == sub.id ? 'selected' : ''}>${sub.name}</option>`
                            ).join('')}
                        </select>
                    </div>`;
                            $('#category-selects').append(newSelectHtml);
                            // Load next level if preselected
                            if (preselectedId) {
                                let nextLevel = level + 1;
                                let nextIndex = selectedCategoryIds.indexOf(preselectedId) + 1;
                                let nextPreselectId = selectedCategoryIds[nextIndex];
                                if (nextPreselectId) {
                                    loadSubcategories(preselectedId, nextLevel, nextPreselectId);
                                }
                            }
                        }
                    },
                    error: function() {
                        alert('Error fetching subcategories.');
                    }
                });
            }

            function loadCategoriesRecursively(startIndex, idsArray) {
                let parentId = idsArray[startIndex - 1];
                let preselectedId = idsArray[startIndex];
                let level = startIndex;
                if (preselectedId) {
                    loadSubcategories(parentId, level, preselectedId);
                }
            }

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

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @js(session('success')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif

            // SweetAlert error (flash message)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: @js(session('error')),
                    confirmButtonColor: '#d33'
                });
            @endif

        });


        Dropzone.autoDiscover = false;
        var filenames = []; // Array to store file paths
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
                var dropzoneInstance = this;

                // Populate existing images from the database
                if (window.existingImages && Array.isArray(window.existingImages)) {
                    window.existingImages.forEach(function(imagePath) {
                        var filename = imagePath.split('/').pop();
                        var mockFile = {
                            name: filename,
                            size: 12345,
                            accepted: true,
                            status: Dropzone.SUCCESS
                        };

                        dropzoneInstance.emit("addedfile", mockFile);
                        dropzoneInstance.emit("thumbnail", mockFile, "{{ url('/') }}/" +
                            imagePath);
                        dropzoneInstance.emit("complete", mockFile);
                        filenames.push(imagePath);
                    });

                    document.getElementById("product_images").value = JSON.stringify(filenames);
                    submitButton.disabled = filenames.length === 0;
                }

                // Handle new file uploads
                this.on("addedfile", function(file) {
                    submitButton.disabled = true; // Disable until validation is done

                    // Validate resolution
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image();
                        img.onload = function() {
                            var width = img.width;
                            var height = img.height;

                            // REQUIRED DIMENSIONS (change as needed)
                            var requiredWidth = 1080;
                            var requiredHeight = 1080;

                            if (width !== requiredWidth || height !== requiredHeight) {
                                dropzoneInstance.removeFile(file);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Image Size',
                                    text: 'Image must be exactly ' + requiredWidth +
                                        'x' + requiredHeight + ' pixels.',
                                    confirmButtonColor: '#d33'
                                });

                                return;
                            }

                            // If valid size, re-enable button when ready
                            submitButton.disabled = false;
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });

                this.on("success", function(file, response) {
                    if (Array.isArray(response)) {
                        filenames = filenames.concat(response);
                    } else {
                        filenames.push(response);
                    }
                    document.getElementById("product_images").value = JSON.stringify(filenames);
                    submitButton.disabled = false;
                });

                this.on("removedfile", function(file) {
                    var pathToRemove = filenames.find(path => path.endsWith(file.name));
                    if (pathToRemove) {
                        filenames = filenames.filter(path => path !== pathToRemove);
                    }
                    document.getElementById("product_images").value = JSON.stringify(filenames);
                    submitButton.disabled = filenames.length === 0;
                });

                this.on("error", function(file, errorMessage) {
                    submitButton.disabled = true;
                    console.error("Upload error:", errorMessage);
                });
            }
        });
    </script>
@endsection
