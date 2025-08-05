@extends('layout')
@section('title', 'Sellers Management')
@section('nav-title', 'Sellers Management')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Sellers List</h1>
        </div>
        @php
            $headers = ['Sr.', 'Seller Id', 'Seller name', 'Joined Date', 'Status', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                @foreach ($sellers as $seller)
                    <tr style="text-transform: capitalize">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $seller->seller_id }}</td>
                        <td class="capitalize">{{ $seller->user->user_name }}</td>
                        <td>{{ $seller->submission_date }}</td>
                        <td><span
                                class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">{{ $seller->status }}</span>
                        </td>
                        <td>
                            <span class='flex gap-4'>
                                <a id="addmanagesellerBtn" class="viewModalBtn"
                                    href="{{ route('SellerProfile', $seller->seller_id) }}">
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
                                </a>
                                <button class="disable_btn" data-id="{{ $seller->user_id }}"
                                    data-modal-target="disableSellerModal" data-modal-toggle="disableSellerModal">
                                    <img src="{{ asset('asset/delete.png') }}" width="30" alt="">
                                </button>
                                @if ($seller->user->user_status == 1)
                                    <span class="text-green-600 font-medium">Active</span>
                                @else
                                    <span class="text-red-600 font-medium">Disabled</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>

    <!-- Modal -->
    <x-modal id="disableSellerModal" x-show="isOpen" x-cloak>
        <x-slot name="title">Change Seller Status</x-slot>
        <x-slot name="modal_width">max-w-2xl</x-slot>

        <x-slot name="body">
            <form action="{{ route('disable.seller') }}" method="POST" id="statusForm">
                @csrf

                <!-- Hidden input to dynamically fill seller ID -->
                <input type="hidden" name="seller_id" id="modalSellerId">

                <!-- Seller Status Dropdown -->
                <div class="px-6 mt-5">
                    <label for="user_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Seller Status
                    </label>
                    <select name="user_status" id="user_status"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required onchange="toggleReasonInput(this.value)">
                        <option value="1">Active</option>
                        <option value="0">Disable</option>
                    </select>
                </div>

                <!-- Reason input (shown only if Disabled is selected) -->
                <div class="px-6 mt-5 hidden" id="reasonField">
                    <label for="disabled_reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Reason for Disabling
                    </label>
                    <input type="text" name="disabled_reason" id="disabled_reason"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Enter reason...">
                </div>

                <!-- Buttons -->
                <div class="mt-6 rounded-b-lg">
                    <div class="flex items-center justify-end p-2">
                        <button type="submit" class="px-6 py-2 text-white bg-blue-700 hover:bg-blue-800 rounded-lg">
                            Update
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
            $('.disable_btn').on('click', function() {
                var sellerId = $(this).data('id');
                $('#modalSellerId').val(sellerId); // Set the hidden input
                $.ajax({
                    url: "{{ route('get.seller.status', '') }}/" + sellerId,
                    type: "GET",
                    success: function(response) {
                        // Set the dropdown value based on the seller's current status
                        // alert(response.data.user_status);
                        var user_status = response.data.user_status;
                        var user_status_reason = response.data.disabled_reason || '';
                        $('#user_status').val(user_status).change(); // Trigger change to show/hide reason input
                        $('#disabled_reason').val(user_status_reason); // Trigger change to show/hide reason input
                        toggleReasonInput(user_status); // Show/hide reason input based on status
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            Swal.fire({
                                icon: "error",
                                title: "Session Expired",
                                text: "Your session has expired. Please refresh the page and try again."
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Failed to fetch seller status."
                            });
                        }
                    }
                });
            });

            // Form submission
            $("#statusForm").on("submit", function(e) {
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

                            $("#disableSellerModal").addClass("hidden");
                            $("#statusForm")[0].reset();
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message || "Something went wrong"
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            Swal.fire({
                                icon: "error",
                                title: "Session Expired",
                                text: "Your session has expired. Please refresh the page and try again."
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            let message = xhr.responseJSON?.message || "An error occurred.";
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: message
                            });
                        }
                    }
                });
            });
        });

        function toggleReasonInput(value) {
            const reasonField = document.getElementById('reasonField');
            if (value == '0') {
                reasonField.classList.remove('hidden');
            } else {
                reasonField.classList.add('hidden');
            }
        }

        // Trigger on page load if "Disable" is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const selectedStatus = document.getElementById('user_status').value;
            toggleReasonInput(selectedStatus);
        });
    </script>





@endsection
