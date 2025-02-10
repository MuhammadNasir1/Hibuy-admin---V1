@extends('boilerplate')

@section('title', 'Hibuy')

@section('main-content')

    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-[97vh] transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div
            class="h-full pl-3 py-4 ml-3 mt-3 overflow-y-auto bg-primary text-white rounded-tl-[30px] rounded-tr-[15px] rounded-br-[80px] rounded-bl-[5px]">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="#"
                        class="flex mb-4 relative px-5 py-3 w-[80%] bg-white rounded-tl-[30px] rounded-tr-[10px] rounded-br-[30px] rounded-bl-[3px]">
                        <img class="" src="{{ asset('asset/logo-black.png') }}" alt="Logo">
                    </a>
                </li>
                <li class="mt-6">
                    <a href="#"
                        class="flex listItem items-center duration-25 p-2 py-2.5 text-white rounded-l-full hover:text-primary hover:bg-white  group hover:rounded-tl-10 hover:rounded-bl-10 relative">
                        <svg class="w-5 h-5 text-white transition duration-25  group-hover:text-primary "
                            viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="15.1827" y="3" width="2.43901" height="5.69101" rx="1" fill="currentColor" />
                            <rect x="18.4351" y="3" width="4.06501" height="5.69101" rx="1" fill="currentColor" />
                            <rect x="2.49994" y="3" width="11.382" height="5.69101" rx="1" fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M22.4999 11C22.4999 10.4477 22.0522 10 21.4999 10H3.49994C2.94765 10 2.49994 10.4477 2.49994 11V22C2.49994 22.5523 2.94765 23 3.49994 23H21.4999C22.0522 23 22.4999 22.5523 22.4999 22V11ZM20.3862 17.634C20.3862 17.185 20.0222 16.821 19.5732 16.821C19.1242 16.821 18.7602 17.185 18.7602 17.634V20.073C18.7602 20.522 19.1242 20.886 19.5732 20.886C20.0222 20.886 20.3862 20.522 20.3862 20.073V17.634ZM17.1342 13.569C17.1342 13.12 16.7702 12.756 16.3212 12.756C15.8722 12.756 15.5082 13.12 15.5082 13.569V20.073C15.5082 20.522 15.8722 20.886 16.3212 20.886C16.7702 20.886 17.1342 20.522 17.1342 20.073V13.569ZM5.31824 19.694C6.08029 20.4561 7.1135 20.8848 8.19121 20.886C9.26931 20.886 10.3033 20.4578 11.0656 19.6954C11.8279 18.9331 12.2562 17.8991 12.2562 16.821C12.2562 15.7429 11.8279 14.709 11.0656 13.9466C10.4965 13.3775 9.77603 12.9946 8.99883 12.837C8.55877 12.7478 8.19121 13.12 8.19121 13.569C8.19121 14.018 8.56495 14.3694 8.98925 14.5163C9.18493 14.584 9.37222 14.6768 9.54625 14.7931C9.94734 15.0611 10.26 15.442 10.4446 15.8877C10.6292 16.3333 10.6775 16.8237 10.5833 17.2969C10.4892 17.77 10.2569 18.2046 9.91584 18.5457C9.57474 18.8868 9.14015 19.1191 8.66703 19.2132C8.19391 19.3073 7.70351 19.259 7.25784 19.0744C6.81217 18.8898 6.43125 18.5772 6.16325 18.1761C6.04688 18.0019 5.95407 17.8145 5.8863 17.6186C5.73955 17.1945 5.38843 16.821 4.93969 16.821C4.49057 16.821 4.11847 17.189 4.20818 17.629C4.36648 18.4056 4.74953 19.1253 5.31824 19.694Z"
                                fill="currentColor" />
                        </svg>


                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <li class="">
                    <a href="#"
                        class="flex listItem items-center duration-25 p-2 py-2.5 text-white rounded-l-full hover:text-primary hover:bg-white  group hover:rounded-tl-10 hover:rounded-bl-10 relative">
                        <svg class="w-5 h-5 text-white transition duration-25  group-hover:text-primary "
                            viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.5002 10.4736L16.2861 8.9592L6.91094 5.20915L3.67864 6.50207C3.51274 6.56843 3.36015 6.65677 3.22367 6.76295L12.5002 10.4736ZM2.54427 7.83748C2.51519 7.96885 2.5 8.10465 2.5 8.24296V17.8004C2.5 18.5671 2.96678 19.2565 3.67864 19.5413L10.8752 22.4199C11.1993 22.5495 11.535 22.6389 11.8752 22.688V11.5698L2.54427 7.83748ZM13.1252 22.6879C13.4652 22.6388 13.8008 22.5495 14.1248 22.4199L21.3214 19.5413C22.0332 19.2565 22.5 18.5671 22.5 17.8004V8.24296C22.5 8.1047 22.4848 7.96894 22.4558 7.83762L13.1252 11.5698V22.6879ZM21.7765 6.76305L17.9689 8.28606L8.5938 4.53601L10.8752 3.62346C11.9182 3.20624 13.0818 3.20624 14.1248 3.62346L21.3214 6.50207C21.4873 6.56845 21.6399 6.65683 21.7765 6.76305Z"
                                fill="currentColor" />
                        </svg>


                        <span class="ms-3">Product</span>
                    </a>
                </li>

                <li class="">
                    <a href="#"
                        class="flex listItem items-center duration-25 p-2 py-2.5 text-white rounded-l-full hover:text-primary hover:bg-white  group hover:rounded-tl-10 hover:rounded-bl-10 relative">
                        <svg class="w-5 h-5 text-white transition duration-25  group-hover:text-primary "
                            viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.76316 14.5791C8.29429 14.5789 8.80586 14.7795 9.19531 15.1406C9.58477 15.5018 9.82332 15.9968 9.86316 16.5264L9.86842 16.6843V19.8422C9.86859 20.3733 9.66799 20.8849 9.30685 21.2744C8.9457 21.6638 8.45069 21.9024 7.92105 21.9422L7.76316 21.9475H4.60526C4.07413 21.9476 3.56256 21.747 3.17311 21.3859C2.78365 21.0247 2.5451 20.5297 2.50526 20.0001L2.5 19.8422V16.6843C2.49983 16.1532 2.70043 15.6416 3.06158 15.2522C3.42272 14.8627 3.91773 14.6241 4.44737 14.5843L4.60526 14.5791H7.76316ZM17.2368 18.7896C17.5051 18.7899 17.7632 18.8926 17.9583 19.0768C18.1534 19.261 18.2708 19.5127 18.2865 19.7805C18.3022 20.0484 18.2151 20.3121 18.0429 20.5178C17.8707 20.7236 17.6264 20.8558 17.36 20.8875L17.2368 20.8948H13.0263C12.758 20.8945 12.5 20.7918 12.3049 20.6076C12.1098 20.4235 11.9924 20.1717 11.9767 19.9039C11.9609 19.6361 12.0481 19.3723 12.2203 19.1666C12.3925 18.9609 12.6367 18.8286 12.9032 18.7969L13.0263 18.7896H17.2368ZM21.4474 14.5791C21.7265 14.5791 21.9943 14.69 22.1917 14.8874C22.3891 15.0848 22.5 15.3525 22.5 15.6317C22.5 15.9109 22.3891 16.1786 22.1917 16.376C21.9943 16.5734 21.7265 16.6843 21.4474 16.6843H13.0263C12.7471 16.6843 12.4794 16.5734 12.282 16.376C12.0846 16.1786 11.9737 15.9109 11.9737 15.6317C11.9737 15.3525 12.0846 15.0848 12.282 14.8874C12.4794 14.69 12.7471 14.5791 13.0263 14.5791H21.4474ZM7.76316 4.05273C8.32151 4.05273 8.85699 4.27454 9.2518 4.66935C9.64662 5.06416 9.86842 5.59965 9.86842 6.158V9.31589C9.86842 9.87424 9.64662 10.4097 9.2518 10.8045C8.85699 11.1994 8.32151 11.4212 7.76316 11.4212H4.60526C4.04691 11.4212 3.51143 11.1994 3.11662 10.8045C2.7218 10.4097 2.5 9.87424 2.5 9.31589V6.158C2.5 5.59965 2.7218 5.06416 3.11662 4.66935C3.51143 4.27454 4.04691 4.05273 4.60526 4.05273H7.76316ZM17.2368 8.26326C17.5051 8.26356 17.7632 8.36629 17.9583 8.55047C18.1534 8.73465 18.2708 8.98637 18.2865 9.2542C18.3022 9.52204 18.2151 9.78577 18.0429 9.99151C17.8707 10.1972 17.6264 10.3295 17.36 10.3612L17.2368 10.3685H13.0263C12.758 10.3682 12.5 10.2655 12.3049 10.0813C12.1098 9.89714 11.9924 9.64542 11.9767 9.37758C11.9609 9.10975 12.0481 8.84602 12.2203 8.64028C12.3925 8.43454 12.6367 8.30232 12.9032 8.27063L13.0263 8.26326H17.2368ZM21.4474 4.05273C21.7157 4.05303 21.9737 4.15577 22.1688 4.33994C22.3639 4.52412 22.4813 4.77584 22.497 5.04368C22.5127 5.31151 22.4256 5.57524 22.2534 5.78098C22.0812 5.98672 21.8369 6.11894 21.5705 6.15063L21.4474 6.158H13.0263C12.758 6.1577 12.5 6.05497 12.3049 5.87079C12.1098 5.68661 11.9924 5.43489 11.9767 5.16706C11.9609 4.89922 12.0481 4.63549 12.2203 4.42975C12.3925 4.22401 12.6367 4.09179 12.9032 4.0601L13.0263 4.05273H21.4474Z"
                                fill="currentColor" />
                        </svg>


                        <span class="ms-3">Packages Offer</span>
                    </a>
                </li>

                <li class="">
                    <a href="#"
                        class="flex listItem items-center duration-25 p-2 py-2.5 text-white rounded-l-full hover:text-primary hover:bg-white  group hover:rounded-tl-10 hover:rounded-bl-10 relative">
                        <svg class="w-5 h-5 text-white transition duration-25  group-hover:text-primary "
                            viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.1316 18.2622H12.7632C11.6022 18.2622 10.658 19.2064 10.658 20.3675C10.658 21.5285 11.6022 22.4728 12.7632 22.4728H20.1316C21.2927 22.4728 22.2369 21.5285 22.2369 20.3675C22.2369 19.2064 21.2927 18.2622 20.1316 18.2622ZM20.1316 10.8938H12.7632C11.6022 10.8938 10.658 11.838 10.658 12.9991C10.658 14.1601 11.6022 15.1043 12.7632 15.1043H20.1316C21.2927 15.1043 22.2369 14.1601 22.2369 12.9991C22.2369 11.838 21.2927 10.8938 20.1316 10.8938ZM20.1316 3.52539H12.7632C11.6022 3.52539 10.658 4.4696 10.658 5.63065C10.658 6.79171 11.6022 7.73592 12.7632 7.73592H20.1316C21.2927 7.73592 22.2369 6.79171 22.2369 5.63065C22.2369 4.4696 21.2927 3.52539 20.1316 3.52539Z"
                                fill="currentColor" />
                            <path
                                d="M5.39476 22.9995C6.84814 22.9995 8.02634 21.8213 8.02634 20.3679C8.02634 18.9145 6.84814 17.7363 5.39476 17.7363C3.94138 17.7363 2.76318 18.9145 2.76318 20.3679C2.76318 21.8213 3.94138 22.9995 5.39476 22.9995Z"
                                fill="currentColor" />
                            <path
                                d="M5.39476 15.6323C6.84814 15.6323 8.02634 14.4541 8.02634 13.0007C8.02634 11.5473 6.84814 10.3691 5.39476 10.3691C3.94138 10.3691 2.76318 11.5473 2.76318 13.0007C2.76318 14.4541 3.94138 15.6323 5.39476 15.6323Z"
                                fill="currentColor" />
                            <path
                                d="M5.39476 8.26316C6.84814 8.26316 8.02634 7.08496 8.02634 5.63158C8.02634 4.1782 6.84814 3 5.39476 3C3.94138 3 2.76318 4.1782 2.76318 5.63158C2.76318 7.08496 3.94138 8.26316 5.39476 8.26316Z"
                                fill="currentColor" />
                        </svg>

                        <span class="ms-3">Orders</span>
                    </a>
                </li>


            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
                <p class="text-2xl text-gray-400 dark:text-gray-500">
                    <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 1v16M1 9h16" />
                    </svg>
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
                <p class="text-2xl text-gray-400 dark:text-gray-500">
                    <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 1v16M1 9h16" />
                    </svg>
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
                <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28 dark:bg-gray-800">
                    <p class="text-2xl text-gray-400 dark:text-gray-500">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 1v16M1 9h16" />
                        </svg>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
