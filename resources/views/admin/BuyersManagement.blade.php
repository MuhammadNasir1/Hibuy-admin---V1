@extends('layout')
@section('nav-title', 'Buyers Management')
@section('title', 'Buyers Management')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]   rounded-lg custom-shadow">
        <div class="flex justify-between px-5">
            <h2 class="text-2xl font-medium  ">Buyers List</h1>
        </div>
        @php
            $headers = [
                'Sr.',
                'Buyer Id',
                'Buyer name',
                'Phone Number',
                'Joined Date',
                'Orders',
                'Total Spend',
                'Status',
                'Action',
            ];
        @endphp

        <x-table :headers="$headers">
            <x-slot name="tablebody">
                @foreach ($buyers as $buyer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $buyer['user_id'] }}</td>
                        <td class="capitalize">{{ $buyer['user']['user_name'] }}</td>
                        <td>{{ $buyer['customer_phone'] }}</td>
                        <td>{{ $buyer['user']['joined_date'] }}</td>
                        <td>{{ $buyer['order_count'] }}</td>
                        <td>Rs {{ number_format($buyer['total_spend'], 2) }}</td>
                        <td>
                            <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">
                                {{ $buyer['user']['user_status'] == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <span class='flex gap-4'>
                                <a id="addmanagesellerBtn" class="viewModalBtn"
                                    href="{{ route('BuyerProfile', base64_encode($buyer['user_id'])) }}">
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
    </div>
@endsection
