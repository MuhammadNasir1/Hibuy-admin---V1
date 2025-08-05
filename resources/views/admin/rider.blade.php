@extends('layout')
@section('title', 'Rider Management')
@section('nav-title', 'Rider Management')

@section('content')
    <div class="px-5 w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Rider List</h1>
                <button id="addModalBtn" data-modal-target="notification-modal" data-modal-toggle="notification-modal"
                    class="px-5 py-3 font-semibold text-white rounded-full bg-primary">
                    Send New
                </button>

        </div>
        @php
            $headers = ['Sr.', 'Rider name', 'Rider Email', 'Phone', 'Vehicle Type', 'Vehicle Number', 'City', 'Action']
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @if ($riders->count() > 0)
                    @foreach ($riders as $key => $data)
                    <tr style="text-transform: capitalize">
                        <td>{{ $key + 1 }}</td>
                        <td>{{$data->rider_name}}</td>
                        <td>{{$data->rider_email}}</td>
                        <td>{{$data->phone}}</td>
                        <td>{{$data->vehicle_type}}</td>
                        <td>{{$data->vehicle_number}}</td>
                        <td>{{$data->city}}</td>
                        <td>
                            <span>
                                 <button
                                    data-id="{{ $data->id }}"
                                    class="viewModalBtn"
                                    data-modal-target="view-notification-modal"
                                    data-modal-toggle="view-notification-modal">

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
                            {{-- <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">{{ $data->notification_status }}</span> --}}
                        </td>

                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center py-4">No data found.</td>
                    </tr>
                @endif

            </x-slot>
        </x-table>


        <x-modal id="notification-modal">
            <x-slot name="title">Rider Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form class="submitForm" action="{{route('rider.create')}}" method="post">
                    @csrf
                    <div class="md:py-5">
                        {{-- Product Category Form --}}
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="Name" placeholder="Name Here" id="notification_title"
                                name="rider_name" value="" />
                        </div>
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="Email" placeholder="Email Here" id="notification_title"
                                name="rider_email" value="" />
                        </div>
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="Phone" placeholder="Phone Here" id="notification_title"
                                name="phone" value="" />
                        </div>
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="Vehicle Type" placeholder="Vehicle Type Here" id="notification_title"
                                name="vehicle_type" value="" />
                        </div>
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="Vehicle Number" placeholder="Vehicle Number Here" id="notification_title"
                                name="vehicle_number" value="" />
                        </div>
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="City" placeholder="City Here" id="notification_title"
                                name="city" value="" />
                        </div>
                          @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
            <x-slot name="title">Rider Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="gird grid-cols-12">
                    <div class="col-span-3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Name</label>
                        <p id="modal_rider_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Email</label>
                        <p id="modal_rider_email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                </div>
                    <div class="col-span-3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Phone</label>
                        <p id="modal_phone"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Vehicle Type</label>
                        <p id="modal_vehicle_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Vehicle Number</label>
                        <p id="modal_vehicle_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">City</label>
                        <p id="modal_city"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                </div>

            </x-slot>
        </x-modal>


    </div>


@endsection


@section('js')
<script>
    document.querySelector('.submitForm')?.addEventListener('submit', function () {
        console.log('Form submitted');
    });
</script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.viewModalBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const riderId = this.getAttribute('data-id');

                fetch(`/viewRider/${riderId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.rider) {
                            document.getElementById('modal_rider_name').innerText = data.rider.rider_name;
                            document.getElementById('modal_rider_email').innerText = data.rider.rider_email;
                            document.getElementById('modal_phone').innerText = data.rider.phone;
                            document.getElementById('modal_vehicle_type').innerText = data.rider.vehicle_type;
                            document.getElementById('modal_vehicle_number').innerText = data.rider.vehicle_number
                            document.getElementById('modal_city').innerText = data.rider.city;
                        }
                    }).catch(err => {
                        // alert("Error loading rider details");
                    });
            });
        });
    });
</script>
@endsection

