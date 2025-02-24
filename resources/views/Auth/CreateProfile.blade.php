@extends('Auth.layout')
@section('title', 'Create Profile')
@section('content')
    <div class="w-full p-4 mt-5 flex-column justify-center items-center bg-white min-h-[80vh] pb-10 rounded-lg">
        <div>
            <button class="px-5 py-1 text-black border border-gray-500 rounded-full ">Back</button>
        </div>
        <div class="mt-3">
            <h1 class="text-lg font-bold text-center md:text-3xl text-primary ">Welcome to Create Profile</h1>
        </div>
        <h2 class="text-sm font-bold text-center md:text-lg ">Let’s Join Us with Easy 5 Steps</h2>
        <div class="flex flex-wrap justify-center gap-4 mt-4 ">
            <div class="flex flex-col items-center ">
                <div class="flex justify-center w-16 h-16 p-4 border-2 border-gray-300 rounded-full">
                    <img src="{{ asset('asset/documents.svg') }}" alt="">
                </div>
                <h1 class="text-[12px] text-center">Personal Info</h1>
            </div>

            <div class="flex flex-col items-center ">
                <div class="flex justify-center w-16 h-16 p-4 border-2 border-gray-300 rounded-full">
                    <img src="{{ asset('asset/documents.svg') }}" alt="">
                </div>
                <h1 class="text-[12px] text-center">My Store</h1>
            </div>

            <div class="flex flex-col items-center ">
                <div class="flex justify-center w-16 h-16 p-4 border-2 border-gray-300 rounded-full">
                    <img src="{{ asset('asset/documents.svg') }}" alt="">
                </div>
                <h1 class="text-[12px] text-center">Document</h1>
            </div>

            <div class="flex flex-col items-center ">
                <div class="flex justify-center w-16 h-16 p-4 border-2 border-gray-300 rounded-full">
                    <img src="{{ asset('asset/documents.svg') }}" alt="">
                </div>
                <h1 class="text-[12px] text-center">Account</h1>
            </div>

            <div class="flex flex-col items-center ">
                <div class="flex justify-center w-16 h-16 p-4 border-2 border-gray-300 rounded-full">
                    <img src="{{ asset('asset/documents.svg') }}" alt="">
                </div>
                <h1 class="text-[12px] text-center">Business</h1>
            </div>
        </div>
        <div class="flex justify-center">
            <img src="{{ asset("asset/dextopdiagram3.svg
                        ") }}" alt="">
        </div>
        <div class="flex justify-center mt-5 ">
            <button class="px-5 py-2 text-white rounded-full bg-primary"> <a href="{{ route("ProfileDetail") }}">Create my Account</a></button>
        </div>
    </div>
@endsection
