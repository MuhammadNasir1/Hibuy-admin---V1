@extends('layout')
@section('title', 'My Store')
@section('nav-title', 'My Store')
@section('content')
    <div class="w-full pt-10 pb-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">My Store</h2>
            <button id="viewModalBtn" data-modal-target="edit-profile-modal" data-modal-toggle="edit-profile-modal"
                class="px-5 py-3 font-semibold text-white rounded-full bg-primary">Edit Profile</button>
        </div>

        <div
            class="h-[200px] shadow-xl bg-gradient-to-r from-[#4A90E2] rounded-xl mx-6 mt-3 via-green-300 to-[#FFCE31] flex justify-center items-center">
            <div class="h-[80%] w-[95%] bg-white rounded-xl flex items-center gap-5">
                <img src="{{ asset('asset/pic.jpg') }}" class="ms-5 h-[80px] w-[80px] rounded-full" alt="">
                <div>
                    <h3 class="text-lg font-semibold ">My Store</h3>
                    <p class="text-sm text-gray-500 flex gap-3 items-center pt-1">
                        <span>
                            <svg class="h-[15px] fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M234.5 5.7c13.9-5 29.1-5 43.1 0l192 68.6C495 83.4 512 107.5 512 134.6l0 242.9c0 27-17 51.2-42.5 60.3l-192 68.6c-13.9 5-29.1 5-43.1 0l-192-68.6C17 428.6 0 404.5 0 377.4L0 134.6c0-27 17-51.2 42.5-60.3l192-68.6zM256 66L82.3 128 256 190l173.7-62L256 66zm32 368.6l160-57.1 0-188L288 246.6l0 188z" />
                            </svg>
                        </span>105 Product Listed
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-5 mx-6 shadow-xl rounded-xl min-h-[100px]">
            <img src="{{ asset('asset/banner.png') }}" alt="Banner Image" class="w-full  object-cover rounded-xl">
        </div>

        <div class="flex justify-center px-6 gap-5 ">
            <div class="lg:h-[370px] h-[200px] lg:w-[370px] w-[200px] rounded-xl  mt-5 shadow-xl"
                style="background-image: url('{{ asset('asset/post-1.png') }}'); background-size: cover; background-position: center;">
            </div>
            <div class="lg:h-[370px] h-[200px] lg:w-[370px] w-[200px] rounded-xl mt-5 shadow-xl"
                style="background-image: url('{{ asset('asset/post-2.png') }}'); background-size: cover; background-position: center;">
            </div>
        </div>

        <x-modal id="edit-profile-modal">
            <x-slot name="title">Edit Profile </x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="">
                    <div class="">
                        <form>
                            @csrf
                            <div class="md:py-5">
                                {{-- Profile --}}
                                <div
                                    class="h-[100px] bg-gradient-to-r from-[#4A90E2] rounded-t-xl mx-6 mt-3 via-green-300 to-[#FFCE31]">
                                </div>
                                <input type="hidden" name="upload-banner" id="upload-banner">

                                <div
                                    class="h-[100px] w-[100px] bg-primary overflow-hidden rounded-full flex items-center justify-center mx-auto -mt-16">
                                    <x-file-uploader name="profile_picture" id="profile_picture" />
                                </div>

                                <p id="storeName" class="text-center pt-3 font-medium">My Store</p>
                                <div class="flex justify-center gap-5 mt-3">
                                    <div
                                        class="rounded-[100px] border flex justify-center items-center text-gray-500 text-sm">
                                        <p class="px-5 py-1">c1</p>
                                    </div>
                                    <div
                                        class="rounded-[100px] border flex justify-center items-center text-gray-500 text-sm">
                                        <p class="px-5 py-1">c2</p>
                                    </div>
                                    <div
                                        class="rounded-[100px] border flex justify-center items-center text-gray-500 text-sm">
                                        <p class="px-5 py-1">red</p>
                                    </div>
                                    <div
                                        class="rounded-[100px] border flex justify-center items-center text-gray-500 text-sm">
                                        <p class="px-5 py-1">green</p>
                                    </div>
                                </div>

                                <hr class="mt-5 mx-7">

                                {{-- Store form --}}

                                <div class="px-6 mt-5">
                                    <x-input type="text" label="Store Name" placeholder="Name Here" id="store_name"
                                        name="store_name" />
                                </div>
                                <div class="px-6 mt-5">
                                    <label class="block mb-2 text-sm font-medium text-customBlack ">Tags</label>
                                    <div id="tag-container" class="flex flex-wrap gap-2 border p-2 rounded-md hidden">
                                        <!-- Tags will be added here -->
                                    </div>
                                    <div class="flex mt-2">
                                        <input type="text" id="tag-input"
                                            class=" border border-gray-300 text-gray-900 text-sm rounded-l-md focus:ring-primary focus:border-primary block w-full p-2.5"
                                            placeholder="Enter a tag...">
                                        <button type="button" id="add-tag-btn"
                                            class="px-3 py-1 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <div class="px-6 mt-5 ">
                                    <label class="block text-gray-700  font-medium text-sm mb-2">Banner</label>
                                    <x-file-uploader type="text" label="Banner" id="Banner"
                                        name="Banner" />
                                </div>

                                <div class="px-6 mt-5">
                                    <label class="block text-gray-700 font-medium text-sm mb-2">Posts</label>
                                    <div class="flex gap-5">
                                        <x-file-uploader name="profile_picture" id="profile_picture" />
                                        <x-file-uploader name="profile_picture" id="profile_picture" />
                                    </div>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="mt-6 bg-gray-300 rounded-b-lg">
                                <div class="flex items-center justify-between p-2">
                                    {{-- <button type="button"
                                        class="px-3 py-1.5 text-gray-700 bg-gray-300 border-2 border-gray-400 rounded-3xl">
                                        Close
                                    </button> --}}
                                    <div></div>
                                    <button type="button" class="px-6 py-2 text-white bg-primary rounded-3xl">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </x-slot>
        </x-modal>
    </div>


@endsection

@section('js')
<script>
    $(document).ready(function () {
        $("#add-tag-btn").click(function () {
            addTag();
        });

        $("#tag-input").keypress(function (e) {
            if (e.which === 13) { // Enter key pressed
                e.preventDefault(); // Prevent form submission
                addTag();
            }
        });

        function addTag() {
            let tagText = $("#tag-input").val().trim();
            if (tagText !== "" && !isDuplicateTag(tagText)) {
                let tag = `
                    <div class="flex items-center bg-gray-200 px-3 py-1 rounded-md text-sm">
                        <span>${tagText}</span>
                        <button class="ml-2 text-gray-500 hover:text-gray-700 remove-tag">&times;</button>
                    </div>
                `;
                $("#tag-container").append(tag).removeClass("hidden"); // Show container
                $("#tag-input").val("").focus(); // Clear input & refocus
            }
        }

        function isDuplicateTag(tagText) {
            let isDuplicate = false;
            $("#tag-container span").each(function () {
                if ($(this).text().trim() === tagText) {
                    isDuplicate = true;
                }
            });
            return isDuplicate;
        }

        $(document).on("click", ".remove-tag", function () {
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
