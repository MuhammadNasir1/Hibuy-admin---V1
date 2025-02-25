@extends('layout')
@section('title', 'Credit Request')
@section('nav-title', 'Credit Request')
@section('content')

    <div class="w-full py-4 rounded-lg custom-shadow">
        <div class="flex flex-col items-center text-center mb-20">
            <h3 class="text-xl font-bold text-customBlack mb-2">Total Credit Distributed (Rs)</h3>
            <h3 class="text-2xl font-bold text-customBlack">100000.00</h3>
        </div>
        <div class="flex justify-center px-5 text-center my-5">
            <div class="flex w-1/3 flex-col items-center border-r">
                <div class="text-xl font-bold text-customBlack mb-2">Pending Requests</div>
                <div class="text-xl font-bold text-customBlack">20</div>
            </div>
            <div class="flex w-1/3 flex-col items-center border-r border-l">
                <div class="text-xl font-bold text-customBlack mb-2">Approved Requests</div>
                <div class="text-xl font-bold text-customBlack">20</div>
            </div>
            <div class="flex w-1/3 flex-col items-center border-l">
                <div class="text-xl font-bold text-customBlack mb-2">Rejected Requests</div>
                <div class="text-xl font-bold text-customBlack">20</div>
            </div>
        </div>
    </div>
    <div class="w-full mt-3 pt-10 min-h-[86vh] rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium ">Credit Request History</h2>
        </div>
        @php
            $headers = ['Sr.', 'Freelancers Name', 'Requested Amount', 'Reason', 'Joined Date', 'Status', 'Action'];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">

                <tr>
                    <td>1</td>
                    <td>Noman Ahmad</td>
                    <td>Rs 5000</td>
                    <td>Reason</td>
                    <td>Jan 2, 2024</td>
                    <td><span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Approved</span></td>
                    <td>
                        <span class='flex gap-4'>
                            <button id="addmanagesellerBtn" data-modal-target="returnorders-modal" data-modal-toggle="returnorders-modal">
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
                                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_635_21720"
                                                result="shape" />
                                        </filter>
                                    </defs>
                                </svg>
                            </button>
                        </span>
                    </td>
                </tr>
            </x-slot>
        </x-table>

        <x-modal id="creditrequest-modal">
            <x-slot name="title">Details</x-slot>
            <x-slot name="modal_width">max-w-4xl</x-slot>
            <x-slot name="body">
                <form>
                    @csrf
                    <div class="md:py-5">
                        {{-- Product Category Form --}}
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Status</label>
                                <select id="type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
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
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-1/2">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Amount</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $amount ?? 'PKR500' }}
                                </p>
                            </div>
                            <div class="w-1/2">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Subject</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $subject ?? 'John Eliya' }}
                                </p>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="flex gap-6 px-6 mt-5">
                            <div class="w-full">
                                <label
                                    class="block mb-2 text-sm font-medium dark:text-white text-customBlack">Reason</label>
                                <p
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    Got it! Here's the updated form where Date, Email, Subject, and Message are displayed
                                    inside
                                    tags instead of input fields. The Response field remains as a textarea for user input.
                                </p>
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
