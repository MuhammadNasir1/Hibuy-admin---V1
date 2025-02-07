@extends('layout.layout1')
@section('content1')
    <div
        class="w-full max-w-sm mb-4 px-6 py-4 mx-auto mt-5 bg-white shadow-lg rounded-tr-[40px] rounded-tl-[100px]  rounded-br-[100px]  rounded-bl-[20px] lg:px-10 lg:py-8 lg:w-1/2 lg:max-w-md">
        <h2 class="text-3xl font-medium text-center text-customblack ">Sign up</h2>
        <p class="mt-3 text-sm font-medium text-center text-customblack">Fill in your details below</p>
        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-bold text-customblack">First name</label>
                <input
                    class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:border-customblue"
                    placeholder="First name" type="text" />
            </div>
            <div>
                <label class="block mb-2 text-sm font-bold text-customblack">Last name</label>
                <input
                    class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:border-customblue"
                    placeholder="Last name" type="text" />
            </div>
        </div>
        <div class="mt-2">
            <label class="block mb-2 text-sm font-bold text-customblack">Email Or Phone</label>
            <input
                class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:border-customblue"
                placeholder="Email Or Phone" type="mail" />
        </div>
        <div class="relative mt-2">
            <label class="block mb-2 text-sm font-bold text-customblack">Password</label>
            <input
                class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:border-customblue"
                placeholder="password" type="password" id="password" />
            <div class="flex justify-end">
            </div>
            <div class="py-4">
                <button class="w-full py-2 font-normal text-white rounded-md px-7 bg-customblue hover:bg-blue-600"><a
                        href="/">Create account</a>
                </button>
            </div>
            <div class="flex justify-center">
                <h1 class="text-sm text-gray-600">Already Haven An Account?<span
                        class="text-[13px] font-bold text-customblue underline-offset-1 "> Login Now</span></h1>
            </div>
            <div>
                <h1 class="text-[12px] text-center text-gray-600 mt-4">By signing up to HiBuy platform you understand and
                    agree with our <span class="font-semibold text-customblue">Terms of Service and Privacy Policy</span>
                </h1>
            </div>
        </div>
    @endsection
