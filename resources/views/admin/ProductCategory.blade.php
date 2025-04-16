@extends('layout')
@section('title', 'Product Category')
@section('nav-title', 'Product Category')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium">Product Category List</h1>
                <button id="addModalBtn" data-modal-target="productcategory-modal" data-modal-toggle="productcategory-modal"
                    class="px-5 py-3 font-semibold text-white rounded-full bg-primary">Add New</button>
        </div>
        @php
            $headers = ['ID', 'Image', 'Name', 'No. of Sub Category', 'No. of Products', 'Status', 'Action'];
        @endphp
        {{-- {{ dd($categories) }} --}}
        <x-table :headers="$headers">
            <x-slot name="tablebody">

                @if ($categories->isNotEmpty())
                    @foreach ($categories as $categorie)
                        <tr>
                            <td> {{ $loop->iteration }}</td>
                            <td>
                                {{-- <img class="rounded-full w-11 h-11" src="{{ asset('' . $categorie->image) }}" alt="Jese image"> --}}
                                @php
                                    $imagePath = $categorie->image;
                                    $defaultImage = asset('asset/Ellipse 2.png');
                                    $finalImage =
                                        !empty($imagePath) && file_exists(public_path($imagePath))
                                            ? asset($imagePath)
                                            : $defaultImage;
                                @endphp
                                <img class="rounded-full w-11 h-11" src="{{ $finalImage }}" alt="Product image">

                            </td>
                            <td class="capitalize">{{ $categorie->name }}</td>
                            <td>{{ $categorie->subcategory_count }}</td>
                            <td>{{ $categorie->product_count }}</td>
                            <td><span
                                    class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span>
                            </td>
                            <td>
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
                                    <button class="deleteDataBtn" delurl="../deleteProductCategory/{{ $categorie->id }}">
                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                            xmlns='http://www.w3.org/2000/svg'>
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
                    @endforeach
                @else
                    <p class="text-gray-500">No categories available.</p>
                @endif
            </x-slot>
        </x-table>

        <x-modal id="productcategory-modal" x-show="isOpen" x-cloak>
            <x-slot name="title">Product Category</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form action="{{ route('productCategory') }}" class="categoryForm" method="POST" id="categoryForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="md:py-5">
                        <!-- Image Upload -->
                        <div class="px-6 mt-5 w-[200px] h-[150px] mb-5 mx-auto">
                            <label class="block mb-2 text-sm font-medium text-center text-gray-700">Category Image</label>
                            <x-file-uploader type="text" label="Banner" placeholder="Banner Here" id="image"
                                name="image" />
                        </div>

                        <!-- Category Name -->
                        <div class="px-6 mt-10">
                            <x-input type="text" label="Category Name" placeholder="Your name..." value=""
                                id="name" name="name" />
                        </div>

                        <!-- Sub Categories -->
                        <div class="px-6 mt-5">
                            <label class="block mb-2 text-sm font-medium text-customBlack">Sub Categories</label>
                            <div id="tag-container" class="flex flex-wrap gap-2 p-2 border rounded-md">
                                <input type="text" id="tag-input-field"
                                    class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-primary focus:border-primary block p-2.5 w-full"
                                    placeholder="Enter a tag and press Enter">
                                <div class="flex flex-wrap gap-2 p-2 border rounded-md" id="view-tag-container">

                                </div>
                            </div>
                            <input type="hidden" name="sub_categories" id="tag-inputs">
                            {{-- <button type="button" id="add-tag-btn"
                                class="px-3 py-1 text-white bg-blue-500 rounded-r-md hover:bg-blue-600">
                                +
                            </button> --}}
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 bg-gray-300 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <button type="button"
                                class="px-4 py-2 text-white bg-red-500 rounded close-modal">Close</button>
                            <button type="submit" id="submit"
                                class="px-6 py-2 text-white bg-primary rounded-3xl">Submit</button>
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
                        <ul id="categorySubCategories" class="p-3 bg-gray-100 rounded-lg shadow-sm capitalize"></ul>
                    </div>
                </div>

            </x-slot>
        </x-modal>
    </div>

@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Define createTagElement globally

        let tags = []; // Move this outside to make it globally accessible

        function createTagElement(tag) {
            let tagElement = $("<span>")
                .addClass("bg-blue-500 text-white px-2 py-1 rounded flex items-center mr-2 mt-1")
                .text(tag);

            let removeButton = $("<button>")
                .html("&times;")
                .addClass("ml-2 cursor-pointer text-white font-bold")
                .click(function() {
                    tags = tags.filter(t => t !== tag);
                    tagElement.remove();
                    updateHiddenInput();
                });

            tagElement.append(removeButton);
            $("#view-tag-container").append(tagElement);
        }

        function updateHiddenInput() {
            $("#tag-inputs").val(JSON.stringify(tags)); // Store as a proper JSON string
        }

        // Ensure updateData is defined globally
        function updateData(id) {
            $("#categoryForm").attr("action", "/ProductCategory/update/" + id);
            $("#productcategory-modal").removeClass("hidden");
            $('#productcategory-modal').removeClass('hidden flex items-start')
                .addClass('flex items-center justify-center bg-gray-900 bg-opacity-50');

            $.ajax({
                url: '/ProductCategory/getforupdate/' + id,
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log("AJAX Response:", response);


                    if (response.status === 'success') {
                        $('#productcategory-modal').attr("aria-hidden", "true");
                        $('#name').val(response.data.name);
                        $("#submit").text("Update");


                        console.log("Raw sub_categories:", response.data.sub_categories);

                        let subCategories = [];

                        try {
                            if (typeof response.data.sub_categories === "string") {
                                subCategories = JSON.parse(response.data.sub_categories);
                            } else if ($.isArray(response.data.sub_categories)) {
                                subCategories = response.data.sub_categories;
                            } else {
                                console.error("Unexpected sub_categories format:", response.data
                                    .sub_categories);
                            }
                        } catch (error) {
                            console.error("JSON Parse Error:", error);
                        }

                        console.log("Parsed subCategories:", subCategories);

                        tags = subCategories.slice();
                        $("#tag-inputs").val("");
                        $("#view-tag-container").empty();
                        $.each(subCategories, function(index, tag) {
                            createTagElement(tag);
                        });

                        updateHiddenInput();

                    } else {
                        alert('Data not found!');
                    }
                },
                error: function() {
                    alert('Error fetching data.');
                }
            });
        }

        $(document).ready(function() {
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

            $(".viewModalBtn").on("click", function() {
                let categoryId = $(this).data("id");

                $.ajax({
                    url: "/fetch-category/" + categoryId,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.status == "success") {
                            // $("#categoryImage").attr("src", "{{ asset('') }}" + response
                            //     .data.image);
                            let imageUrl = response.data.image ?
                                "{{ asset('') }}" + response.data.image :
                                "{{ asset('asset/Ellipse 2.png') }}";
                            $("#categoryImage")
                                .attr("src", imageUrl)
                                .on("error", function() {
                                    $(this).attr("src",
                                        "{{ asset('asset/Ellipse 2.png') }}");
                                });




                            $("#categoryName").text(response.data.name);

                            let subCategories = JSON.parse(response.data.sub_categories);
                            let subCategoryHtml = "";

                            subCategories.forEach(function(sub) {
                                subCategoryHtml +=
                                    `<li class="py-1 font-medium text-gray-800">${sub}</li>`;
                            });

                            $("#categorySubCategories").html(subCategoryHtml);
                            $("#editproductcategory-modal").show();
                        } else {
                            alert("Category not found!");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        alert("Something went wrong. Please try again.");
                    }
                });
            });
            // $("#addModalBtn").on("click", function() {
            //     $("#submit").text("Submit");
            //     $("#categoryForm").attr("action", "/ProductCategory");

            // });
            $(document).ready(function() {
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

                                // Optionally, close the modal
                                $("#productcategory-modal").addClass("hidden");

                                // Reset the form
                                $("#categoryForm")[0].reset();
                                $("#view-tag-container").empty();
                                tags = [];
                                updateHiddenInput();

                                // Optionally reload the table or part of the page
                                setTimeout(() => location.reload(), 2000);
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message ||
                                        "Something went wrong"
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = "An error occurred.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: message
                            });
                        }
                    });
                });
            });

            $(document).ready(function() {
                // When any close button inside a modal is clicked
                $("[data-modal-hide]").click(function() {
                    let modalId = $(this).attr("data-modal-hide"); // Get the modal ID
                    let modal = $("#" + modalId);

                    modal.addClass("hidden"); // Hide modal

                    // Remove dynamically added hidden inputs inside the modal forms
                    modal.find("form").each(function() {
                        $(this).find("input[type='hidden']")
                            .remove(); // Remove all hidden inputs
                        this.reset(); // Reset the form fields
                    });
                });
            });

        });
    </script>
