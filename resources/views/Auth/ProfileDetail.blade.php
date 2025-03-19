@extends('Auth.layout')
@section('title', 'Login')
@section('content')
    <div class="  w-full p-4 md:p-8 mt-5 mb-5 bg-[#000000] bg-opacity-30 text-white rounded-2xl">
        <div class="flex flex-col md:flex-row gap-4">
            <aside
                class="relative bg-white text-[#2C2C2C] w-full md:w-[500px] h-[450px] md:h-auto  rounded-2xl shadow-lg border">
                <div class="p-5 pb-0">
                    <h2 class="text-sm lg:text-base font-bold pb-4">Apply for Profile</h2>

                    <div class="">
                        <!-- Step 1 -->
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('asset/status (1).svg') }}" class="h-10 md:h-full" alt="Step 1">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold">Personal Information</p>
                                <p class="text-gray-400 text-sm">text</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                                <img src="{{ asset('asset/status.svg') }}" class="h-10 md:h-full" alt="Step 2">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold">My Store Information</p>
                                <p class="text-gray-400 text-sm">text</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                                <img src="{{ asset('asset/status.svg') }}"class="h-10 md:h-full" alt="Step 3">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold">Address Verification</p>
                                <p class="text-gray-400 text-sm">text</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                                <img src="{{ asset('asset/status.svg') }}" class="h-10 md:h-full" alt="Step 4">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                            </div>
                            <div>
                                <p class="text-xs lg:text-base font-semibold">Bank Account Verification</p>
                                <p class="text-gray-400 text-sm">text</p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('asset/line.svg') }}" class="" alt="Line">
                                <img src="{{ asset('asset/status.svg') }}" class="h-10 md:h-full" alt="Step 5">
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold">Business Verification</p>
                                <p class="text-gray-400 text-sm">text</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl flex justify-center items-center py-4">
                    <button class="text-blue-600 text-xs  md:text-sm font-semibold bg-[#D9D9D980] rounded-3xl px-6 py-2">
                        Fill All details to activate your Store
                    </button>
                </div>
            </aside>

            {{-- second section --}}
            <div class="w-full bg-white  text-[#4E4646] rounded-2xl">

                <div class="mb-4 border-b ">
                    <ul class="flex flex-wrap justify-around -mb-px text-sm font-medium text-center mt-2 " id="default-tab"
                        data-tabs-toggle="#default-styled-tab-content"
                        data-tabs-active-classes="bg-primary text-white border-primary dark:bg-primary dark:text-white dark:border-primary"
                        data-tabs-inactive-classes="dark:border-transparent text-[#333333] dark:text-gray-400 border-gray-100 dark:border-gray-700 dark:hover:text-gray-300"
                        role="tablist">
                        <li class="me-2" role="presentation">
                            <button class=" px-6 py-2 border-b-2 rounded-t-lg " id="personal-tab"
                                data-tabs-target="#personal" type="button" role="tab" aria-controls="personal"
                                aria-selected="false">Personal</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button class="inline-block px-6 py-2 border-b-2 rounded-t-lg" id="store-tab"
                                data-tabs-target="#store" type="button" role="tab" aria-controls="store"
                                aria-selected="false">My Store</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button class="inline-block px-6 py-2 border-b-2 rounded-t-lg" id="document-tab"
                                data-tabs-target="#document" type="button" role="tab" aria-controls="document"
                                aria-selected="false">Document</button>
                        </li>
                        <li role="presentation">
                            <button class="inline-block px-6 py-2 border-b-2 rounded-t-lg " id="account-tab"
                                data-tabs-target="#account" type="button" role="tab" aria-controls="account"
                                aria-selected="false">Bank Account</button>
                        </li>
                        <li role="presentation">
                            <button class="inline-block px-6 py-2 border-b-2 rounded-t-lg " id="business-tab"
                                data-tabs-target="#business" type="button" role="tab" aria-controls="business"
                                aria-selected="false">Business</button>
                        </li>
                    </ul>
                </div>
                <div id="default-tab-content">
                    {{-- personal --}}
                    <div class="hidden relative p-4 rounded-lg" id="personal" role="tabpanel"
                        aria-labelledby="personal-tab">
                        <div class="text-sm">
                            <h3 class="text-base font-bold text-[#2C2C2C]">Personal Information</h3>

                            <form action="{{ route('KYC_Authentication') }}" id="myFormNew" method="POST">
                                @csrf
                                <input type="hidden" name="step" value="1">
                                <div class="grid grid-cols-1 items-center md:grid-cols-2 gap-6 mt-4">
                                    <div class="flex flex-col gap-6">
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Full Name</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="full_name">
                                        </div>
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Address</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="address">
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden">
                                            <x-file-uploader name="profile_picture" id="profile_picture" />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Phone No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="phone_no">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Email</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="email">
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center  text-sm font-semibold mt-6">
                                    <label class="md:w-32">CNIC</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter Here" name="cnic">
                                </div>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-32 md:mb-20 text-base font-semibold">
                                    <div>
                                        <h3 class="pb-2">Front Image</h3>
                                        <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                            <x-file-uploader name="front_imane" id="front_imane" />
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="pb-2">Back Image</h3>
                                        <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                            <x-file-uploader name="back_image" id="back_image" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Buttons -->
                                <div
                                    class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                                    <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">
                                        Discard
                                    </button>
                                    <button type="submit"
                                        class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-primary text-white">
                                        Next
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- store --}}
                    <div class="hidden relative p-4 rounded-2xl  dark:bg-gray-800" id="store" role="tabpanel"
                        aria-labelledby="store-tab">

                        <div class="text-sm">
                            <h3 class="text-base font-bold text-[#2C2C2C]">Store Information</h3>
                            <form action="{{ route('KYC_Authentication') }}" id="myFormNew" class="myFormNew"
                                method="POST">
                                @csrf
                                <input type="hidden" name="step" value="2">
                                <div class="grid grid-cols-1 items-center md:grid-cols-2 gap-6 mt-4">
                                    <div class="flex flex-col gap-6">
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Store Name</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="store_name">
                                        </div>
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Type</label>
                                            <select name="type" id="type"
                                                class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">
                                                <option class="text-[#B4B4B4]" value="">Option</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden">
                                            <x-file-uploader name="profile_picture_store" id="profile_picture2" />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Phone No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="phone_no">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Email</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="email">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Country</label>
                                        <select name="country" id="country"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">

                                            <option value="">Option</option>
                                        </select>
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Province/ Region</label>
                                        <select name="province" id="province"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here">
                                            <option value="">Option</option>
                                        </select>
                                    </div>

                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">City</label>
                                        <select name="city" id="city"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here">
                                            <option value="">Option</option>
                                        </select>
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">ZipCode</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="0000" name="zip_code">
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center  text-sm font-semibold mt-6 gap-2 md:gap-0">
                                    <label class="md:w-32">Address</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" name="address" placeholder="Enter Here">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-6 mb-32  gap-2 md:gap-0  relative">
                                    <label class="md:w-32">Pin Location</label>
                                    <div class="relative w-full">
                                        <input class="rounded-lg w-full p-2 pr-10 border border-gray-300  text-[#B4B4B4]"
                                            type="text" placeholder="Enter Pin Location" name="pin_location">
                                        <img class="absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5"
                                            src="{{ asset('asset/Location.svg') }}" alt="Location Icon">
                                    </div>
                                </div>


                        </div>

                        <!-- Footer Buttons -->
                        <div
                            class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                            <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">
                                Back
                            </button>
                            <button type="submit"
                                class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-primary text-white">
                                Next
                            </button>
                        </div>
                        </form>
                    </div>

                </div>
                {{-- documen --}}
                <div class="hidden relative p-4 rounded-2xl  dark:bg-gray-800" id="document" role="tabpanel"
                    aria-labelledby="document-tab">
                    <div class="text-sm">
                        <h3 class="text-base font-bold text-[#2C2C2C]">Document Verification</h3>
                        {{-- img section --}}
                        <form action="{{ route('KYC_Authentication') }}" id="myFormNew" class="myFormNew"
                            method="POST">
                            @csrf
                            <input type="hidden" name="step" value="3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4  text-base font-semibold">
                                <div>
                                    <h3 class="pb-2">Shop / Home Bill</h3>
                                    <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                        <x-file-uploader name="home_bill" id="home_bill" />
                                    </div>
                                </div>
                                <div>
                                    <h3 class="pb-2">Shop Video (Optional)</h3>
                                    <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                        <x-file-uploader name="shop_video" id="shop_video" />
                                    </div>
                                </div>
                            </div>
                            {{-- select oprions --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 mb-32 md:mb-20">
                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                    <label class="md:w-32">Country</label>
                                    <select name="country" id="country"
                                        class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">

                                        <option value="">Option</option>
                                    </select>
                                </div>
                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                    <label class="md:w-32">Province/ Region</label>
                                    <select name="province" id="province"
                                        class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]" type="text"
                                        placeholder="Enter Here">
                                        <option value="">Option</option>
                                    </select>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                    <label class="md:w-32">City</label>
                                    <select name="city" id="city"
                                        class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]" type="text"
                                        placeholder="Enter Here">
                                        <option value="">Option</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Footer Buttons -->
                            <div
                                class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                                <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">
                                    Back
                                </button>
                                <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-primary text-white">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- account --}}
                <div class="hidden relative p-4 rounded-lg  dark:bg-gray-800" id="account" role="tabpanel"
                    aria-labelledby="account-tab">
                    <div class="text-sm ">
                        <div class="border-2 p-2 border-[#FFAE4240] rounded-xl">
                            <div class="flex items-center flex-col md:flex-row gap-2">
                                <div><img src="{{ asset('asset/kyc.svg') }}" alt=""></div>
                                <div>
                                    <h3 class="text-sm font-semibold">Reason</h3>
                                    <p>Lorem Ipsum is basically just dummy text that is latin. It's a content filler for
                                        when you don't really have content to put in there yet.</p>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-[#2C2C2C] mt-4">Bank Account Verification</h3>
                        <form action="{{ route('KYC_Authentication') }}" id="myFormNew" class="myFormNew"
                            method="POST">
                            @csrf
                            <input type="hidden" name="step" value="4">
                            <div
                                class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-4 text-sm font-semibold">
                                <label class="md:w-32">Account Type</label>
                                <select name="account_type" id="account_type"
                                    class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">

                                    <option value="">Option</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6 text-sm font-semibold">
                                    <label class="md:w-32">Bank Name</label>
                                    <select name="bank_name" id="bank_name"
                                        class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">

                                        <option value="">Option</option>
                                    </select>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                    <label class="md:w-32">Branch Code</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" id="branch_code" name="branch_code">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                    <label class="md:w-32">Branch Name</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" id="branch_name" name="branch_name">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                    <label class="md:w-32">Branch Phone</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" id="branch_phone" name="branch_phone">
                                </div>

                            </div>

                            <div
                                class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                <label class="md:w-32">Account Title</label>
                                <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]" type="text"
                                    placeholder="Enter here" id="account_title" name="account_title">
                            </div>

                            <div
                                class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                <label class="md:w-32">Account No.</label>
                                <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]" type="text"
                                    placeholder="Enter here" id="account_no" name="account_no">
                            </div>

                            <div
                                class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                <label class="md:w-32">IBAN No.</label>
                                <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]" type="text"
                                    placeholder="Enter here" id="iban_no" name="iban_no">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-32 md:mb-20 text-base font-semibold">
                                <div>
                                    <h3 class="pb-2">Canceled Cheque</h3>
                                    <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                        <x-file-uploader name="canceled_cheque" id="canceled_cheque" />
                                    </div>
                                </div>
                                <div>
                                    <h3 class="pb-2">Verification Letter (Optional)</h3>
                                    <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                        <x-file-uploader name="verification_letter" id="verification_letter" />
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Buttons -->
                            <div
                                class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                                <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">
                                    Back
                                </button>
                                <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-primary text-white">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- business --}}
                <div class="hidden relative p-4 rounded-lg  dark:bg-gray-800" id="business" role="tabpanel"
                    aria-labelledby="business-tab">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <div>
                            <h3 class="text-base font-bold text-[#2C2C2C]">Personal Information</h3>
                            <p>Fill this form if your business registered.</p>
                        </div>
                        <form action="{{ route('KYC_Authentication') }}" id="myFormNew" class="myFormNew"
                            method="POST">
                            @csrf
                            <input type="hidden" name="step" value="5">
                            <div class="grid grid-cols-1  md:grid-cols-2 gap-6 mt-4">
                                <div class="flex flex-col gap-6">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Business Name</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="business_name"
                                            id="business_name">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Owner Name</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="owner_name" id="owner_name">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Phone No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="phone_no" id="phone_no">
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden">
                                        <x-file-uploader name="personal_profile" id="personal_profile" />
                                    </div>
                                </div>
                            </div>

                            <div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                    <label class="md:w-32">Reg. No.</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" name="reg_no" id="reg_no">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                    <label class="md:w-32">Tax. No.</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" name="tax_no" id="tax_no">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                    <label class="md:w-32">Address</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" name="address" id="address">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-6 gap-2 md:gap-0  relative">
                                    <label class="md:w-32">Pin Location</label>
                                    <div class="relative w-full">
                                        <input class="rounded-lg w-full p-2 pr-10 border border-gray-300  text-[#B4B4B4]"
                                            type="text" placeholder="Enter Pin Location" name="pin_location"
                                            id="pin_location">
                                        <img class="absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5"
                                            src="{{ asset('asset/Location.svg') }}" alt="Location Icon">
                                    </div>
                                </div>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-32 md:mb-20 text-base font-semibold">
                                    <div>
                                        <h3 class="pb-2">Letter Head</h3>
                                        <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                            <x-file-uploader name="letter_head" id="letter_head" />
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="pb-2">Stamp</h3>
                                        <div class="w-full rounded-lg border border-gray-300 h-[30vh]">
                                            <x-file-uploader name="stamp" id="stamp" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Buttons -->
                            <div
                                class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                                <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">
                                    Back
                                </button>
                                <button class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-primary text-white">
                                    Submit
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    </div>
@endsection
