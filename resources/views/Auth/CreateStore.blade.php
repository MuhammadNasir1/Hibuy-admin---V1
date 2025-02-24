@extends('Auth.layout')
@section('title', 'Create Store')
@section('content')
    <div class="w-full mb-5">
        <div class="flex flex-col justify-center gap-2 mt-5 md:flex-row">
            <!-- Left Panel - Store Selection -->
            <div class="flex flex-col items-center w-full p-6 bg-white rounded-lg shadow-lg md:w-64">
                <h2 class="mb-4 font-bold text-gray-700 md:text-sm lg:text-lg">Select Type Profile</h2>
                <div class="flex flex-row justify-center w-full gap-5 align-middle md:flex-col">
                    <button id="freelancerBtn" onclick="selectProfile('freelancerBtn', 'freelancerSection')"
                        class="flex flex-col items-center justify-center w-full p-4 transition border rounded-lg h-52 hover:bg-gray-100">
                        <img src="{{ asset('asset/image 916.png') }}" alt="Freelancer Store" class="mb-2 w-14 h-14">
                        <span class="text-gray-700">Freelancer Store</span>
                    </button>
                    <button id="businessBtn" onclick="selectProfile('businessBtn', 'businessSection')"
                        class="flex flex-col items-center justify-center w-full p-4 transition border rounded-lg h-52 hover:bg-gray-100">
                        <img src="{{ asset('asset/image 917.png') }}" alt="Business Store" class="mb-2 w-14 h-14">
                        <span class="text-gray-700">Business Store</span>
                    </button>
                </div>
                <button id="selectOneBtn"
                    class="w-full py-2 mt-4 text-gray-500 bg-gray-300 rounded-full cursor-not-allowed">
                    Select One
                </button>
                <button id="continueBtn" onclick="continueToSection()"
                    class="hidden w-full py-2 mt-4 text-white bg-blue-600 rounded-full">
                    <a href="{{  route("CreateProfile") }}">Continue</a>
                </button>
            </div>

            <!-- Right Panel - Preview -->
            <div class="p-6 bg-white shadow-lg rounded-xl">
                <!-- Default Section (Visible Initially) -->
                <div id="defaultSection">
                    <div class="relative">
                        <div class="relative overflow-hidden rounded-t-lg">
                            <div class="">
                                <div class="flex justify-center px-4">
                                    <img src="{{ asset('asset/Group 1000008400.svg') }}" alt="Business Store">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('asset/dextopdiagram.svg') }}" alt="">
                    </div>
                    <div class="flex items-center justify-center">
                        <h1 class="mt-5 text-xl font-extrabold">Hi Buyooooooo</h1>
                    </div>
                    <div class="flex items-center justify-center">
                        <h1 class="text-lg text-gray-500">Start Selling Products</h1>
                    </div>
                    <div class="mt-4 mb-3 space-y-4">
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                    </div>
                </div>

                <!-- Freelancer Store Section -->
                <div id="freelancerSection" class="hidden">
                    <h1 class="text-2xl font-bold text-gray-700">Freelancer Store</h1>
                    <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                        content filler for when you don't really have content to put in there yet.</p>
                    <div class="flex justify-center px-4">
                        <img src="{{ asset('asset/dextopdiagram1.svg') }}" alt="Freelancer Store">
                    </div>
                    <div class="max-w-4xl mx-auto">
                        <h2 class="mb-4 text-xl font-bold">Features List</h2>
                        <div class="grid grid-cols-4 gap-8 text-gray-500">
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 mb-3 space-y-4">
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                    </div>
                </div>

                <!-- Business Store Section -->
                <div id="businessSection" class="hidden">
                    <h1 class="text-2xl font-bold text-gray-700">Business Store</h1>
                    <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                        content filler for when you don't really have content to put in there yet.</p>
                    <div class="flex justify-center px-4">
                        <img src="{{ asset('asset/dextopdiagram2.svg') }}" alt="Business Store">
                    </div>
                    <div class="max-w-4xl mx-auto">
                        <h2 class="mb-4 text-xl font-bold">Features List</h2>
                        <div class="grid grid-cols-4 gap-8 text-gray-500">
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                            <ul class="flex flex-col gap-2">
                                <li>1. Option</li>
                                <li>2. Option</li>
                                <li>3. Option</li>
                                <li>4. Option</li>
                                <li>5. Option</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 mb-3 space-y-4">
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                        <div>
                            <h3 class="mb-2 font-medium">Heading</h3>
                            <p class="text-sm text-gray-600">Lorem ipsum is basically just dummy text that is latin. It's a
                                content filler for when you don't really have content to put in there yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedButton = null;
        let selectedSection = null;

        function selectProfile(buttonId, sectionId) {
            // Remove border and reset the text of previously selected button
            if (selectedButton) {
                selectedButton.classList.remove("border-blue-600", "bg-blue-600", "text-white");
                selectedButton.innerHTML = selectedButton.dataset.originalText;
            }

            // Update selected button and section
            selectedButton = document.getElementById(buttonId);
            selectedSection = document.getElementById(sectionId);

            // Save original text if not already saved
            if (!selectedButton.dataset.originalText) {
                selectedButton.dataset.originalText = selectedButton.innerHTML;
            }

            // Change selected button style and text
            selectedButton.classList.add("border-blue-600");

            // Hide the default section and other sections
            document.getElementById("defaultSection").classList.add("hidden");
            document.getElementById("freelancerSection").classList.add("hidden");
            document.getElementById("businessSection").classList.add("hidden");

            // Show the selected section
            selectedSection.classList.remove("hidden");

            // Hide "Select One" button and show "Continue" button
            document.getElementById("selectOneBtn").classList.add("hidden");
            document.getElementById("continueBtn").classList.remove("hidden");
        }
    </script>
@endsection
