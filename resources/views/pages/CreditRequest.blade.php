@extends('layout')
@section('title', 'Credit Request')
@section('nav-title', 'Credit Request')
@section('content')

    <div class="w-full py-4 rounded-lg custom-shadow">
        <div class="flex flex-col items-center text-center mb-4 sm:mb-20">
            <h3 class="text-lg sm:text-xl font-bold text-customBlack mb-2">
                @if (session('user_details.user_role') == 'admin')
                    Total Credit Distributed
                @else
                    My Balance
                @endif
                (Rs)
            </h3>
            <h3 class="text-xl sm:text-2xl font-bold text-customBlack">
                @if (session('user_details.user_role') == 'admin')
                    {{ $approvedTotalAmount }}
                @else
                    {{ $approvedTotalAmount - $totalUsedAmount }}
                @endif
            </h3>
        </div>
        <div class="flex justify-center px-5 text-center my-5">
            <div class="flex w-1/3 flex-col items-center border-r">
                <div class="text-sm sm:text-xl font-bold text-customBlack mb-2">
                    @if (session('user_details.user_role') == 'admin')
                        Pending Requests
                    @else
                        Total Credit Allocated
                    @endif
                </div>
                <div class="text-sm sm:text-xl font-bold text-customBlack">
                    @if (session('user_details.user_role') == 'admin')
                        {{ $pendingCount }}
                    @else
                        {{ $approvedTotalAmount }}
                    @endif
                </div>
            </div>
            <div class="flex w-1/3 flex-col items-center border-r border-l">
                <div class="text-sm sm:text-xl font-bold text-customBlack mb-2">
                    @if (session('user_details.user_role') == 'admin')
                        Approved Requests
                    @else
                        Credit Used
                    @endif
                </div>
                <div class="text-sm sm:text-xl font-bold text-customBlack">
                    @if (session('user_details.user_role') == 'admin')
                        {{ $approvedCount }}
                    @else
                        {{ $totalUsedAmount }}
                    @endif
                </div>
            </div>
            <div class="flex w-1/3 flex-col items-center border-l">
                <div class="text-sm sm:text-xl font-bold text-customBlack mb-2">
                    @if (session('user_details.user_role') == 'admin')
                        Rejected Requests
                    @else
                        Credit Remaining
                    @endif
                </div>
                <div class="text-sm sm:text-xl font-bold text-customBlack">
                    @if (session('user_details.user_role') == 'admin')
                        {{ $rejectedCount }}
                    @else
                        {{ $approvedTotalAmount - $totalUsedAmount }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="w-full mt-3 pt-10 min-h-[86vh] rounded-lg custom-shadow">
        <div class="flex justify-between items-center px-5">
            <h2 class="text-2xl font-medium ">Credit Request History</h2>
            @if (session('user_details.user_role') !== 'admin')
                <button class="px-3 py-2 font-semibold text-white rounded-full bg-primary"
                    data-modal-target="creditrequest-modal" data-modal-toggle="creditrequest-modal">
                    Request Credit
                </button>
            @endif
        </div>
        @php
            $headers = ['Sr.', 'Freelancers Name', 'Requested Amount', 'Reason', 'Requested Date', 'Status', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @foreach ($creditRequests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $request->user_name ?? 'N/A' }}</td>
                        <td>Rs {{ number_format($request->amount, 2) }}</td>
                        <td>{{ $request->reason }}</td>
                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'approved' => 'bg-green-500',
                                    'pending' => 'bg-yellow-500',
                                    'rejected' => 'bg-red-500',
                                ];
                                $color = $statusColors[$request->request_status] ?? 'bg-gray-500';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold text-white rounded {{ $color }}">
                                {{ ucfirst($request->request_status) }}
                            </span>
                        </td>
                        <td>
                            <span class='flex gap-4'>
                                <!-- Eye icon -->
                                <button id="viewmoreBtn" class="viewmoreModalBtn" data-id="{{ $request->credit_id }}"
                                    data-modal-target="viewrequest-modal" data-modal-toggle="viewrequest-modal">
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
                                <button id="viewmoreBtn" class="viewmoreModalBtn">
                                    <svg width="35" height="37" viewBox="0 0 35 37" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g opacity="0.1" filter="url(#filter0_d_635_21720)">
                                            <circle cx="17.5" cy="18" r="17.5" fill="#B4B4B4" />
                                        </g>
                                        <path
                                            d="M17.5 20C17.8315 20 18.1495 20.1317 18.3839 20.3661C18.6183 20.6005 18.75 20.9185 18.75 21.25C18.75 21.5815 18.6183 21.8995 18.3839 22.1339C18.1495 22.3683 17.8315 22.5 17.5 22.5C17.1685 22.5 16.8505 22.3683 16.6161 22.1339C16.3817 21.8995 16.25 21.5815 16.25 21.25C16.25 20.9185 16.3817 20.6005 16.6161 20.3661C16.8505 20.1317 17.1685 20 17.5 20ZM17.5 16.25C17.8315 16.25 18.1495 16.3817 18.3839 16.6161C18.6183 16.8505 18.75 17.1685 18.75 17.5C18.75 17.8315 18.6183 18.1495 18.3839 18.3839C18.1495 18.6183 17.8315 18.75 17.5 18.75C17.1685 18.75 16.8505 18.6183 16.6161 18.3839C16.3817 18.1495 16.25 17.8315 16.25 17.5C16.25 17.1685 16.3817 16.8505 16.6161 16.6161C16.8505 16.3817 17.1685 16.25 17.5 16.25ZM17.5 12.5C17.8315 12.5 18.1495 12.6317 18.3839 12.8661C18.6183 13.1005 18.75 13.4185 18.75 13.75C18.75 14.0815 18.6183 14.3995 18.3839 14.6339C18.1495 14.8683 17.8315 15 17.5 15C17.1685 15 16.8505 14.8683 16.6161 14.6339C16.3817 14.3995 16.25 14.0815 16.25 13.75C16.25 13.4185 16.3817 13.1005 16.6161 12.8661C16.8505 12.6317 17.1685 12.5 17.5 12.5Z"
                                            fill="#1B262C" />
                                        <defs>
                                            <filter id="filter0_d_635_21720" x="0" y="0.5" width="35" height="36"
                                                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                                <feColorMatrix in="SourceAlpha" type="matrix"
                                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                                <feOffset dy="1" />
                                                <feComposite in2="hardAlpha" operator="out" />
                                                <feColorMatrix type="matrix"
                                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0" />
                                                <feBlend mode="normal" in2="BackgroundImageFix"
                                                    result="effect1_dropShadow_635_21720" />
                                                <feBlend mode="normal" in="SourceGraphic"
                                                    in2="effect1_dropShadow_635_21720" result="shape" />
                                            </filter>
                                        </defs>
                                    </svg>
                                </button>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>


        <x-modal id="creditrequest-modal">
            <x-slot name="title">Request Credit</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form id="creditRequestForm" action="{{ route('credit-request.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="{{ session('user_details.user_id') }}">

                    <div class="md:py-5">
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-full">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Amount
                                    (Rs)</label>
                                <input type="number" name="amount" id="amount" placeholder="Enter Here"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                            </div>

                        </div>

                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-full">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Reason</label>
                                <input type="text" name="reason" id="reason" placeholder="Enter Here"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 bg-gray-300 rounded-b-lg">
                        <div class="flex items-center justify-between p-2">
                            <button type="button" data-modal-hide="creditrequest-modal"
                                class="px-6 py-2 text-white bg-red-500 rounded-full close-modal">Close</button>

                            <button type="submit" id="request" class="px-6 py-2 text-white bg-primary rounded-3xl">
                                Send Request
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>

        <x-modal id="viewrequest-modal">
            <x-slot name="title">Credit Request</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">

                <div class="max-w-4xl mx-auto bg-white p-4 md:p-5 space-y-4 rounded-lg shadow-sm">
                    <!-- Customer Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 pt-4 gap-4">
                        {{-- <div class="flex items-center gap-4">
                            <div class="w-32 text-sm font-medium text-gray-600">Status</div>
                            <div id="modalStatus" class="text-base text-gray-700"></div>
                        </div> --}}
                        <div class="flex items-center gap-4">
                            <div class="w-32 text-sm font-medium text-gray-600">Date</div>
                            <div id="modalDate" class="whitespace-nowrap text-base text-gray-700"></div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-32 text-sm font-medium text-gray-600">Amount</div>
                            <div id="modalAmount" class="whitespace-nowrap text-base text-gray-700"></div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-32 text-sm font-medium text-gray-600">Freelancer Name</div>
                            <div id="modalUserName" class="text-base text-gray-700 capitalize"></div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <div class="flex items-start gap-4">
                            <div class="w-32 text-sm font-medium text-gray-600">Reason</div>
                            <div id="modalReason" class="text-base text-gray-700 leading-relaxed"></div>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    @if (session('user_details.user_role') == 'admin')
                        <form id="statusForm" class="max-w-sm mt-6">
                            @csrf
                            <input type="hidden" id="edit_status_id" name="edit_status_id">

                            <div
                                class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mb-4">
                                <label for="request_status" class="text-sm font-medium text-gray-600 w-32">Status</label>
                                <select id="request_status" name="request_status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 w-full sm:w-auto">
                                    <option value="" selected>Select</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <button type="submit" id="submitStatus"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-full transition-all duration-300 shadow-md">
                                Submit
                            </button>
                        </form>
                    @endif

                </div>

            </x-slot>
        </x-modal>
    </div>



@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#creditRequestForm').on('submit', function(e) {
                e.preventDefault();
                // console.log($(this).serialize());
                let formData = {
                    _token: $('input[name="_token"]').val(),
                    amount: $('#amount').val(),
                    reason: $('#reason').val(),
                    user_id: $('#user_id').val()
                };
                console.log(formData);

                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Processing...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('Submit');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Submit');

                        let errorMessage = 'Something went wrong. Please try again.';

                        if (xhr.status === 422) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage
                        });
                    }
                });
            });

            $('.viewmoreModalBtn').on('click', function(e) {
                e.preventDefault();

                const creditId = $(this).data('id');

                $.ajax({
                    url: `/credit-request/${creditId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        $('#modalUserName').text(data.user_name);
                        $('#modalStatus').text(data.request_status);

                        $('#modalAmount').text("Rs " + data.amount);
                        $('#modalReason').text(data.reason);
                        $('#modalStatus').text(data.request_status);
                        const joinedDate = new Date(data.created_at);
                        const formattedDate = joinedDate.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        $('#modalDate').text(formattedDate);
                        $('#edit_status_id').val(data.credit_id);

                        $('#viewrequest-modal').removeClass('hidden');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load request details.'
                        });
                    }
                });
            });

            $('#closeModal').on('click', function() {
                $('#viewrequest-modal').addClass('hidden');

            })

            $("#statusForm").submit(function(event) {
                event.preventDefault();

                let requestStatus = $("#request_status").val();
                let creditRequestId = $("#edit_status_id").val();

                if (creditRequestId === "") {
                    Swal.fire({
                        icon: "error",
                        title: "Missing Credit Request ID",
                        text: "Credit Request ID is required to update status!",
                    });
                    return;
                }

                // Show SweetAlert confirmation
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to update the Credit Request status?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with AJAX request
                        $.ajax({
                            url: "/credit/update-status",
                            type: "POST",
                            data: {
                                _token: $('input[name="_token"]').val(),
                                credit_id: creditRequestId,
                                request_status: requestStatus
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Updated!",
                                        text: "Credit Request status has been updated successfully.",
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location
                                            .reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Failed!",
                                        text: "Failed to update Credit Request status.",
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error!",
                                    text: "An error occurred while updating status.",
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
