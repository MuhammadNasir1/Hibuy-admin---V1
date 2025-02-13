@extends('layout')
@section('title', 'Add Product')
@section('nav-title', 'Add Product')
@section('content')
    <div class="w-full pt-10 min-h-[86vh] px-5  rounded-lg custom-shadow">
            <h3 class="text-[20px] font-medium">Add Product</h3>
            <h4 class="text-[16px] pt-3 font-medium">Images</h4>

            <div class="flex gap-5 pt-5">
                <div class="h-[85px] w-[70px] border rounded-md flex-column  justify-center">
                    <svg class="h-[65%] fill-primary m-auto pt-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 288c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128z"/></svg>
                    <p class="text-[12px] text-center pt-1">File.png</p>
                </div>
                <div class="h-[85px] w-[70px] border rounded-md flex-column  justify-center">
                    <svg class="h-[65%] fill-primary m-auto pt-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 288c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128z"/></svg>
                    <p class="text-[12px] text-center pt-1">File.png</p>
                </div>
                <div class="h-[85px] w-[70px] border rounded-md flex-column  justify-center">
                    <svg class="h-[65%] fill-primary m-auto pt-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 288c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128z"/></svg>
                    <p class="text-[12px] text-center pt-1">File.png</p>
                </div>
            </div>
    </div>
@endsection
