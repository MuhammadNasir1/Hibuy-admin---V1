@extends('layout')
@section('title', 'My Store')
@section('nav-title', 'My Store')
@section('content')
    <div class="w-full pt-10 pb-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium "></h2>
            <button id="viewModalBtn"
                viewstoreurl="{{ isset($storeData['store_id']) || !empty($storeData['store_id']) ? '/view-store/' . $storeData['store_id'] : '/view-store/' . $storeData['seller_id'] }}"
                class="px-5 py-3 font-semibold text-white rounded-full bg-primary">
                {{ !empty($storeData['store_id']) ? 'Edit Profile' : 'Cretae Profile' }}
            </button>
            <button class="hidden" id="modal-btn" data-modal-target="edit-profile-modal" data-modal-toggle="edit-profile-modal">
            </button>
        </div>

        <div
            class="h-[200px] shadow-xl bg-gradient-to-r from-[#4A90E2] rounded-xl mx-6 mt-3 via-green-300 to-[#FFCE31] flex justify-center items-center">
            <div class="h-[80%] w-[95%] bg-white rounded-xl flex items-center gap-5">
                <img src="{{ !empty($storeData['store_image']) ? asset($storeData['store_image']) : asset('asset/defualt-image.png') }}"
                    class="ms-5 h-[80px] w-[80px] rounded-full" alt="">
                <div>
                    <h3 class="text-lg font-semibold">
                        {{ isset($storeData['store_name']) && !empty($storeData['store_name']) ? $storeData['store_name'] : 'Your Store Name' }}
                    </h3>
                    <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                        <span>
                            <svg class="h-[15px] fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M234.5 5.7c13.9-5 29.1-5 43.1 0l192 68.6C495 83.4 512 107.5 512 134.6l0 242.9c0 27-17 51.2-42.5 60.3l-192 68.6c-13.9 5-29.1 5-43.1 0l-192-68.6C17 428.6 0 404.5 0 377.4L0 134.6c0-27 17-51.2 42.5-60.3l192-68.6zM256 66L82.3 128 256 190l173.7-62L256 66zm32 368.6l160-57.1 0-188L288 246.6l0 188z" />
                            </svg>
                        </span>{{ $storeData['product_count'] ?? 0 }} Product Listed
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-5 mx-6 shadow-xl rounded-xl min-h-[100px]">
            <img src="{{ asset(@$storeData['store_banner'] ? @$storeData['store_banner'] : 'asset/banner.png') }}"
                alt="Banner Image" class="w-full object-cover rounded-xl">
        </div>

        <div class="flex justify-center px-6 gap-5">
            @php
                $defaultImages = ['asset/post-1.png', 'asset/post-2.png'];
            @endphp

            @foreach ($defaultImages as $index => $defaultImage)
                @php
                    $imagePath = isset($storeData['store_posts'][$index]['image'])
                        ? $storeData['store_posts'][$index]['image']
                        : $defaultImage;
                @endphp
                <div class="lg:h-[370px] h-[200px] lg:w-[370px] w-[200px] rounded-xl mt-5 shadow-xl"
                    style="background-image: url('{{ asset($imagePath) }}'); background-size: cover; background-position: center;">
                </div>
            @endforeach
        </div>


        <x-modal id="edit-profile-modal">
            <x-slot name="title">Edit Profile </x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="">
                    <form id="store_form" enctype="multipart/form-data">
                        @csrf
                        <div class="md:py-5">

                            <div
                                class="h-[100px] bg-gradient-to-r from-[#4A90E2] rounded-t-xl mx-6 mt-3 via-green-300 to-[#FFCE31]">
                            </div>
                            <input type="hidden" name="upload-banner" id="upload-banner">

                            <div class="flex items-center justify-center mx-auto gap-1 -mt-16">
                                <div id="profile_picture_preview" class=""
                                    > </div>
                                <div class="h-[100px] w-[100px] bg-primary overflow-hidden rounded-full">
                                    <x-file-uploader name="profile_picture" id="profile_picture_input" />

                                </div>
                            </div>
                            <p id="storeName" class="text-center pt-3 font-medium">My Store</p>
                            <div class="flex justify-center gap-5 mt-3" id="tags">
                                <!-- Dynamic tags will be inserted here -->
                            </div>
                            <div class="flex justify-center gap-5 mt-3" id="tag-container">
                                <!-- Dynamic tags will be inserted here -->
                            </div>

                            <hr class="mt-5 mx-7">

                            {{-- Store form --}}

                            <div class="px-6 mt-5">
                                <x-input value="" type="text" label="Store Name" placeholder="Name Here"
                                    id="store_name" name="store_name" />
                            </div>

                            <div class="px-6 mt-5">
                                <label class="block mb-2 text-sm font-medium text-customBlack">Tags</label>
                                <div id="tag-container" class="flex flex-wrap gap-2 border p-2 rounded-md hidden"></div>
                                <div class="flex mt-2">
                                    <input type="text" id="tag-input"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-l-md focus:ring-primary focus:border-primary block w-full p-2.5"
                                        placeholder="Enter a tag...">
                                    <input type="hidden" id="tags-hidden" name="tags">
                                    <button type="button" id="add-tag-btn"
                                        class="px-3 py-1 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">
                                        +
                                    </button>
                                </div>
                            </div>

                            <div class="px-6 mt-5">
                                <label class="block text-gray-700 font-medium text-sm mb-2">Banner</label>
                                <x-file-uploader type="text" label="Banner" id="banner_input" name="Banner" />
                                <div class="flex gap-5" id="preview-banner">

                                </div>
                            </div>

                            <div class="px-6 mt-5">
                                <label class="block text-gray-700 font-medium text-sm mb-2">Posts</label>
                                <div class="flex gap-5">
                                    <x-file-uploader name="store_posts[]" id="store_posts" />
                                    <x-file-uploader name="store_posts[]" id="store_posts" />
                                </div>
                                <div class="" id="store_posts_container">
                                    <!-- Dynamic post images will be inserted here -->
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-6 bg-gray-300 rounded-b-lg">
                            <div class="flex items-center justify-between p-2">
                                <div></div>
                                <button type="submit" id="submit_store"
                                    class="px-6 py-2 text-white bg-primary rounded-3xl">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-slot>
        </x-modal>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $("#viewModalBtn").on("click", function() {
                let viewstoreurl = $(this).attr("viewstoreurl"); // Get store details URL

                if (!viewstoreurl) {
                    alert("Invalid store URL!");
                    return;
                }

                $("#modal-btn").click(); // Open modal

                // Run AJAX to fetch store details
                $.ajax({
                    url: viewstoreurl,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response) {
                            // Store Name
                            $("#store_name").val(response.data.store_name || "");
                            $("#storeName").text(response.data.store_name || "My Store");

                            let profileImagePath = response.data.store_image ?
                                response.data.store_image :
                                "";
                                if (profileImagePath) {
                                $("#profile_picture_preview").html(
                                    `<img src="${profileImagePath}" class="h-[100px] w-[100px] rounded-full object-cover" alt="Profile Picture">`
                                );
                            } else {
                                $("#profile_picture_preview").html(
                                    "");
                            }
                            // Store Banner (Full Width)
                            let bannerPath = response.data.store_banner ? response.data
                                .store_banner : "";
                            if (bannerPath) {
                                $("#preview-banner").html(
                                    `<img src="${bannerPath}" class="my-3 w-full h-[200px] object-cover rounded-lg" alt="Store Banner">`
                                );
                            } else {
                                $("#preview-banner").html(
                                    ""); // Clear the preview if no banner exists
                            }
                            // Store Tags
                            if (Array.isArray(response.data.store_tags) && response.data
                                .store_tags.length > 0) {
                                $("#tags").html(""); // Clear existing tags

                                setTimeout(() => {
                                    response.data.store_tags.forEach(tag => {
                                        $("#tags").append(
                                            `<div class="rounded-[100px] border flex justify-center items-center text-gray-500 text-sm">
                                        <p class="px-5 py-1">${tag}</p>
                                    </div>`
                                        );
                                    });
                                }, 100);
                            } else {
                                $("#tags").addClass("hidden");
                            }

                            // Store Posts (Two-Column Flexbox)
                            let postContainer = $("#store_posts_container");
                            postContainer.empty(); // Clear previous images

                            if (response.data.store_posts && response.data.store_posts.length >
                                0) {
                                let postHtml =
                                    `<div class="flex gap-3 my-3">`; // Flex wrap for responsive layout

                                response.data.store_posts.forEach(post => {
                                    let postImagePath = post.image;
                                    postHtml += `
            <div class="w-1/2">
                <img src="${postImagePath}" class="w-full h-[120px] object-cover rounded-lg" alt="Post Image">
            </div>
        `;
                                });

                                postHtml += `</div>`;
                                postContainer.append(postHtml);
                            }

                            // Product Count
                            $("#product_count").text(response.data.product_count || 0);
                        } else {
                            alert("Failed to load store data.");
                        }
                    },
                    error: function() {
                        alert("Failed to fetch store details. Please try again.");
                    }
                });
            });


            //form submission for edit store
            $("#store_form").on("submit", function(e) {
                e.preventDefault(); // Prevent default form submission

                let formData = new FormData(this); // Collect form data including files
                console.log(formData);
                $.ajax({
                    url: "{{ route('editStoreProfile') }}", // Replace with your actual route
                    type: "POST",
                    data: formData,
                    processData: false, // Don't process the files
                    contentType: false, // Prevent jQuery from setting content type
                    beforeSend: function() {
                        $("#submit_store").text("Submitting...").prop("disabled",
                            true); // Disable button
                    },
                    success: function(response) {
                        console.log(response); // Check response in console
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.msg,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location
                                        .reload(); // Reload page after OK is clicked
                                }
                            });
                            console.log("Request Data:", response
                                .request_data); // Log request data
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = Object.values(errors).join("\n");
                            alert("Validation Errors:\n" + errorMsg);
                        } else {
                            alert("Something went wrong!");
                        }
                    },
                    complete: function() {
                        $("#submit_store").text("Submit").prop("disabled",
                            false); // Re-enable button
                    }
                });
            });
        });
        $(document).ready(function() {
            let tagsArray = []; // Array to store tags

            $("#add-tag-btn").click(function() {
                addTag();
            });

            $("#tag-input").keypress(function(e) {
                if (e.which === 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    addTag();
                }
            });

            function addTag() {
                let tagText = $("#tag-input").val().trim();
                if (tagText !== "" && !isDuplicateTag(tagText)) {
                    tagsArray.push(tagText); // Add tag to array
                    updateTagsHiddenInput(); // Update hidden input

                    let tag = `
                <div class="flex items-center bg-gray-200 px-3 py-1 rounded-md text-sm">
                    <span>${tagText}</span>
                    <button class="ml-2 text-gray-500 hover:text-gray-700 remove-tag" data-tag="${tagText}">&times;</button>
                </div>
            `;
                    $("#tag-container").append(tag).removeClass("hidden"); // Show container
                    $("#tag-input").val("").focus(); // Clear input & refocus
                }
            }

            function isDuplicateTag(tagText) {
                return tagsArray.includes(tagText);
            }

            function updateTagsHiddenInput() {
                $("#tags-hidden").val(tagsArray.join(",")); // Convert array to comma-separated string
            }

            $(document).on("click", ".remove-tag", function() {
                let tagText = $(this).data("tag");
                tagsArray = tagsArray.filter(tag => tag !== tagText); // Remove tag from array
                updateTagsHiddenInput(); // Update hidden input
                $(this).parent().remove();

                if ($("#tag-container").children().length === 0) {
                    $("#tag-container").addClass("hidden"); // Hide container when empty
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#store_name").on("input", function() {
                $("#storeName").text($(this).val());
                if (this.value === "") {
                    $("#storeName").text("My Store");
                }
            });
        });
    </script>
@endsection
