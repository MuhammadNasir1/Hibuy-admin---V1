@extends('Auth.layout')
@section('title', 'Login')
@section('content')
    @php
        // print_r($seller);
        $personal_info = json_decode($seller->personal_info, true);
        $store_info = json_decode($seller->store_info, true);
        $bank_info = json_decode($seller->bank_info, true);
        $documents_info = json_decode($seller->documents_info, true);
        $business_info = json_decode($seller->business_info, true);
        // print_r($personal_info);
        if (empty($personal_info)) {
            $activeTab = 'personal';
        } elseif (empty($store_info)) {
            $activeTab = 'store';
        } elseif (empty($documents_info)) {
            $activeTab = 'document';
        } elseif (empty($bank_info)) {
            $activeTab = 'account';
        } elseif (empty($business_info)) {
            $activeTab = 'business';
        } else {
            // If all tabs have data, default to the first tab
            $activeTab = 'personal';
        }

        $tabsStatus = [
            'personal' => true,
            'store' => !empty($personal_info),
            'document' => !empty($personal_info) && !empty($store_info),
            'account' => !empty($personal_info) && !empty($store_info) && !empty($documents_info),
            'business' =>
                !empty($personal_info) && !empty($store_info) && !empty($documents_info) && !empty($bank_info),
        ];
    @endphp
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

                    <ul class="flex flex-wrap justify-around -mb-px text-sm font-medium text-center mt-2" id="default-tab"
                        data-tabs-toggle="#default-styled-tab-content"
                        data-tabs-active-classes="bg-primary text-white border-primary dark:bg-primary dark:text-white dark:border-primary"
                        data-tabs-inactive-classes="dark:border-transparent text-[#333333] dark:text-gray-400 border-gray-100 dark:border-gray-700 dark:hover:text-gray-300"
                        role="tablist">

                        <li class="me-2" role="presentation">
                            <button
                                class="px-6 py-2 border-b-2 tab-pane rounded-t-lg {{ $activeTab == 'personal' ? 'bg-primary text-white border-primary' : '' }}"
                                id="personal-tab" data-tabs-target="#personal" type="button" role="tab"
                                aria-controls="personal"
                                aria-selected="{{ $activeTab == 'personal' ? 'true' : 'false' }}">Personal</button>
                        </li>

                        <li class="me-2" role="presentation">
                            <button
                                class="inline-block px-6 py-2 border-b-2 rounded-t-lg {{ $activeTab == 'store' ? 'bg-primary text-white border-primary' : '' }}"
                                id="store-tab" data-tabs-target="#store" type="button" role="tab" aria-controls="store"
                                aria-selected="{{ $activeTab == 'store' ? 'true' : 'false' }}"
                                {{ !$tabsStatus['store'] ? 'disabled class="opacity-50 cursor-not-allowed"' : '' }}>My
                                Store</button>
                        </li>

                        <li class="me-2" role="presentation">
                            <button
                                class="inline-block px-6 py-2 tab-pane border-b-2 rounded-t-lg {{ $activeTab == 'document' ? 'bg-primary text-white border-primary' : '' }}"
                                id="document-tab" data-tabs-target="#document" type="button" role="tab"
                                aria-controls="document" aria-selected="{{ $activeTab == 'document' ? 'true' : 'false' }}"
                                {{ !$tabsStatus['document'] ? 'disabled class="opacity-50 cursor-not-allowed"' : '' }}>Document</button>
                        </li>

                        <li role="presentation">
                            <button
                                class="inline-block px-6 py-2 tab-pane border-b-2 rounded-t-lg {{ $activeTab == 'account' ? 'bg-primary text-white border-primary' : '' }}"
                                id="account-tab" data-tabs-target="#account" type="button" role="tab"
                                aria-controls="account" aria-selected="{{ $activeTab == 'account' ? 'true' : 'false' }}"
                                {{ !$tabsStatus['account'] ? 'disabled class="opacity-50 cursor-not-allowed"' : '' }}>Bank
                                Account</button>
                        </li>

                        <li role="presentation">
                            <button
                                class="inline-block px-6 py-2 tab-pane border-b-2 rounded-t-lg {{ $activeTab == 'business' ? 'bg-primary text-white border-primary' : '' }}"
                                id="business-tab" data-tabs-target="#business" type="button" role="tab"
                                aria-controls="business"
                                aria-selected="{{ $activeTab == 'business' ? 'true' : 'false' }}"
                                {{ !$tabsStatus['business'] ? 'disabled class="opacity-50 cursor-not-allowed"' : '' }}>Business</button>
                        </li>

                    </ul>
                </div>
                <div id="default-tab-content">
                    {{-- personal --}}
                    <div class="hidden relative p-4 rounded-lg" id="personal" role="tabpanel"
                        aria-labelledby="personal-tab">
                        <div class="text-sm">
                            <h3 class="text-base font-bold text-[#2C2C2C]">Personal Information</h3>

                            <form action="{{ route('KYC_Authentication') }}" id="myFormNew" class="myFormNew"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="step" value="1">
                                <div class="grid grid-cols-1 items-center md:grid-cols-2 gap-6 mt-4">
                                    <div class="flex flex-col gap-6">
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Full Name</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="full_name"
                                                value="{{ $personal_info['full_name'] ?? '' }}">
                                        </div>
                                        <input type="text" name="status" value="pending" hidden>
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Address</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="address"
                                                value="{{ $personal_info['address'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <div
                                            class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden flex items-center justify-center border border-gray-300">
                                            @if (!empty($personal_info['profile_picture']))
                                                <img src="{{ asset($personal_info['profile_picture']) }}"
                                                    alt="Profile Picture" class="w-full h-full object-cover rounded-full">
                                                <input type="text" name="profile_picture"
                                                    value="{{ $personal_info['profile_picture'] }}" hidden>
                                            @endif
                                            <x-file-uploader name="profile_picture" id="profile_picture" />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Phone No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="phone_no"
                                            value="{{ $personal_info['phone_no'] ?? '' }}">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Email</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter Here" name="email"
                                            value="{{ $personal_info['email'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-6">
                                    <label class="md:w-32">CNIC</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter Here" name="cnic"
                                        value="{{ $personal_info['cnic'] ?? '' }}">
                                </div>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-32 md:mb-20 text-base font-semibold">
                                    <div>
                                        <h3 class="pb-2">Front Image</h3>
                                        <div
                                            class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                            @if (!empty($personal_info['front_image']))
                                                <img src="{{ asset($personal_info['front_image']) }}" alt="Front Image"
                                                    class="h-full object-contain">
                                                <input type="text" name="front_image"
                                                    value="{{ $personal_info['front_image'] }}" hidden>
                                            @endif
                                            <x-file-uploader name="front_image" id="front_image" />
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="pb-2">Back Image</h3>
                                        <div
                                            class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                            @if (!empty($personal_info['back_image']))
                                                <img src="{{ asset($personal_info['back_image']) }}" alt="Back Image"
                                                    class="h-full object-contain">
                                                <input type="text" name="back_image"
                                                    value="{{ $personal_info['back_image'] }}" hidden>
                                            @endif
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

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div class="flex flex-col gap-6">
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Store Name</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#333]"
                                                type="text" placeholder="Enter Here" name="store_name"
                                                value="{{ $store_info['store_name'] ?? '' }}">
                                        </div>
                                        <input type="text" name="status" value="pending" hidden>
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Type</label>
                                            <select name="type" id="type"
                                                class="rounded-lg w-full p-2 border border-gray-300 text-[#333]">
                                                <option value="">Select Type</option>
                                                <option value="Retail"
                                                    {{ ($store_info['type'] ?? '') == 'Retail' ? 'selected' : '' }}>Retail
                                                </option>
                                                <option value="Wholesale"
                                                    {{ ($store_info['type'] ?? '') == 'Wholesale' ? 'selected' : '' }}>
                                                    Wholesale</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <label for="profile_picture2" class="cursor-pointer">
                                            <div
                                                class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-gray-300 flex items-center justify-center">
                                                <img id="imagePreview" class="w-full h-full object-cover"
                                                    src="{{ $store_info['profile_picture_store'] ?? asset('default-profile.png') }}"
                                                    alt="Profile Picture">
                                            </div>
                                        </label>
                                        <input type="file" name="profile_picture_store" id="profile_picture2"
                                            class="hidden" accept="image/*" onchange="previewImage(event)">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Phone No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#333]"
                                            type="text" placeholder="Enter Here" name="phone_no"
                                            value="{{ $store_info['phone_no'] ?? '' }}">
                                    </div>

                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Email</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#333]"
                                            type="email" placeholder="Enter Here" name="email"
                                            value="{{ $store_info['email'] ?? '' }}">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Country</label>
                                        <select name="country" id="country"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#333]">
                                            <option value="">Select Country</option>
                                            <option value="USA"
                                                {{ ($store_info['country'] ?? '') == 'USA' ? 'selected' : '' }}>USA
                                            </option>
                                            <option value="Canada"
                                                {{ ($store_info['country'] ?? '') == 'Canada' ? 'selected' : '' }}>Canada
                                            </option>
                                        </select>
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Province/Region</label>
                                        <select name="province" id="province"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#333]">
                                            <option value="">Select Province</option>
                                            <option value="Ontario"
                                                {{ ($store_info['province'] ?? '') == 'Ontario' ? 'selected' : '' }}>
                                                Ontario</option>
                                            <option value="Quebec"
                                                {{ ($store_info['province'] ?? '') == 'Quebec' ? 'selected' : '' }}>Quebec
                                            </option>
                                        </select>
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">City</label>
                                        <select name="city" id="city"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#333]">
                                            <option value="">Select City</option>
                                            <option value="New York"
                                                {{ ($store_info['city'] ?? '') == 'New York' ? 'selected' : '' }}>New York
                                            </option>
                                            <option value="Los Angeles"
                                                {{ ($store_info['city'] ?? '') == 'Los Angeles' ? 'selected' : '' }}>Los
                                                Angeles</option>
                                        </select>
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Zip Code</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#333]"
                                            type="text" placeholder="0000" name="zip_code"
                                            value="{{ $store_info['zip_code'] ?? '' }}">
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-6 gap-2 md:gap-0">
                                    <label class="md:w-32">Address</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#333]" type="text"
                                        name="address" placeholder="Enter Here"
                                        value="{{ $store_info['address'] ?? '' }}">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-6 mb-32 gap-2 md:gap-0 relative">
                                    <label class="md:w-32">Pin Location</label>
                                    <div class="relative w-full">
                                        <input class="rounded-lg w-full p-2 pr-10 border border-gray-300 text-[#333]"
                                            type="text" placeholder="Enter Pin Location" name="pin_location"
                                            value="{{ $store_info['pin_location'] ?? '' }}">
                                        <img class="absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5"
                                            src="{{ asset('asset/Location.svg') }}" alt="Location Icon">
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
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="step" value="3">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 text-base font-semibold">
                                    <!-- Shop / Home Bill -->
                                    <div>
                                        <h3 class="pb-2">Shop / Home Bill</h3>
                                        <div
                                            class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                            @if (!empty($documents_info['home_bill']))
                                                <img src="{{ asset($documents_info['home_bill']) }}" alt="Home Bill"
                                                    class="h-full object-cover">
                                                <input type="text" name="home_bill"
                                                    value="{{ $documents_info['home_bill'] }}" hidden>
                                            @endif
                                            <x-file-uploader name="home_bill" id="home_bill" />
                                        </div>
                                    </div>
                                    <input type="text" name="status" value="pending" hidden>
                                    <!-- Shop Video (Optional) -->
                                    <div>
                                        <h3 class="pb-2">Shop Video (Optional)</h3>
                                        <div
                                            class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                            @if (!empty($documents_info['shop_video']))
                                                <video controls class="h-[100px]">
                                                    <source src="{{ asset($documents_info['shop_video']) }}"
                                                        class="w-50" type="video/mp4">
                                                    <source src="{{ asset($documents_info['shop_video']) }}"
                                                        class="w-50" type="video/mp4">
                                                    <input type="text" name="shop_video"
                                                        value="{{ $documents_info['shop_video'] }}" hidden>
                                                </video>
                                            @endif
                                            <x-file-uploader name="shop_video" id="shop_video" />
                                        </div>
                                    </div>
                                </div>

                                {{-- Select Options --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 mb-32 md:mb-20">
                                    <!-- Country -->
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Country</label>
                                        <select name="country" id="country"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">
                                            <option value="">Select Country</option>
                                            <option value="USA"
                                                {{ isset($documents_info['country']) && $documents_info['country'] == 'USA' ? 'selected' : '' }}>
                                                USA</option>
                                            <option value="Canada"
                                                {{ isset($documents_info['country']) && $documents_info['country'] == 'Canada' ? 'selected' : '' }}>
                                                Canada</option>
                                        </select>
                                    </div>

                                    <!-- Province/ Region -->
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Province/ Region</label>
                                        <select name="province" id="province"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">
                                            <option value="">Select Province</option>
                                            <option value="Ontario"
                                                {{ isset($documents_info['province']) && $documents_info['province'] == 'Ontario' ? 'selected' : '' }}>
                                                Ontario</option>
                                            <option value="Quebec"
                                                {{ isset($documents_info['province']) && $documents_info['province'] == 'Quebec' ? 'selected' : '' }}>
                                                Quebec</option>
                                        </select>
                                    </div>

                                    <!-- City -->
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">City</label>
                                        <select name="city" id="city"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">
                                            <option value="">Select City</option>
                                            <option value="Toronto"
                                                {{ isset($documents_info['city']) && $documents_info['city'] == 'Toronto' ? 'selected' : '' }}>
                                                Toronto</option>
                                            <option value="Vancouver"
                                                {{ isset($documents_info['city']) && $documents_info['city'] == 'Vancouver' ? 'selected' : '' }}>
                                                Vancouver</option>
                                        </select>
                                    </div>
                                </div>


                                <!-- Footer Buttons -->
                                <div
                                    class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                                    <button type="button"
                                        class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">
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

                                <!-- Account Type -->
                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-4 text-sm font-semibold">
                                    <label class="md:w-32">Account Type</label>
                                    <select name="account_type" id="account_type"
                                        class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">
                                        <option value="">Option</option>
                                        <option value="savings"
                                            {{ ($bank_info['account_type'] ?? '') == 'savings' ? 'selected' : '' }}>Savings
                                        </option>
                                        <option value="current"
                                            {{ ($bank_info['account_type'] ?? '') == 'current' ? 'selected' : '' }}>Current
                                        </option>
                                    </select>
                                </div>
                                <input type="text" name="status" value="pending" hidden>
                                <!-- Bank Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6 text-sm font-semibold">
                                        <label class="md:w-32">Bank Name</label>
                                        <select name="bank_name" id="bank_name"
                                            class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]">
                                            <option value="">Option</option>
                                            <option value="Bank A"
                                                {{ ($bank_info['bank_name'] ?? '') == 'Bank A' ? 'selected' : '' }}>Bank A
                                            </option>
                                            <option value="Bank B"
                                                {{ ($bank_info['bank_name'] ?? '') == 'Bank B' ? 'selected' : '' }}>Bank B
                                            </option>
                                        </select>
                                    </div>

                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Branch Code</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter here" id="branch_code" name="branch_code"
                                            value="{{ $bank_info['branch_code'] ?? '' }}">
                                    </div>

                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Branch Name</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter here" id="branch_name" name="branch_name"
                                            value="{{ $bank_info['branch_name'] ?? '' }}">
                                    </div>

                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                        <label class="md:w-32">Branch Phone</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter here" id="branch_phone"
                                            name="branch_phone" value="{{ $bank_info['branch_phone'] ?? '' }}">
                                    </div>
                                </div>

                                <!-- Account Details -->
                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                    <label class="md:w-32">Account Title</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" id="account_title" name="account_title"
                                        value="{{ $bank_info['account_title'] ?? '' }}">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                    <label class="md:w-32">Account No.</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" id="account_no" name="account_no"
                                        value="{{ $bank_info['account_no'] ?? '' }}">
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                    <label class="md:w-32">IBAN No.</label>
                                    <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                        type="text" placeholder="Enter here" id="iban_no" name="iban_no"
                                        value="{{ $bank_info['iban_no'] ?? '' }}">
                                </div>

                                <!-- File Uploads -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-32 md:mb-20 text-base font-semibold">
                                    <div>
                                        <h3 class="pb-2">Canceled Cheque</h3>
                                        <div
                                            class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                            @if (!empty($bank_info['canceled_cheque']))
                                                <img src="{{ asset($bank_info['canceled_cheque']) }}"
                                                    alt="Canceled Cheque" class="h-full object-contain">
                                                <input type="text" name="canceled_cheque"
                                                    value="{{ $bank_info['canceled_cheque'] }}" hidden>
                                            @endif
                                            <x-file-uploader name="canceled_cheque" id="canceled_cheque" />
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="pb-2">Verification Letter (Optional)</h3>
                                        <div
                                            class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                            @if (!empty($bank_info['verification_letter']))
                                                <img src="{{ asset($bank_info['verification_letter']) }}"
                                                    alt="Verification Letter" class="h-full object-contain ">
                                                <input type="text" name="verification_letter"
                                                    value="{{ $bank_info['verification_letter'] }}" hidden>
                                            @endif
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div class="flex flex-col gap-6">
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Business Name</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="business_name"
                                                id="business_name" value="{{ $business_info['business_name'] ?? '' }}">
                                        </div>
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Owner Name</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="owner_name"
                                                id="owner_name" value="{{ $business_info['owner_name'] ?? '' }}">
                                        </div>
                                        <input type="text" name="status" value="pending" hidden>
                                        <div
                                            class="flex flex-col md:flex-row md:items-center gap-2 md:gap-5 text-sm font-semibold">
                                            <label class="md:w-32">Phone No.</label>
                                            <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Here" name="phone_no" id="phone_no"
                                                value="{{ $business_info['phone_no'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <div
                                            class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden flex items-center justify-center border border-gray-300">
                                            @if (!empty($business_info['personal_profile']))
                                                <img src="{{ asset($business_info['personal_profile']) }}"
                                                    alt="Personal Profile" class="h-full object-contain">
                                                <input type="text" name="personal_profile"
                                                    value="{{ $business_info['personal_profile'] }}" hidden>
                                            @endif
                                            <x-file-uploader name="personal_profile" id="personal_profile" />
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                        <label class="md:w-32">Reg. No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter here" name="reg_no" id="reg_no"
                                            value="{{ $business_info['reg_no'] ?? '' }}">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                        <label class="md:w-32">Tax. No.</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter here" name="tax_no" id="tax_no"
                                            value="{{ $business_info['tax_no'] ?? '' }}">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center gap-2 md:gap-0 mt-6 text-sm font-semibold">
                                        <label class="md:w-32">Address</label>
                                        <input class="rounded-lg w-full p-2 border border-gray-300 text-[#B4B4B4]"
                                            type="text" placeholder="Enter here" name="address" id="address"
                                            value="{{ $business_info['address'] ?? '' }}">
                                    </div>
                                    <div
                                        class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-6 gap-2 md:gap-0 relative">
                                        <label class="md:w-32">Pin Location</label>
                                        <div class="relative w-full">
                                            <input
                                                class="rounded-lg w-full p-2 pr-10 border border-gray-300 text-[#B4B4B4]"
                                                type="text" placeholder="Enter Pin Location" name="pin_location"
                                                id="pin_location" value="{{ $business_info['pin_location'] ?? '' }}">
                                            <img class="absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5"
                                                src="{{ asset('asset/Location.svg') }}" alt="Location Icon">
                                        </div>
                                    </div>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-32 md:mb-20 text-base font-semibold">
                                        <div>
                                            <h3 class="pb-2">Letter Head</h3>
                                            <div
                                                class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                                @if (!empty($business_info['letter_head']))
                                                    <img src="{{ asset($business_info['letter_head']) }}"
                                                        alt="Letter Head" class="h-full object-contain">
                                                    <input type="text" name="letter_head"
                                                        value="{{ $business_info['letter_head'] }}" hidden>
                                                @endif
                                                <x-file-uploader name="letter_head" id="letter_head" />
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="pb-2">Stamp</h3>
                                            <div
                                                class="w-full rounded-lg border border-gray-300 h-[30vh] flex items-center justify-center">
                                                @if (!empty($business_info['stamp']))
                                                    <img src="{{ asset($business_info['stamp']) }}" alt="Stamp"
                                                        class="h-full object-contain">
                                                    <input type="text" name="stamp"
                                                        value="{{ $business_info['stamp'] }}" hidden>
                                                @endif
                                                <x-file-uploader name="stamp" id="stamp" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 w-full bg-[#D9D9D980] rounded-b-2xl p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                                    <button
                                        class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-[#D9D9D980]">Back</button>
                                    <button
                                        class="w-full md:w-auto rounded-3xl shadow-md px-6 py-2 bg-primary text-white">Submit</button>
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
@section('js')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function firstImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                let imgElement = document.createElement('img');
                imgElement.src = reader.result;
                imgElement.classList.add('h-full', 'object-contain');

                let container = document.getElementById(previewId);
                container.innerHTML = ""; // Purana image hatao
                container.appendChild(imgElement);
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
