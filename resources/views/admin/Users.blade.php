@extends('layout')
@section('title', 'Users')
@section('nav-title', 'Users')
@section('content')
    <div class="w-full pt-10 min-h-[86vh] rounded-lg custom-shadow">
        <div class="flex justify-between items-center px-5">
            <h2 class="text-2xl font-medium">Users List</h2>
            <div>
                <button id="addUserModalBtn" type="button" data-modal-target="add-user-modal"
                    data-modal-toggle="add-user-modal" class="px-3 py-2 font-semibold text-white rounded-full bg-primary">
                    Add User
                </button>
            </div>
        </div>
        <x-table :headers="['ID', 'Name', 'Email', 'Role', 'Status', 'Added By', 'Created At', 'Action']">
            <x-slot name="tablebody">
                @foreach ($users as $user)
                    <tr>
                        <!-- ID -->
                        <td>{{ $user->user_id }}</td>

                        <!-- Name -->
                        <td class="capitalize">{{ $user->user_name }}</td>

                        <!-- Email -->
                        <td>{{ $user->user_email }}</td>

                        <!-- Role -->
                        <td class="capitalize">{{ $user->user_role }}</td>

                        <!-- Status -->
                        <td>
                            @if ($user->user_status == 1)
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Inactive</span>
                            @endif
                        </td>

                        <!-- Added By -->
                        <td>{{ $user->addedBy->user_name ?? 'N/A' }}</td>

                        <!-- Created At -->
                        <td>{{ $user->created_at->format('d M Y') }}</td>

                        <!-- Action -->
                        <td>
                            <span class="flex gap-4">
                                <!-- Edit Button -->
                                <button editurl="{{ route('users.edit', $user->user_id) }}" class="updateDataBtn">
                                    <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                        xmlns='http://www.w3.org/2000/svg'>
                                        <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                            d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188Z'
                                            fill='#233A85' />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button delurl="{{ route('users.delete', $user->user_id) }}" class="deleteDataBtn">
                                    <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                        xmlns='http://www.w3.org/2000/svg'>
                                        <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                            d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                            fill='#D11A2A' />
                                    </svg>
                                </button>
                                <a href="{{ route('menus.privileges', $user->user_id) }}" target="_blank"
                                    class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                                    Privileges
                                </a>


                            </span>
                        </td>
                    </tr>
               @endforeach
            </x-slot>
        </x-table>

        <x-modal id="add-user-modal">
            <x-slot name="title" id="title">Add User</x-slot>
            <x-slot name="modal_width">max-w-2xl</x-slot>
            <x-slot name="body">
                <form id="addUserForm" method="POST" enctype="multipart/form-data" action="{{ '/users/store' }}">
                    @csrf
                    <div class="space-y-5">
                        <!-- Row 1: User Name and User Email -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- User Name -->
                            <div>
                                <label for="user_name" class="block text-sm font-medium text-gray-700 mb-1">User
                                    Name</label>
                                <input type="text" name="user_name" id="user_name"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter user name">
                            </div>
                            <!-- User Email -->
                            <div>
                                <label for="user_email" class="block text-sm font-medium text-gray-700 mb-1">User
                                    Email</label>
                                <input type="email" name="user_email" id="user_email"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter user email">
                            </div>
                        </div>

                        <!-- Row 2: User Password and User Role -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- User Password -->
                            <div>
                                <label for="user_password"
                                    class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <input type="password" name="user_password" id="user_password"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                                        placeholder="Enter password">
                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg id="eyeIcon" class="h-5 w-5 text-gray-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <!-- User Role -->
                            <div>
                                <label for="user_role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="user_role" id="user_role"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select role</option>
                                    <option value="manager">Manager</option>
                                    <option value="staff">Staff</option>
                                    {{-- <option value="support">Support</option> --}}
                                </select>
                            </div>
                        </div>

                        <!-- Row 3: User Status (single column, centered) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="user_status"
                                    class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="user_status" id="user_status"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2 flex justify-end">
                            <button type="submit" id="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#user_password');
                const eyeIcon = $('#eyeIcon');
                const isPassword = passwordInput.attr('type') === 'password';

                passwordInput.attr('type', isPassword ? 'text' : 'password');

                eyeIcon.html(isPassword ?
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.975 9.975 0 011.689-2.854"></path>
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 9.88a3 3 0 104.24 4.24"></path>
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.73 5.08A10.43 10.43 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411"></path>` :
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`
                );
            });

            // Open modal for editing a user
            $(document).on('click', '.updateDataBtn', function() {
                $("#add-user-modal")
                    .removeClass("hidden")
                    .addClass("flex items-center justify-center bg-gray-900 bg-opacity-50");
                $("#submit").text("Update");
                const editUrl = $(this).attr('editurl');
                if (!editUrl) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid URL',
                        text: 'Edit URL is missing or invalid!'
                    });
                    return;
                }

                $.ajax({
                    url: editUrl,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.success) {

                            const user = response.user;
                            $('#addUserForm').attr('action',
                                '{{ route('users.update', ':id') }}'.replace(':id', user
                                    .user_id));
                            $('#title').text('Edit User');
                            $('#user_name').val(user.user_name || '');
                            $('#user_email').val(user.user_email || '');
                            $('#user_role').val(user.user_role || '').trigger('change');
                            $('#user_status').val(user.user_status || '').trigger('change');
                            $('#user_password').val(''); // Clear password field
                            $('#user_password').parent().show();
                            $('#add-user-modal').removeClass('hidden'); // Flowbite modal show
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: response.message || 'Failed to load user data.'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'An error occurred while fetching user data.'
                        });
                    }
                });
            });
            // AJAX for deleting a user
            $(document).on('click', '.deleteDataBtn', function() {
                const deleteUrl = $(this).attr('delurl');
                if (!deleteUrl) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid URL',
                        text: 'Delete URL is missing or invalid!'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE'
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message ||
                                            'User has been deleted.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload(); // Refresh user list
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: response.message ||
                                            'Failed to delete user.'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ||
                                        'An error occurred while deleting the user.'
                                });
                            }
                        });
                    }
                });
            });

            $('#addUserModalBtn').on('click', function() {
                $('#addUserForm').attr('action', '{{ route('users.store') }}');
                $('#addUserForm').attr('method', 'POST');
                $('#addUserForm').find('input[name="_method"]').remove();
                $('#submit').text('Submit');
                $('#addUserForm')[0].reset(); // Reset form to empty all fields
                $('#user_name').val('');
                $('#user_email').val('');
                $('#user_role').val('').trigger('change');
                $('#user_status').val('').trigger('change');
                modal.show();
            });
            // AJAX form submission for add/edit user
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const formData = new FormData(this);
                const url = $form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $form.find('button[type="submit"]').text('Submitting...').prop(
                            'disabled', true);
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $form[0].reset();
                                $('#add-user-modal').addClass(
                                    'hidden'); // Flowbite modal hide
                                location.reload(); // Refresh user list
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred while submitting the form.';
                        if (xhr.status === 422 && xhr.responseJSON?.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 500) {
                            errorMsg =
                                'Server error: Please check the server logs for details.';
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMsg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        $form.find('button[type="submit"]').text('Submit').prop('disabled',
                            false);
                    }
                });
            });
        });
    </script>
@endsection
