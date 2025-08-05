@extends('layout')
@section('title', 'Rider Management')
@section('nav-title', 'Rider Management')

@section('content')
    <div class="px-5 w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Rider List</h1>
                <button id="addModalBtn" data-modal-target="notification-modal" data-modal-toggle="notification-modal"
                    class="px-5 py-3 font-semibold text-white rounded-full bg-primary">
                    Add Rider
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
                            <span class="flex gap-2">
                                <form action="{{ url('/deleteRider/' . $data->id) }}" method="post">
                                @csrf
                                @method('delete')
                                 <button>
                                     <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                        xmlns='http://www.w3.org/2000/svg'>
                                        <circle opacity='0.1' cx='18' cy='18' r='18' fill='#DF6F79' />
                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                            d='M23.4905 13.7423C23.7356 13.7423 23.9396 13.9458 23.9396 14.2047V14.4441C23.9396 14.6967 23.7356 14.9065 23.4905 14.9065H13.0493C12.8036 14.9065 12.5996 14.6967 12.5996 14.4441V14.2047C12.5996 13.9458 12.8036 13.7423 13.0493 13.7423H14.8862C15.2594 13.7423 15.5841 13.4771 15.6681 13.1028L15.7642 12.6732C15.9137 12.0879 16.4058 11.6992 16.9688 11.6992H19.5704C20.1273 11.6992 20.6249 12.0879 20.7688 12.6423L20.8718 13.1022C20.9551 13.4771 21.2798 13.7423 21.6536 13.7423H23.4905ZM22.557 22.4932C22.7487 20.7059 23.0845 16.4598 23.0845 16.4169C23.0968 16.2871 23.0545 16.1643 22.9705 16.0654C22.8805 15.9728 22.7665 15.918 22.6409 15.918H13.9025C13.7762 15.918 13.6562 15.9728 13.5728 16.0654C13.4883 16.1643 13.4466 16.2871 13.4527 16.4169C13.4539 16.4248 13.4659 16.5744 13.4861 16.8244C13.5755 17.9353 13.8248 21.0292 13.9858 22.4932C14.0998 23.5718 14.8074 24.2496 15.8325 24.2742C16.6235 24.2925 17.4384 24.2988 18.2717 24.2988C19.0566 24.2988 19.8537 24.2925 20.6692 24.2742C21.7298 24.2559 22.4369 23.59 22.557 22.4932Z'
                                            fill='#D11A2A' />
                                    </svg>
                                 </button>
                                </form>

                                {{-- Edit Button --}}
                                <button type="button"
                                     class="editRiderBtn"
                                     data-id="{{ $data->id }}"
                                     data-modal-target="edit-rider-modal"
                                    data-modal-toggle="edit-rider-modal">
                                    <svg width='36' height='36' viewBox='0 0 36 36' fill='none'
                                                            xmlns='http://www.w3.org/2000/svg'>
                                                            <circle opacity='0.1' cx='18' cy='18' r='18' fill='#233A85' />
                                                            <path fill-rule='evenodd' clip-rule='evenodd'
                                                                d='M16.1637 23.6188L22.3141 15.665C22.6484 15.2361 22.7673 14.7402 22.6558 14.2353C22.5593 13.7763 22.277 13.3399 21.8536 13.0088L20.8211 12.1886C19.9223 11.4737 18.8081 11.549 18.1693 12.3692L17.4784 13.2654C17.3893 13.3775 17.4116 13.543 17.523 13.6333C17.523 13.6333 19.2686 15.0329 19.3058 15.063C19.4246 15.1759 19.5137 15.3264 19.536 15.507C19.5732 15.8607 19.328 16.1918 18.9641 16.2369C18.7932 16.2595 18.6298 16.2068 18.511 16.109L16.6762 14.6492C16.5871 14.5822 16.4534 14.5965 16.3791 14.6868L12.0188 20.3304C11.7365 20.6841 11.64 21.1431 11.7365 21.5871L12.2936 24.0025C12.3233 24.1304 12.4348 24.2207 12.5685 24.2207L15.0197 24.1906C15.4654 24.1831 15.8814 23.9799 16.1637 23.6188ZM19.5958 22.8672H23.5929C23.9829 22.8672 24.3 23.1885 24.3 23.5835C24.3 23.9794 23.9829 24.2999 23.5929 24.2999H19.5958C19.2059 24.2999 18.8887 23.9794 18.8887 23.5835C18.8887 23.1885 19.2059 22.8672 19.5958 22.8672Z'
                                                                fill='#233A85' />
                                                        </svg>
                                    </button>


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
                            <x-input type="text" label="Name" placeholder="Name Here" id="notification_title"
                                name="rider_name" value="" requred />
                        </div>
                        <div class="px-6 mt-5">
                            <x-input type="text" label="Email" placeholder="Email Here" id="notification_title"
                                name="rider_email" value="" required/>
                        </div>
                        <div class="px-6 mt-5">
                            <x-input type="text" label="Phone" placeholder="Phone Here" id="notification_title"
                                name="phone" value="" required/>
                        </div>
                        <div class="px-6 mt-5">
                            <label for="vehicle_type" class="block text-gray-700 font-medium text-sm mb-2">Vehicle Type</label>
                            <select id="vehicle_type" name="vehicle_type"
                                class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring focus:border-blue-300">
                                <option value="">Select Vehicle Type</option>
                                <option value="Bike">Bike</option>
                                <option value="Car">Car</option>
                                <option value="Van">Van</option>
                                <option value="Truck">Truck</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>

                        <div class="px-6 mt-5">
                            <x-input type="text" label="Vehicle Number" placeholder="Vehicle Number Here" id="notification_title"
                                name="vehicle_number" value="" required/>
                        </div>
                        <div class="px-6 mt-5">
                            {{-- <label class="block text-gray-700  font-medium text-sm mb-2 text-center">Title</label> --}}
                            <x-input type="text" label="City" placeholder="City Here" id="notification_title"
                                name="city" value="" />
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
            <x-slot name="title">Rider Detail</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <div class="flex flex-wrap px-2 gap-3 my-4 justify-center items-center">
                    <div class="w-1/3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Name</label>
                        <p id="modal_rider_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="w-1/3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Email</label>
                        <p id="modal_rider_email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                </div>
                    <div class="w-1/3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Phone</label>
                        <p id="modal_phone"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="w-1/3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Vehicle Type</label>
                        <p id="modal_vehicle_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="w-1/3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Vehicle Number</label>
                        <p id="modal_vehicle_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                    <div class="w-1/3">
                        <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">City</label>
                        <p id="modal_city"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </p>
                    </div>
                </div>

            </x-slot>
        </x-modal>

        {{-- Modal --}}
<x-modal id="edit-rider-modal">
    <x-slot name="title">Edit Rider</x-slot>
    <x-slot name="modal_width">max-w-3xl</x-slot>
    <x-slot name="body">
        <form id="editRiderForm" method="POST" action="#">
            @csrf
            @method('PUT')
           <div class="grid grid-cols-12 px-4 gap-3 my-4">
                <input type="hidden" id="edit_rider_id" name="id">

                <div class="mb-4 col-span-4 ">
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" id="edit_rider_name" name="rider_name" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4 col-span-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" id="edit_rider_email" name="rider_email" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4 col-span-4">
                    <label class="block text-sm font-medium">Phone</label>
                    <input type="text" id="edit_phone" name="phone" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4 col-span-4">
                    <label class="block text-sm font-medium">Vehicle Type</label>
                    <input type="text" id="edit_vehicle_type" name="vehicle_type" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4 col-span-4">
                    <label class="block text-sm font-medium">Vehicle Number</label>
                    <input type="text" id="edit_vehicle_number" name="vehicle_number" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4 col-span-4">
                    <label class="block text-sm font-medium">City</label>
                    <input type="text" id="edit_city" name="city" class="w-full p-2 border rounded">
                </div>
           </div>
           <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
        </form>
    </x-slot>
</x-modal>

@endsection


@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-rider-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // stop normal form submission

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to delete this rider.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // submit if confirmed
                    }
                });
            });
        });

        // Show success alert after redirect if session has success message
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: '{{ session('success') }}',
            });
        @endif

        @if ($errors->has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ $errors->first('error') }}'
            });
        @endif
    });
</script>


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

                fetch(`/rider/${riderId}`)
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Open modal and populate form
    document.querySelectorAll('.editRiderBtn').forEach(button => {
        button.addEventListener('click', function () {
            const riderId = this.getAttribute('data-id');

            fetch(`/rider/${riderId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Failed to load rider data');
                    return res.json();
                })
                .then(data => {
                    const rider = data.rider;

                    document.getElementById('edit_rider_id').value = rider.id;
                    document.getElementById('edit_rider_name').value = rider.rider_name;
                    document.getElementById('edit_rider_email').value = rider.rider_email;
                    document.getElementById('edit_phone').value = rider.phone ?? '';
                    document.getElementById('edit_vehicle_type').value = rider.vehicle_type ?? '';
                    document.getElementById('edit_vehicle_number').value = rider.vehicle_number ?? '';
                    document.getElementById('edit_city').value = rider.city ?? '';
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to load rider info.');
                });
        });
    });

    // Submit form via AJAX
   const editForm = document.getElementById('editRiderForm');
editForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const riderId = document.getElementById('edit_rider_id').value;
    const formData = new FormData(editForm);
    formData.append('_method', 'PUT'); // 👈 Laravel expects PUT

    fetch(`/updateRider/${riderId}`, {
        method: 'POST', // spoofing PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                confirmButtonColor: '#3085d6'
            }).then(() => {
                location.reload(); // or close modal
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'Something went wrong.'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: err?.error || 'Error updating rider.'
        });
    });
});
    });
</script>

@endsection

