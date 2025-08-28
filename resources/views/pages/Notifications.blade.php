@extends('layout')
@section('title', 'Notifications')
@section('nav-title', 'Notifications')
@section('content')
    <div class="px-5 w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Notifications List</h1>
                @if (session('user_details.user_role') == 'admin' || canMenuAction('notifications', 'add'))
                    <button id="addModalBtn" data-modal-target="notification-modal" data-modal-toggle="notification-modal"
                        class="px-5 py-3 font-semibold text-white rounded-full bg-primary">
                        Send New
                    </button>
                @endif
        </div>
        @php
            $headers = ['Sr.', 'Title', 'Description', 'Type', 'Date Sent', 'Status', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @foreach ($notifications as $data)
                    <tr style="text-transform: capitalize">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->title }}</td>
                        <td>{{ $data->description }}</td>
                        <td>{{ $data->type }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d M, Y') }}</td>
                        <td>
                            <span
                                class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">{{ $data->notification_status }}</span>
                        </td>
                        <td>
                            <span class='flex gap-4'>
                                @if (session('user_details.user_role') == 'admin' || canMenuAction('notifications', 'delete'))
                                    <button class="deleteDataBtn" data-id="{{ $data->notification_id }}">

                                        <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                            xmlns='http://www.w3.org/2000/svg'>
                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                                fill='#D11A2A' />
                                        </svg>
                                    </button>
                                @endif
                                <button data-modal-target="view-notification-modal"
                                    data-modal-toggle="view-notification-modal" class="viewModalBtn">
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
                                                <stop offset='1' stop-color='#FE8A29' />F
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </button>
                            </span>
                        </td>
                    </tr>
                @endforeach

            </x-slot>
        </x-table>


        <x-modal id="notification-modal">
            <x-slot name="title">Send Notification</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form class="submitForm" action="{{ route('send-notification') }}" method="POST">
                    @csrf
                    <div class="md:py-5">
                        {{-- Product Category Form --}}
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="Title" placeholder="Name Here" id="notification_title"
                                name="title" value="" />
                        </div>
                        <div class="px-6 mt-5">
                            <label class="block text-gray-700  font-medium text-sm mb-2">Description</label>
                            <x-input type="text" label="" placeholder="Name Here" id="notification_d"
                                name="description" value="" />
                        </div>
                        <div class="flex gap-6 px-6 mt-5">
                            <!-- Type -->
                            <div class="w-full">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Type</label>
                                <select id="type" name="type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option disabled selected>Select Type</option>
                                    <option value="seller">Sellers</option>
                                    <option value="freelancer">Freelancers</option>
                                    <option value="customer">Customers</option>
                                </select>
                            </div>

                            <!-- Target Audience -->
                            {{-- <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Target
                                    Audience</label>
                                <select id="target-audience"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Choose audience</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="FR">France</option>
                                    <option value="DE">Germany</option>
                                </select>
                            </div> --}}
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="mt-6 bg-gray-300 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <div></div>
                            <button class="px-6 py-2 text-white bg-primary rounded-3xl">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal id="view-notification-modal">
            <x-slot name="title">Notification Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form>
                    @csrf
                    <div class="md:py-5">
                        {{-- Product Category Form --}}
                        <div class="flex gap-6 px-6 mt-5">
                            <!-- Status -->
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Date</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $date ?? '24 Jan, 2024' }}
                                </p>
                            </div>
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Type</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $type ?? 'Promotional' }}
                                </p>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Target
                                    Audience</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $t_audience ?? 'Seller' }}
                                </p>
                            </div>
                            <div class="w-1/2">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Title</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $subject ?? 'Subject Details' }}
                                </p>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-full">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Description</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    Got it! Here's the updated form where Date, Email, Subject, and Message are displayed
                                    inside
                                    tags instead of input fields. The Response field remains as a textarea for user input.
                                </p>
                            </div>
                        </div>

                        <!-- Response -->
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-full">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Response</label>
                                <textarea id="response" rows="4"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Enter response"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 bg-gray-300 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <div></div>
                            @if (session('user_details.user_role') == 'admin' || canMenuAction('notifications', 'edit'))
                                <button type="submit" class="px-6 py-2 text-white bg-primary rounded-3xl">
                                    Submit
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>


    </div>


@endsection


@section('js')
    <script></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token globally
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.deleteDataBtn').on('click', function() {
                let id = $(this).data('id');
                // alert(id);
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
                            url: `/delete-notification/${id}`,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your notification has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the notification.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
