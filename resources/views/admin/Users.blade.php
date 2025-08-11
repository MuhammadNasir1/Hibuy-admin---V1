@extends('layout')
@section('title', 'Users')
@section('nav-title', 'Users')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between items-center px-5">
            <h2 class="text-2xl font-medium  ">Users List</h1>
                <div>
                    <button id="addUserModalBtn" type="button" data-modal-target="add-user-modal"
                        data-modal-toggle="add-user-modal" class="px-3 py-2 font-semibold text-white rounded-full bg-primary">
                        Add User
                    </button>
                </div>
        </div>
        <x-table :headers="['ID', 'Name', 'Email', 'Role', 'Status', 'Added By', 'Created At', 'Action']">
            <x-slot name="tablebody">
                    <tr>
                        <td></td>

                        <td class="capitalize"></td>
                        <td></td>

                        <td class="capitalize"></td>

                        <td>
                        </td>

                        <td></td>

                        <td></td>

                        <td>
                            <span class="flex gap-4">
                                <button editurl="" class="updateDataBtn">
                                    <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                        xmlns='http://www.w3.org/2000/svg'>
                                        <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                            d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188Z'
                                            fill='#233A85' />
                                    </svg>
                                </button>

                                <button delurl="" class="deleteDataBtn">
                                    <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                        xmlns='http://www.w3.org/2000/svg'>
                                        <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                            d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                            fill='#D11A2A' />
                                    </svg>
                                </button>
                            </span>
                        </td>
                    </tr>
            </x-slot>
        </x-table>

        <x-modal id="add-faq-category-modal">
            <x-slot name="title">Add FAQ Category</x-slot>
            <x-slot name="modal_width">max-w-lg</x-slot>
            <x-slot name="body">
                <form id="addFaqCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">

                        <!-- Category Name -->
                        <div>
                            <label for="category_name" class="block text-sm font-medium text-gray-700 mb-1">Category
                                Name</label>
                            <input type="hidden" name="category_type" id="category_type" value="faqs">
                            <input type="text" name="category_name" id="category_name"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter category name" required>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="category_image" class="block text-sm font-medium text-gray-700 mb-1">Category
                                Image</label>
                            <input type="file" name="category_image" id="category_image"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                accept="image/*" required>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="Categorystatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="Categorystatus" id="Categorystatus"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="">Select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal id="add-user-modal">
            <x-slot name="title"><span id="faqTitle">Add FAQ</span></x-slot>
            <x-slot name="modal_width">max-w-lg</x-slot>
            <x-slot name="body">
                <form id="addFaqForm" method="POST">
                    @csrf

                </form>
            </x-slot>
        </x-modal>
    </div>

@endsection

@section('js')
    <script>
        $("#addFaqCategoryForm").on("submit", function(e) {
            e.preventDefault(); // prevent default form submission

            let formData = new FormData(this); // includes file upload
            console.log(formData); // log form data to console for debugging
            $.ajax({
                url: "{{ route('faq-category.store') }}", // replace with your actual route
                type: "POST",
                data: formData,
                processData: false, // don't process files
                contentType: false, // prevent default header
                beforeSend: function() {
                    $("#addFaqCategoryForm button[type='submit']").text("Submitting...").prop(
                        "disabled", true);
                },
                success: function(response) {
                    console.log(response); // log response to console
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // reload page
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Something went wrong!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    $("#addFaqCategoryForm button[type='submit']").text("Submit").prop("disabled",
                        false);
                }
            });
        });

        $("#addUserModalBtn").on("click", function(e) {
            // ✅ Reset form before AJAX
            $('#addFaqForm')[0].reset();
        });
        $("#addModalBtn").on("click", function(e) {
            // ✅ Reset form before AJAX
            $('#addFaqCategoryForm')[0].reset();
        });
        $("#addFaqForm").on("submit", function(e) {
            e.preventDefault(); // prevent default form submission

            let formData = $(this).serialize(); // serialize form data
            // console.log(formData); // log form data to console for debugging
            $.ajax({
                url: "{{ route('faq.store') }}", // replace with your actual route
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $("#addFaqForm button[type='submit']").text("Submitting...").prop(
                        "disabled", true);
                },
                success: function(response) {
                    console.log(response); // log response to console
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // reload page
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Something went wrong!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    $("#addFaqForm button[type='submit']").text("Submit").prop("disabled",
                        false);
                }
            });
        });


        $(document).on("click", ".updateDataBtn", function() {
            let editUrl = $(this).attr("editurl");
            // alert(editUrl);
            if (!editUrl) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid URL",
                    text: "Edit URL is missing or invalid!"
                });
                return;
            }

            $.ajax({
                url: editUrl,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        const faq = response.faq;
                        $('#faq_id').val(faq.faq_id);
                        $('#faq_question').val(faq.question);
                        $('#faq_answer').val(faq.answer);
                        $('#faqTitle').text('Edit FAQ');
                        $('#status').val(faq.is_active).change();
                        // Select category after small delay to ensure options exist
                        $('#faq_category_id').val(faq.faq_category).change();
                        // Show modal (Flowbite)
                        const modal = document.getElementById('add-user-modal');
                        if (modal) {
                            modal.classList.remove('hidden'); // remove hidden to show
                        }

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: response.message || "Failed to load FAQ data."
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON?.message ||
                            "An error occurred while fetching FAQ data."
                    });
                }
            });
        });


        $(document).on("click", ".deleteDataBtn", function() {
            let delurl = $(this).attr("delurl");

            if (!delurl) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid URL",
                    text: "Delete URL is missing or invalid!",
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this FAQ? This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: delurl,
                        type: "get",
                        data: {
                            _token: $('input[name="_token"]').val(),
                            _method: "DELETE"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "FAQ has been deleted successfully.",
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Failed!",
                                    text: response.message ||
                                        "Failed to delete FAQ.",
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: xhr.responseJSON?.message ||
                                    "An error occurred while deleting the FAQ.",
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
