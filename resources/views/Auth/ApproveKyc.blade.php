@extends('Auth.layout')
@section('title', 'KYC')
@section('content')

    <div class="w-[90vw] max-w-4xl mt-10 bg-white rounded-lg p-5 mx-auto">
        <div class="h-[100px] bg-gradient-to-r from-[#4A90E2] rounded-t-xl via-green-300 to-[#FFCE31]"></div>
        <input type="hidden" name="upload-banner" id="upload-banner">
        <div
            class="h-[100px] w-[100px] bg-primary overflow-hidden rounded-full flex items-center justify-center mx-auto -mt-16">
            <x-file-uploader name="profile_picture" id="profile_picture" />
        </div>

        <p class="text-center text-lg font-semibold mt-2">My Store</p>

        <div class="flex flex-wrap justify-center mt-5 text-sm gap-4 sm:gap-8">
            <a href="{{ route('ProfileDetail') }}" class="text-center">
                <img src="{{ $statusImages['personal_info'] }}" alt=""
                    class="mx-auto h-10 w-10 sm:h-auto sm:w-auto">
                <p class="mt-1">Personal Info</p>
            </a>
            <a href="{{ route('ProfileDetail') }}" class="text-center">
                <img src="{{ $statusImages['store_info'] }}" alt="" class="mx-auto h-10 w-10 sm:h-auto sm:w-auto">
                <p class="mt-1">My Store</p>
            </a>
            <a href="{{ route('ProfileDetail') }}" class="text-center">
                <img src="{{ $statusImages['documents_info'] }}" alt=""
                    class="mx-auto h-10 w-10 sm:h-auto sm:w-auto">
                <p class="mt-1">Document</p>
            </a>
            <a href="{{ route('ProfileDetail') }}" class="text-center">
                <img src="{{ $statusImages['bank_info'] }}" alt="" class="mx-auto h-10 w-10 sm:h-auto sm:w-auto">
                <p class="mt-1">Account</p>
            </a>
            <a href="{{ route('ProfileDetail') }}" class="text-center">
                <img src="{{ $statusImages['business_info'] }}" alt=""
                    class="mx-auto h-10 w-10 sm:h-auto sm:w-auto">
                <p class="mt-1">Business</p>
            </a>
        </div>

        <div class="relative text-center mt-6">
            <img src="{{ asset('asset/kyc-bg.png') }}" alt="" class="mx-auto w-full max-w-md">
            <div class="absolute top-[15%] left-1/2 transform -translate-x-1/2 text-center w-full px-4">
                <img src="{{ $imageSrc }}" alt="KYC Status" class="mx-auto h-16">
                <p class="font-semibold mt-2 text-base">
                    {{ $kycStatus === 'approved' ? 'KYC Approved' : 'In Review' }}
                </p>
                <p class="text-customBlackColor text-sm pt-2">
                    {{ $kycStatus === 'approved' ? 'Now you can list your products.' : 'Fill this form if your business registered.' }}
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center pt-11 sm:pt-6 gap-4 text-sm mt-6 text-center">
            {{-- Edit Details Button --}}
            <a href="{{ route('ProfileDetail') }}" class="px-6 py-2 border border-gray-600 text-gray-700 rounded-full hover:bg-gray-100 transition">
                Edit Details
            </a>
            {{-- Start Store Button --}}
            <a href="{{ $isDisabled ? 'Kyc-profile' : '/' }}"
                class="px-6 py-2 rounded-full border transition
        {{ $isDisabled
            ? 'bg-gray-200 text-gray-500 border-gray-300 cursor-not-allowed'
            : 'bg-primary text-white border-primary hover:bg-blue-600' }}"
                {{ $isDisabled ? 'disabled' : '' }}>
                Start Store
            </a>

            {{-- Logout Button --}}
            <a href="{{ route('logout') }}"
                class="px-6 py-2 bg-red-500 text-white border border-red-500 rounded-full hover:bg-red-600 transition">
                Logout
            </a>
        </div>

    </div>

@endsection
