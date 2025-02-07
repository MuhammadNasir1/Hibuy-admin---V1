@extends('Auth.layout')
@section('title', 'Login')
@section('content')
    <div
        class="w-full max-w-sm  p-4 mx-auto mt-5 bg-white shadow-lg rounded-tr-[40px] rounded-tl-[100px]  rounded-br-[100px]  rounded-bl-[20px] lg:px-6 lg:py-20  lg:max-w-lg">
        <h2 class="text-4xl font-medium text-center ">LOG IN</h2>
        <p class="mt-3 text-sm font-medium text-center">Fill in your details below and Sign up</p>
    
       <form action="#">
        <div class="mt-4">
            <x-input id="email" label="Email" placeholder="Enter Email"
            name='email' type="email"></x-input>
        </div>
        <div class="relative mt-6">
            <x-input id="mediaTitle" label="Password" placeholder="Enter Password"
            name='password' type="password"></x-input>
        </div>
        <div class="mt-6">
            <button class="w-full px-4 py-2 font-semibold text-white rounded-md bg-primary" >Log In</button>
        </div>
       </form>
        <div class="flex justify-center mt-6">
            <h1 class="">Forgot Password?<span class="text-[#2C80FF] font-bold ml-2">Reset here</span></h1>
        </div>

    </div>
@endsection
