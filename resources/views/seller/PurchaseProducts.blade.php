@extends('layout')
@section('title', 'Purchases')
@section('nav-title', 'Purchases Products')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Purchases List</h1>
        </div>
        @php
            $headers = ['ID', 'Image', 'Title', 'Category', 'Price', 'Available Stock', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                <tr>
                    <td>1</td>
                    <td>
                        <img class="rounded-full w-11 h-11" src="{{ asset('asset/Ellipse 2.png') }}" alt="Jese image">
                    </td>
                    <td>Purchases title</td>
                    <td>Category One</td>
                    <td>PKR500</td>
                    <td>225</td>
                    <td>
                        <span class='flex gap-4'>
                            <button class="viewModalBtn" data-modal-target="purchases-modal"
                                data-modal-toggle="purchases-modal">
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
            </x-slot>
        </x-table>


        <x-modal id="purchases-modal">
            <x-slot name="title">Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form>
                    @csrf
                    <div class="md:py-5">
                        {{-- Product Category Form --}}
                        <div class="flex gap-6 px-6 mt-5">
                            <!-- Status -->
                            <div class="w-1/2">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Status</label>
                                <select id="type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Select Status</option>
                                    <option value="new">New</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Date</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $date ?? '24 Jan, 2024' }}
                                </p>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Email</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $email ?? 'email@gmail.com' }}
                                </p>
                            </div>
                            <div class="w-1/2">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Subject</label>
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
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Message</label>
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
                            <button type="submit" class="px-6 py-2 text-white bg-primary rounded-3xl">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </div>
@endsection
