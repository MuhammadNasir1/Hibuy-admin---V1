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
                <img src="{{ !empty($storeData['store_image'])
                    ? asset($storeData['store_image'])
                    : (!empty($storeData['profile_picture_store'])
                        ? asset($storeData['profile_picture_store'])
                        : asset('asset/defualt-image.png')) }}"
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

        <div id="default-carousel" class="relative w-full " data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden rounded-lg md:h-96 mx-6 mt-4">
                @php
                    // Default banner if nothing is set
                    $defaultBanners = ['asset/banner.png'];

                    // Get banners array from storeData
                    $banners = [];
                    if (!empty($storeData['store_banners']) && is_array($storeData['store_banners'])) {
                        foreach ($storeData['store_banners'] as $banner) {
                            if (!empty($banner['image'])) {
                                $banners[] = $banner['image'];
                            }
                        }
                    }

                    // Use default if none found
                    if (empty($banners)) {
                        $banners = $defaultBanners;
                    }
                @endphp

                @foreach ($banners as $banner)
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="{{ asset($banner) }}"
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2
                            top-1/2 left-1/2 rounded-xl"
                            alt="Banner">
                    </div>
                @endforeach
            </div>

            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                @foreach ($banners as $index => $banner)
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}" data-carousel-slide-to="{{ $index }}">
                    </button>
                @endforeach
            </div>

            <!-- Slider controls -->
            <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                    <svg class="w-4 h-4 text-white rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 1 1 5l4 4" />
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                    <svg class="w-4 h-4 text-white rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
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
                                <div id="profile_picture_preview" class=""> </div>
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
                                <div class="relative flex flex-col items-center justify-center w-full h-full">
                                    <!-- Preview container -->
                                    <div id="preview-container"
                                        class="flex flex-wrap gap-2 p-2 w-full mb-2 border border-gray-200 rounded-lg min-h-20">
                                    </div>

                                    <!-- Upload area -->
                                    <label
                                        class="flex flex-col items-center justify-center w-full min-h-24 bg-gray-100 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer file-upload-label overflow-hidden">
                                        <!-- Upload prompt -->
                                        <div id="upload-prompt"
                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <!-- SVG and text -->
                                            <span class="font-semibold">Upload Banners</span></p>
                                            <p class="text-xs text-gray-500">Max 2MB each Size 820*312</p>
                                        </div>
                                        <input type="file" class="hidden file-input" id="banner_input"
                                            name="banners[]" accept="image/*" multiple onchange="previewFiles(event)" />
                                    </label>
                                    <div id="error-container" class="text-red-500 text-sm mt-2 hidden"></div>
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
        window.bannerFiles = []; // New files to upload
        window.removedBannerIds = []; // Existing banner IDs to delete

        // Function to remove a banner
        function removeBanner(element, file = null, bannerId = null) {
            // Remove from DOM
            element.remove();

            // Track what we're removing
            if (bannerId) {
                // Existing banner from server - mark for deletion
                window.removedBannerIds.push(bannerId);
            } else if (file) {
                // New file - remove from upload queue
                window.bannerFiles = window.bannerFiles.filter(f => f !== file);
            }

            // Show upload prompt if no banners left
            if (document.getElementById('preview-container').children.length === 0) {

            }
        }

        // Preview new files
        function previewFiles(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('preview-container');
            const errorContainer = document.getElementById('error-container');

            errorContainer.innerHTML = '';
            errorContainer.style.display = 'none';

            const maxSizeMB = 2;
            const maxWidth = 820;
            const maxHeight = 312;

            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    // Add size validation check
                    if (file.size > maxSizeMB * 1024 * 1024) {
                        errorContainer.innerHTML += `<div>${file.name} is too large (max ${maxSizeMB}MB)</div>`;
                        errorContainer.style.display = 'block';
                        return; // Skip this file if too large
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            // Add dimension validation check
                            if (img.width > maxWidth || img.height > maxHeight) {
                                errorContainer.innerHTML +=
                                    `<div>${file.name} must be no larger than ${maxWidth}×${maxHeight} pixels</div>`;
                                errorContainer.style.display = 'block';
                                return; // Skip this file if dimensions are wrong
                            }

                            // Original functionality remains unchanged below
                            const previewWrapper = document.createElement('div');
                            previewWrapper.classList.add('banner-wrapper', 'relative', 'w-32', 'h-20',
                                'inline-block', 'm-2');

                            // Store file reference
                            previewWrapper._file = file;

                            const previewImg = document.createElement('img');
                            previewImg.src = e.target.result;
                            previewImg.classList.add('w-full', 'h-full', 'object-cover', 'rounded');

                            const removeBtn = document.createElement('button');
                            removeBtn.innerHTML = '✖';
                            removeBtn.onclick = () => removeBanner(previewWrapper, file);
                            removeBtn.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500',
                                'text-white', 'rounded-full', 'w-5', 'h-5', 'flex', 'items-center',
                                'justify-center', 'text-xs', 'cursor-pointer', 'shadow');

                            previewWrapper.appendChild(previewImg);
                            previewWrapper.appendChild(removeBtn);
                            previewContainer.appendChild(previewWrapper);

                            // Add to upload queue
                            window.bannerFiles.push(file);
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }
            event.target.value = '';
        }

        // For existing banners loaded from server
        function removeExistingBanner(id, btn) {
            removeBanner(btn.closest('.banner-wrapper'), null, id);
        }

        // For server-side banners (loaded from DB)
        function removeExistingBanner(id, btn) {
            window.removedBannerIds.push(id);
            removeBanner(btn.closest('.banner-wrapper'), null);
        }

        $(document).ready(function() {

            $("#banner_input").on("change", previewFiles);
            $("#viewModalBtn").on("click", function() {
                let viewstoreurl = $(this).attr("viewstoreurl");

                if (!viewstoreurl) {
                    alert("Invalid store URL!");
                    return;
                }

                $("#modal-btn").click(); // Open modal

                $.ajax({
                    url: viewstoreurl,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (!response || !response.data) {
                            alert("Failed to load store data.");
                            return;
                        }

                        // Store Name
                        $("#store_name").val(response.data.store_name || "");
                        $("#storeName").text(response.data.store_name || "My Store");

                        // Profile Image
                        let profileImagePath = response.data.store_image || "";
                        $("#profile_picture_preview").html(
                            profileImagePath ?
                            `<img src="${profileImagePath}" class="h-[100px] w-[100px] rounded-full object-cover" alt="Profile Picture">` :
                            ""
                        );

                        // Store Banners
                        let previewContainer = $("#preview-container");
                        previewContainer.empty();
                        window.removedBannerIds = []; // Reset removed banner IDs

                        if (Array.isArray(response.data.store_banners) && response.data
                            .store_banners.length > 0) {
                            response.data.store_banners.forEach(banner => {
                                previewContainer.append(`
                                <div class="banner-wrapper relative w-32 h-20 inline-block m-2">
                                    <img src="${banner.image}" class="w-full h-full object-cover rounded" alt="Store Banner">
                                    <button type="button"
                                        onclick="removeExistingBanner('${banner.id}', this)"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs cursor-pointer shadow">
                                        ✖
                                    </button>
                                </div>
                            `);
                            });
                            // $("#upload-prompt").addClass("hidden");
                        } else {
                            $("#upload-prompt").removeClass("hidden");
                        }

                        // Store Tags
                        if (Array.isArray(response.data.store_tags) && response.data.store_tags
                            .length > 0) {
                            $("#tags").html("");
                            response.data.store_tags.forEach(tag => {
                                $("#tags").append(
                                    `<div class="rounded-[100px] border flex justify-center items-center text-gray-500 text-sm">
                                    <p class="px-5 py-1">${tag}</p>
                                </div>`
                                );
                            });
                            $("#tags").removeClass("hidden");
                        } else {
                            $("#tags").addClass("hidden");
                        }

                        // Store Posts
                        let postContainer = $("#store_posts_container");
                        postContainer.empty();

                        if (Array.isArray(response.data.store_posts) && response.data
                            .store_posts.length > 0) {
                            let postHtml = `<div class="flex gap-5 my-3">`;
                            response.data.store_posts.forEach(post => {
                                postHtml += `
                                <div class="w-1/2">
                                    <img src="${post.image}" class="w-full h-[120px] object-cover rounded-lg" alt="Post Image">
                                </div>
                            `;
                            });
                            postHtml += `</div>`;
                            postContainer.append(postHtml);
                        }

                        // Product Count
                        $("#product_count").text(response.data.product_count || 0);
                    },
                    error: function() {
                        alert("Failed to fetch store details. Please try again.");
                    }
                });
            });

            // Form submission for edit store
            $("#store_form").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Add removed server banner IDs
                formData.append('removed_banners', JSON.stringify(window.removedBannerIds));

                // Add new banner files
                window.bannerFiles.forEach((file, index) => {
                    formData.append(`banners[${index}]`, file);
                });

                $.ajax({
                    url: "{{ route('editStoreProfile') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#submit_store").text("Submitting...").prop("disabled", true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reset files after successful upload
                            window.bannerFiles = [];
                            $('#banner_input').val('');

                            Swal.fire({
                                title: 'Success!',
                                text: response.msg,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
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
                        $("#submit_store").text("Submit").prop("disabled", false);
                    }
                });
            });
            // Store name live update
            $("#store_name").on("input", function() {
                $("#storeName").text($(this).val());
                if (this.value === "") {
                    $("#storeName").text("My Store");
                }
            });

            // Tag management
            let tagsArray = [];
            $("#add-tag-btn").click(function() {
                addTag();
            });

            $("#tag-input").keypress(function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    addTag();
                }
            });

            function addTag() {
                let tagText = $("#tag-input").val().trim();
                if (tagText !== "" && !isDuplicateTag(tagText)) {
                    tagsArray.push(tagText);
                    updateTagsHiddenInput();

                    let tag = `
                    <div class="flex items-center bg-gray-200 px-3 py-1 rounded-md text-sm">
                        <span>${tagText}</span>
                        <button class="ml-2 text-gray-500 hover:text-gray-700 remove-tag" data-tag="${tagText}">&times;</button>
                    </div>
                `;
                    $("#tag-container").append(tag).removeClass("hidden");
                    $("#tag-input").val("").focus();
                }
            }

            function isDuplicateTag(tagText) {
                return tagsArray.includes(tagText);
            }

            function updateTagsHiddenInput() {
                $("#tags-hidden").val(tagsArray.join(","));
            }

            $(document).on("click", ".remove-tag", function() {
                let tagText = $(this).data("tag");
                tagsArray = tagsArray.filter(tag => tag !== tagText);
                updateTagsHiddenInput();
                $(this).parent().remove();

                if ($("#tag-container").children.length === 0) {
                    $("#tag-container").addClass("hidden");
                }
            });
        });
    </script>
@endsection
