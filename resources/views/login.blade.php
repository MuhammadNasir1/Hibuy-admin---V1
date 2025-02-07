@extends('layout.layout1')
@section('content1')

<div
class="w-full max-w-sm px-6 py-4 mx-auto mt-5 bg-white shadow-lg rounded-tr-[40px] rounded-tl-[100px]  rounded-br-[100px]  rounded-bl-[20px] lg:px-10 lg:py-8 lg:w-1/2 lg:max-w-md">
<h2 class="text-4xl font-medium text-center ">LOG IN</h2>
<p class="mt-3 text-sm font-medium text-center">Fill in your details below and Sign up</p>
<div class="mt-4">
    <label class="block mb-2 text-sm font-bold text-black">Email:</label>
    <input
        class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded  focus:outline-none focus:border-[#2C80FF]"
        placeholder="Email Or Phone" type="email" />
</div>
<div class="relative mt-4">
    <label class="block mb-2 text-sm font-bold text-black">Password</label>
    <input
        class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded  focus:outline-none focus:border-[#2C80FF]"
        placeholder="password" type="password" id="password" />
    <span class="absolute inset-y-0 flex items-center cursor-pointer right-4 bottom-2"
        id="toggle-password">
        <i class="text-gray-400 fa-solid fa-eye-slash" id="toggle-icon"></i>
    </span>
    <div class="flex justify-end">
        <a href="forgetpage" class="mt-4 text-xs text-gray-500">Forget Password?</a>
    </div>
</div>
<div class="p-5 mx-4">
    <button class="w-full px-4 py-2 font-bold text-white rounded-md bg-[#2C80FF] hover:bg-blue-600"><a
            href="/">Login</a>
    </button>
</div>
<div class="flex justify-center">
    <h1 class="">Forgot Password?<span class="text-[#2C80FF] font-bold">Reset here</span></h1>
</div>

</div>
@endsection
