@extends('Auth.layout')
@section('title', 'KYC')
@section('content')

    <div class="h-[75vh] w-[70vw] mt-10   bg-white rounded-lg p-5  mx-auto">
        <div class="h-[100px] bg-gradient-to-r from-[#4A90E2] rounded-t-xl  via-green-300 to-[#FFCE31]"></div>
        <input type="hidden" name="upload-banner" id="upload-banner">
        <div
            class="h-[100px] w-[100px] bg-primary overflow-hidden rounded-full flex items-center justify-center mx-auto -mt-16">
            <x-file-uploader name="profile_picture" id="profile_picture" />
        </div>

        <p class="text-center">My Store</p>

        <div>
            {{--  --}}

            <div class="flex justify-center mt-5 text-sm">
                <a href="{{ route('ProfileDetail') }}">
                    <div class="px-5">
                        <img src="{{ $statusImages['personal_info'] }}" alt="" class="mx-auto">
                        <p>Personal Info</p>
                    </div>
                </a>
                <a href="{{ route('ProfileDetail') }}">
                    <div class="px-5">
                        <img src="{{ $statusImages['store_info'] }}" alt="" class="mx-auto">
                        <p>My Store</p>
                    </div>
                </a>
                <a href="{{ route('ProfileDetail') }}">
                    <div class="px-5">
                        <img src="{{ $statusImages['documents_info'] }}" alt="" class="mx-auto">
                        <p>Document</p>
                    </div>
                </a>
                <a href="{{ route('ProfileDetail') }}">
                    <div class="px-5">
                        <img src="{{ $statusImages['bank_info'] }}" alt="" class="mx-auto">
                        <p>Account</p>
                    </div>
                </a>
                <a href="{{ route('ProfileDetail') }}">
                    <div class="px-5">
                        <img src="{{ $statusImages['business_info'] }}" alt="" class="mx-auto">
                        <p>Business</p>
                    </div>
                </a>
            </div>


            {{--  --}}

            <div class="  text-center mt-5 relative">
                <img src="{{ asset('asset/kyc-bg.png') }}" alt="" class="mx-auto">
                <div class="absolute top-[15%] text-center w-full">
                    <img src="{{ $imageSrc }}" alt="KYC Status" class="mx-auto">
                    <p class="font-semibold">
                        {{ $kycStatus === 'approved' ? 'KYC Approved' : 'In Review' }}
                    </p>
                    <p class="text-customBlackColor text-sm pt-3">
                        {{ $kycStatus === 'approved' ? 'Now you can list your products.' : 'Fill this form if your business registered.' }}
                    </p>
                </div>
            </div>

            {{--  --}}

            <div class="flex justify-center pt-4 gap-5 text-sm">
                <button class="px-5 py-2 border-2 border-gray-600 text-gray-600  rounded-full">Edit Details</button>
                <a href="{{ $isDisabled ? 'Kyc-profile' : '/' }}" {{ $isDisabled ? 'disabled' : '' }}>
                    <button
                        class="px-5 py-2 border-2 bg-primary text-white border-primary rounded-full
                       {{ $isDisabled ? 'disabled text-gray-600 cursor-not-allowed' : '' }}"
                        {{ $isDisabled ? 'disabled' : '' }}>
                        Start Store
                    </button>
                </a>
            </div>
        </div>
    </div>
@endsection
