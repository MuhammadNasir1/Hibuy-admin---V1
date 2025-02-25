@extends('layout')
@section('title', 'Dashboard')
@section('nav-title', 'Dashboard')
@section('content')
    <div class="w-full pt-10 min-h-[86vh]">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4  my-3">
            <!-- Card 1 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <!-- Replace with actual icon -->
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.0907 11.8356C12.0907 12.4519 12.2619 12.5432 12.65 12.6801L13.232 12.8856V10.9453H12.8896C12.4559 10.9453 12.0907 11.3448 12.0907 11.8356ZM14.944 17.2227H15.2864C15.7316 17.2227 16.0854 16.8232 16.0854 16.3324C16.0854 15.7161 15.9142 15.6248 15.5261 15.4878L14.944 15.2824V17.2227Z"
                                fill="#4A90E2" />
                            <path
                                d="M22.7394 6.64035L20.3996 8.98008C20.2284 9.15128 20.0116 9.23118 19.7947 9.23118C19.5779 9.23118 19.361 9.15128 19.1898 8.98008C19.0306 8.819 18.9414 8.60165 18.9414 8.37517C18.9414 8.1487 19.0306 7.93135 19.1898 7.77027L21.5296 5.43053C19.5208 3.71853 16.93 2.6685 14.0881 2.6685C7.78789 2.6685 2.67471 7.78168 2.67471 14.0818C2.67471 20.382 7.78789 25.4952 14.0881 25.4952C20.3882 25.4952 25.5014 20.382 25.5014 14.0818C25.5014 11.2399 24.4514 8.6491 22.7394 6.64035ZM16.0854 13.8764C16.8159 14.1389 17.7974 14.6639 17.7974 16.3417C17.7974 17.7684 16.6675 18.9439 15.2865 18.9439H14.9441V19.2293C14.9441 19.6972 14.556 20.0853 14.0881 20.0853C13.6201 20.0853 13.2321 19.6972 13.2321 19.2293V18.9439H13.1408C11.6228 18.9439 10.3787 17.6656 10.3787 16.0906C10.3787 15.6112 10.7668 15.2232 11.2347 15.2232C11.7027 15.2232 12.0907 15.6112 12.0907 16.0792C12.0907 16.7069 12.5587 17.2205 13.1408 17.2205H13.2321V14.6868L12.0907 14.2873C11.3603 14.0248 10.3787 13.4998 10.3787 11.822C10.3787 10.3953 11.5086 9.21976 12.8897 9.21976H13.2321V8.94584C13.2321 8.4779 13.6201 8.08984 14.0881 8.08984C14.556 8.08984 14.9441 8.4779 14.9441 8.94584V9.23118H15.0354C16.5533 9.23118 17.7974 10.5095 17.7974 12.0845C17.7974 12.5525 17.4093 12.9405 16.9414 12.9405C16.4735 12.9405 16.0854 12.5525 16.0854 12.0845C16.0854 11.4568 15.6174 10.9432 15.0354 10.9432H14.9441V13.4769L16.0854 13.8764ZM26.2889 2.33751C26.1987 2.12733 26.0312 1.95982 25.821 1.86957C25.7183 1.8331 25.6103 1.81382 25.5014 1.8125H20.9361C20.4681 1.8125 20.0801 2.20055 20.0801 2.6685C20.0801 3.13645 20.4681 3.5245 20.9361 3.5245H23.4356L21.5296 5.43053C21.9633 5.80717 22.3627 6.20664 22.7394 6.64035L24.6454 4.73432V7.23384C24.6454 7.70179 25.0335 8.08984 25.5014 8.08984C25.9694 8.08984 26.3574 7.70179 26.3574 7.23384V2.6685C26.3574 2.55437 26.3346 2.45165 26.2889 2.33751Z"
                                fill="#4A90E2" />
                        </svg>
                    </div>
                    <h3 class="text-gray-600 font-semibold">Total Sales</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">$166,580</h2>
                <p class="text-green-500 text-sm">⬆ 5% in the last 1 month</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <!-- Replace with actual icon -->
                        <svg width="25" height="26" viewBox="0 0 25 26" fill="#4A90E2"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.5002 10.4736L16.2861 8.9592L6.91094 5.20915L3.67864 6.50207C3.51274 6.56843 3.36015 6.65677 3.22367 6.76295L12.5002 10.4736ZM2.54427 7.83748C2.51519 7.96885 2.5 8.10465 2.5 8.24296V17.8004C2.5 18.5671 2.96678 19.2565 3.67864 19.5413L10.8752 22.4199C11.1993 22.5495 11.535 22.6389 11.8752 22.688V11.5698L2.54427 7.83748ZM13.1252 22.6879C13.4652 22.6388 13.8008 22.5495 14.1248 22.4199L21.3214 19.5413C22.0332 19.2565 22.5 18.5671 22.5 17.8004V8.24296C22.5 8.1047 22.4848 7.96894 22.4558 7.83762L13.1252 11.5698V22.6879ZM21.7765 6.76305L17.9689 8.28606L8.5938 4.53601L10.8752 3.62346C11.9182 3.20624 13.0818 3.20624 14.1248 3.62346L21.3214 6.50207C21.4873 6.56845 21.6399 6.65683 21.7765 6.76305Z"
                                fill="#4A90E2" />
                        </svg>
                    </div>
                    <h3 class="text-gray-600 font-semibold">Total Products</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">5,679</h2>
                <p class="text-green-500 text-sm">⬆ 2% in the last 1 month</p>
            </div>


            <!-- Card 3 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg width="21" height="26" viewBox="0 0 21 26" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_836_16087)">
                                <path
                                    d="M6.71962 12.7812C7.56125 14.3912 9.71847 14.3782 10.5429 12.7989C11.0545 13.0296 11.5684 13.2594 12.0804 13.4929C12.6988 13.7751 13.3282 14.037 13.9308 14.3498C14.8253 14.815 15.6196 15.7146 15.9153 16.5456C15.8271 16.5502 15.7468 16.5586 15.667 16.5586C14.6461 16.5591 13.6249 16.5586 12.6041 16.5577C11.9119 16.5572 11.4881 16.8617 11.269 17.5177C10.99 18.3533 10.7105 19.1889 10.4334 20.0249C10.1683 20.8234 10.4134 21.5373 11.0986 22.0183C11.177 22.073 11.2448 22.1951 11.2513 22.2903C11.2708 22.5828 11.256 22.8775 11.2592 23.1714C11.2606 23.287 11.2216 23.3482 11.0977 23.3348C11.0596 23.3306 11.0206 23.3343 10.9816 23.3343C7.71769 23.3343 4.45331 23.332 1.1894 23.3404C0.953108 23.3408 0.89276 23.2819 0.894152 23.046C0.905294 21.5146 0.88069 19.9827 0.904829 18.4517C0.935004 16.5349 1.80727 15.1005 3.51791 14.2329C4.55637 13.7064 5.63985 13.2673 6.71962 12.7812Z"
                                    fill="#4A90E2" />
                                <path
                                    d="M15.6461 17.4125C16.659 17.413 17.6719 17.412 18.6853 17.413C19.0237 17.4134 19.1142 17.4835 19.2206 17.8048C19.4995 18.6478 19.786 19.4885 20.0589 20.3338C20.2516 20.9299 19.8445 21.4503 19.2335 21.4071C18.8585 21.3806 18.5595 21.0789 18.5233 20.6736C18.5057 20.4735 18.4448 20.3125 18.2494 20.2284C17.9709 20.1091 17.7081 20.2902 17.6696 20.6263C17.6157 21.0993 17.3275 21.3941 16.9055 21.4071C16.4366 21.4215 16.1103 21.123 16.0727 20.6332C16.0574 20.4364 15.9868 20.2646 15.7914 20.2303C15.6563 20.2062 15.4841 20.2419 15.368 20.3143C15.284 20.3663 15.2343 20.519 15.2213 20.6328C15.1698 21.09 14.8755 21.3983 14.4535 21.4085C14.0083 21.4192 13.6899 21.142 13.6314 20.6704C13.6044 20.4527 13.5408 20.2535 13.3101 20.221C13.1774 20.2024 12.9968 20.2549 12.8974 20.3426C12.8097 20.4197 12.7888 20.5882 12.767 20.721C12.708 21.0817 12.4267 21.37 12.0795 21.4043C11.6696 21.4452 11.3191 21.2386 11.2216 20.8565C11.1798 20.6936 11.1831 20.4963 11.2337 20.3361C11.5071 19.4667 11.8023 18.6046 12.0888 17.7393C12.1686 17.4984 12.3353 17.4088 12.5832 17.4102C13.6049 17.4153 14.6253 17.412 15.6461 17.4125Z"
                                    fill="#4A90E2" />
                                <path
                                    d="M5.10369 5.8768C6.09851 5.8327 7.01813 5.67394 7.63043 4.78125C7.67731 4.81978 7.72467 4.8532 7.76552 4.89313C8.44327 5.54907 9.2538 5.8717 10.1966 5.86798C10.8307 5.8652 11.4644 5.86752 12.1125 5.86752C12.2211 7.11627 12.0948 8.28517 11.1784 9.23867C10.1683 10.2897 8.64521 10.6374 7.34354 10.1128C5.95367 9.55295 5.10369 8.29167 5.10323 6.7876C5.10369 6.49375 5.10369 6.20036 5.10369 5.8768Z"
                                    fill="#4A90E2" />
                                <path
                                    d="M12.1352 5.00265C11.3502 5.00265 10.5972 5.05046 9.8531 4.99058C8.94834 4.9177 8.29333 4.41077 7.87414 3.60535C7.73301 3.33425 7.51947 3.2363 7.29386 3.34168C7.13278 3.41688 7.05526 3.54547 7.03947 3.72466C6.98052 4.39777 6.74841 4.69812 6.10036 4.88056C5.79258 4.9669 5.46809 4.99429 5.11947 5.05371C5.08419 4.13549 5.04751 3.27669 5.45092 2.46709C6.11939 1.1255 7.50648 0.365574 9.02261 0.519694C10.4348 0.663137 11.6454 1.7132 12.0224 3.12024C12.1877 3.7344 12.1264 4.35971 12.1352 5.00265Z"
                                    fill="#4A90E2" />
                                <path
                                    d="M18.0387 21.7783C18.4188 21.9677 18.7716 22.1441 19.1245 22.3201C19.1365 22.3043 19.1481 22.288 19.1602 22.2722C19.1658 22.3168 19.176 22.3614 19.176 22.4064C19.1764 23.3576 19.1732 24.3088 19.1783 25.26C19.1792 25.4466 19.0994 25.4981 18.9262 25.4976C16.7374 25.4944 14.5491 25.4944 12.3603 25.4976C12.1835 25.4981 12.111 25.4382 12.112 25.2553C12.1175 24.3194 12.1166 23.384 12.1124 22.4482C12.112 22.321 12.1496 22.2764 12.2782 22.2286C12.6045 22.107 12.9155 21.944 13.2586 21.7857C14.0213 22.4408 14.7747 22.4491 15.647 21.75C15.9873 22.0582 16.3823 22.2662 16.8623 22.2644C17.3442 22.2625 17.7448 22.0694 18.0387 21.7783ZM15.6377 24.2066C15.6377 24.2076 15.6377 24.209 15.6377 24.2099C16.1247 24.2099 16.6112 24.2117 17.0981 24.2094C17.4059 24.208 17.5995 24.0432 17.6027 23.7875C17.606 23.5354 17.411 23.3599 17.1033 23.3585C16.1298 23.3548 15.1563 23.3548 14.1824 23.3585C13.8751 23.3599 13.6838 23.5363 13.6899 23.7926C13.6959 24.04 13.8839 24.2034 14.1777 24.2053C14.6642 24.209 15.1507 24.2066 15.6377 24.2066Z"
                                    fill="#4A90E2" />
                                <path
                                    d="M7.4206 11.0391C8.2418 11.2591 9.03468 11.2438 9.8285 11.0405C9.8285 11.435 9.8712 11.8092 9.81875 12.1699C9.7324 12.766 9.14795 13.1791 8.53519 13.1345C7.94238 13.0918 7.4582 12.6188 7.42338 12.0251C7.40574 11.7113 7.4206 11.3951 7.4206 11.0391Z"
                                    fill="#4A90E2" />
                            </g>
                            <defs>
                                <clipPath id="clip0_836_16087">
                                    <rect width="19.2145" height="25" fill="white"
                                        transform="translate(0.892761 0.5)" />
                                </clipPath>
                            </defs>
                        </svg>
                        <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Active Sellers</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">5,679</h2>
                <p class="text-green-500 text-sm">⬆ 2% in the last 1 month</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg width="25" height="26" viewBox="0 0 25 26" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.3106 14.2032C16.6675 14.2032 16.0244 14.2094 15.382 14.1956C15.2973 14.1938 15.2 14.1015 15.1348 14.0281C14.7031 13.545 14.1541 13.3235 13.5205 13.3229C13.1051 13.3223 12.681 13.3248 12.2763 13.4051C11.4758 13.5645 10.8622 14.2182 10.7423 14.9586C10.603 15.8187 10.9531 16.6764 11.6891 17.0817C12.0185 17.263 12.1483 17.5002 12.2017 17.8314C12.2318 18.0159 12.27 18.1997 12.3133 18.4268C12.1577 18.4268 12.0254 18.4268 11.893 18.4268C10.5654 18.4268 9.23719 18.3936 7.91086 18.4362C6.61089 18.4776 5.84608 17.209 6.00482 16.1274C6.12026 15.3388 6.57199 14.7465 7.22072 14.3456C7.90961 13.9202 8.65496 13.5852 9.37396 13.2081C9.71527 13.0293 10.0716 12.8706 10.3885 12.6547C10.9092 12.3003 11.0861 11.7381 10.8251 11.169C10.6677 10.8265 10.4293 10.5078 10.1745 10.2267C8.91031 8.83135 8.72021 6.845 9.74036 5.27586C10.5598 4.01542 12.0103 3.57561 13.4006 3.96585C14.8217 4.36488 15.5376 5.40636 15.7804 6.82115C16.0175 8.20018 15.6448 9.36966 14.6611 10.3729C14.4013 10.6376 14.1999 10.9965 14.0745 11.3485C13.8906 11.8673 14.0745 12.3642 14.545 12.6585C15.003 12.9446 15.5056 13.1585 15.988 13.4045C16.4404 13.6347 16.8921 13.865 17.3445 14.0959C17.3338 14.1316 17.3225 14.1674 17.3106 14.2032Z"
                                fill="#4A90E2" />
                            <path
                                d="M9.17752 2.88942C8.94476 3.11152 8.69442 3.31919 8.48048 3.56012C7.38127 4.79861 6.95527 6.26735 7.03683 7.89985C7.08702 8.8993 7.37876 9.82598 7.89888 10.6824C8.18121 11.1473 8.07141 11.5538 7.58769 11.8029C6.78461 12.2157 6.01793 12.6825 5.39429 13.3438C5.11698 13.6381 4.87919 13.9731 4.64643 14.3063C4.46385 14.5679 4.2405 14.7197 3.91801 14.7141C3.42739 14.7059 2.93676 14.7034 2.44676 14.6877C1.50628 14.6563 0.929073 13.8658 1.00687 12.8507C1.06961 12.0263 1.53702 11.4139 2.1914 10.9835C2.86774 10.5387 3.58988 10.1541 4.32143 9.8059C5.12388 9.42444 5.50534 8.90495 5.26442 8.2123C5.14396 7.86597 4.87229 7.5529 4.61255 7.2781C4.25242 6.89852 3.95503 6.49196 3.81073 5.99004C3.52212 4.9862 3.57483 3.99679 4.06106 3.06761C4.7832 1.68732 6.19925 1.22744 7.64039 1.65282C8.38511 1.87241 8.91025 2.31849 9.17752 2.88942Z"
                                fill="#4A90E2" />
                            <path
                                d="M15.7138 2.86861C16.3205 1.87732 17.2151 1.49649 18.315 1.50402C20.0912 1.51656 20.9934 2.90249 21.1916 4.09393C21.4106 5.40959 21.1226 6.53829 20.1175 7.45178C20.0184 7.54213 19.9374 7.65569 19.8603 7.76674C19.3671 8.47194 19.5215 9.20223 20.2756 9.62259C20.8861 9.96264 21.5329 10.2406 22.1308 10.6007C22.7777 10.9903 23.3674 11.4603 23.6981 12.1774C24.0425 12.924 23.9616 13.7427 23.486 14.2641C23.2966 14.4718 23.1265 14.5948 22.8172 14.4034C22.6008 14.2698 22.304 14.2309 22.0392 14.2171C21.5078 14.1888 20.9733 14.2145 20.4406 14.1989C20.3346 14.1957 20.1897 14.1374 20.1332 14.0558C19.428 13.0338 18.4455 12.3637 17.3532 11.8267C16.7935 11.5512 16.6687 11.1553 17.0043 10.6277C18.4794 8.30756 17.968 4.67239 15.8186 2.98468C15.7866 2.95959 15.7627 2.92383 15.7138 2.86861Z"
                                fill="#4A90E2" />
                            <path
                                d="M18.3294 21.7514C17.4096 21.7514 16.4899 21.7539 15.5707 21.7501C15.0324 21.7482 14.9088 21.6479 14.7545 21.1227C14.2783 19.5015 13.7933 17.8835 13.3322 16.2579C13.2475 15.9592 13.1351 15.8344 12.807 15.845C12.6301 15.8507 12.3873 15.7384 12.2813 15.5978C12.0642 15.3111 12.275 14.8901 12.6445 14.8462C12.9745 14.8073 13.3127 14.8042 13.6452 14.8261C13.9558 14.8468 14.0926 15.0928 14.1233 15.3657C14.1553 15.6499 14.2858 15.7089 14.5524 15.7082C16.9767 15.6995 19.4016 15.7045 21.8259 15.7051C22.3636 15.7051 22.4677 15.8344 22.326 16.3432C22.0455 17.3495 21.7544 18.3527 21.4877 19.3629C21.42 19.6188 21.2933 19.6923 21.0473 19.691C19.4794 19.6835 17.9122 19.6853 16.3443 19.6853C16.2295 19.6853 16.1128 19.681 16.0005 19.6992C15.6937 19.7481 15.5124 19.9583 15.5318 20.2274C15.5513 20.496 15.7583 20.6835 16.0676 20.6848C17.1336 20.6892 18.1995 20.6867 19.2655 20.6861C19.8923 20.6861 20.5197 20.6848 21.1464 20.6848C21.2192 20.6848 21.2945 20.6798 21.3654 20.693C21.6101 20.7388 21.8002 20.8555 21.8353 21.1246C21.8717 21.4013 21.7362 21.5939 21.4859 21.6868C21.3529 21.7357 21.2004 21.7464 21.0561 21.7476C20.1482 21.7539 19.2391 21.7514 18.3294 21.7514Z"
                                fill="#4A90E2" />
                            <path
                                d="M16.1712 24.5001C15.7145 24.5039 15.333 24.1331 15.3242 23.6757C15.3148 23.1983 15.6844 22.813 16.1568 22.8086C16.6236 22.8036 17.0214 23.1995 17.0138 23.6607C17.0069 24.1136 16.623 24.4964 16.1712 24.5001Z"
                                fill="#4A90E2" />
                            <path
                                d="M20.3007 24.5001C19.834 24.4951 19.4569 24.1005 19.4669 23.6274C19.477 23.1725 19.8609 22.8036 20.3196 22.8086C20.7738 22.8137 21.1578 23.1964 21.1603 23.6469C21.1628 24.1092 20.7644 24.5051 20.3007 24.5001Z"
                                fill="#4A90E2" />
                        </svg>
                        <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Active Buyers</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>

            <!-- Card 5 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg width="25" height="26" viewBox="0 0 25 26" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.1316 18.2642H12.7632C11.6022 18.2642 10.658 19.2084 10.658 20.3694C10.658 21.5305 11.6022 22.4747 12.7632 22.4747H20.1316C21.2927 22.4747 22.2369 21.5305 22.2369 20.3694C22.2369 19.2084 21.2927 18.2642 20.1316 18.2642ZM20.1316 10.8958H12.7632C11.6022 10.8958 10.658 11.84 10.658 13.001C10.658 14.1621 11.6022 15.1063 12.7632 15.1063H20.1316C21.2927 15.1063 22.2369 14.1621 22.2369 13.001C22.2369 11.84 21.2927 10.8958 20.1316 10.8958ZM20.1316 3.52734H12.7632C11.6022 3.52734 10.658 4.47155 10.658 5.63261C10.658 6.79366 11.6022 7.73787 12.7632 7.73787H20.1316C21.2927 7.73787 22.2369 6.79366 22.2369 5.63261C22.2369 4.47155 21.2927 3.52734 20.1316 3.52734Z"
                                fill="#4A90E2" />
                            <path
                                d="M5.39473 23.0014C6.84811 23.0014 8.02631 21.8232 8.02631 20.3699C8.02631 18.9165 6.84811 17.7383 5.39473 17.7383C3.94135 17.7383 2.76315 18.9165 2.76315 20.3699C2.76315 21.8232 3.94135 23.0014 5.39473 23.0014Z"
                                fill="#4A90E2" />
                            <path
                                d="M5.39473 15.6303C6.84811 15.6303 8.02631 14.4521 8.02631 12.9988C8.02631 11.5454 6.84811 10.3672 5.39473 10.3672C3.94135 10.3672 2.76315 11.5454 2.76315 12.9988C2.76315 14.4521 3.94135 15.6303 5.39473 15.6303Z"
                                fill="#4A90E2" />
                            <path
                                d="M5.39473 8.26316C6.84811 8.26316 8.02631 7.08496 8.02631 5.63158C8.02631 4.1782 6.84811 3 5.39473 3C3.94135 3 2.76315 4.1782 2.76315 5.63158C2.76315 7.08496 3.94135 8.26316 5.39473 8.26316Z"
                                fill="#4A90E2" />
                        </svg>
                        <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Current Orders</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>

            <!-- Card 6 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.8968 2.87109H9.33242C5.17796 2.87109 2.70126 5.34779 2.70126 9.50225V19.0552C2.70126 23.2211 5.17796 25.6978 9.33242 25.6978H18.8854C23.0399 25.6978 25.5166 23.2211 25.5166 19.0666V9.50225C25.528 5.34779 23.0513 2.87109 18.8968 2.87109ZM20.7914 16.6812C20.7458 16.784 20.6887 16.8753 20.6088 16.9552L17.1392 20.4248C16.968 20.596 16.7511 20.6759 16.5342 20.6759C16.3174 20.6759 16.1005 20.596 15.9293 20.4248C15.7701 20.2637 15.6809 20.0464 15.6809 19.8199C15.6809 19.5934 15.7701 19.3761 15.9293 19.215L17.9381 17.2063H8.23674C7.76879 17.2063 7.38074 16.8182 7.38074 16.3503C7.38074 15.8823 7.76879 15.4943 8.23674 15.4943H20.0039C20.118 15.4943 20.2208 15.5171 20.3349 15.5627C20.5403 15.654 20.7115 15.8138 20.8028 16.0307C20.8713 16.2361 20.8713 16.4758 20.7914 16.6812ZM19.9925 13.0632H8.23674C8.1226 13.0632 8.01988 13.0404 7.90575 12.9947C7.69557 12.9045 7.52806 12.737 7.4378 12.5268C7.39265 12.4243 7.36934 12.3135 7.36934 12.2015C7.36934 12.0895 7.39265 11.9787 7.4378 11.8762C7.48346 11.7735 7.54052 11.6822 7.62042 11.6023L11.0901 8.13265C11.4211 7.80166 11.9689 7.80166 12.2999 8.13265C12.6309 8.46363 12.6309 9.01148 12.2999 9.34246L10.3026 11.3512H20.0039C20.4718 11.3512 20.8599 11.7393 20.8599 12.2072C20.8599 12.6752 20.4718 13.0632 19.9925 13.0632Z"
                                fill="#4A90E2" />
                        </svg>
                        <!-- Replace with actual icon -->
                    </div>
                    <h3 class="text-gray-600 font-semibold">Return Orders</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,80</h2>
                <p class="text-green-500 text-sm">⬆ 5% in the last 1 month</p>
            </div>

            <!-- Card 7 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.33844 1.83412C9.01831 -0.121788 11.7844 -0.121791 12.4643 1.83412L13.4968 4.80453C13.7967 5.66737 14.602 6.2524 15.5152 6.27101L18.6593 6.33508C20.7296 6.37727 21.5844 9.008 19.9343 10.259L17.4283 12.1589C16.7004 12.7108 16.3928 13.6574 16.6574 14.5317L17.568 17.5417C18.1676 19.5237 15.9298 21.1496 14.2301 19.9668L11.6488 18.1706C10.899 17.6489 9.90371 17.6489 9.15391 18.1706L6.57262 19.9668C4.87294 21.1496 2.6351 19.5237 3.23472 17.5417L4.14537 14.5317C4.40989 13.6574 4.10232 12.7108 3.3744 12.1589L0.868428 10.259C-0.781657 9.008 0.0731168 6.37727 2.14339 6.33508L5.28749 6.27101C6.20077 6.2524 7.006 5.66737 7.30592 4.80453L8.33844 1.83412Z"
                                fill="#4A90E2" />
                        </svg>

                    </div>
                    <h3 class="text-gray-600 font-semibold">Reviews</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>

            <!-- Card 8 -->
            <div class="bg-white p-4 rounded-lg shadow-md border">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 13C2.5 7.47715 6.97715 3 12.5 3C18.0228 3 22.5 7.47715 22.5 13C22.5 18.5228 18.0228 23 12.5 23C6.97715 23 2.5 18.5228 2.5 13ZM12.5 10C12.2015 10 11.9344 10.1296 11.7497 10.3388C11.3843 10.7529 10.7523 10.7923 10.3383 10.4268C9.9242 10.0614 9.8848 9.42942 10.2503 9.01535C10.7985 8.3942 11.6038 8 12.5 8C14.1569 8 15.5 9.34315 15.5 11C15.5 12.3072 14.6647 13.4171 13.5 13.829V14C13.5 14.5523 13.0523 15 12.5 15C11.9477 15 11.5 14.5523 11.5 14V13.5C11.5 12.6284 12.1873 12.112 12.7482 11.9692C13.181 11.859 13.5 11.4655 13.5 11C13.5 10.4477 13.0523 10 12.5 10ZM12.5 16C11.9477 16 11.5 16.4477 11.5 17C11.5 17.5523 11.9477 18 12.5 18H12.51C13.0623 18 13.51 17.5523 13.51 17C13.51 16.4477 13.0623 16 12.51 16H12.5Z" fill="#4A90E2"/>
                            </svg>

                    </div>
                    <h3 class="text-gray-600 font-semibold">Pending Queries</h3>
                </div>
                <h2 class="text-2xl font-bold mt-2">51,801</h2>
                <p class="text-red-500 text-sm">⬇ 3% in the last 1 month</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-10">
            <!-- Sales Data Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <h2 class="text-lg font-semibold">Sales Data</h2>
                <canvas id="salesChart" class="mt-4"></canvas>
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center cursor-pointer" id="toggleThisMonth">
                        <span class="w-3 h-3 bg-blue-500 rounded-full inline-block mr-2"></span>
                        <span class="text-gray-600 text-sm">This month</span>
                    </div>
                    <div class="flex items-center cursor-pointer" id="togglePrevMonth">
                        <span class="w-3 h-3 bg-black rounded-full inline-block mr-2"></span>
                        <span class="text-gray-600 text-sm">Prev month</span>
                    </div>
                </div>
            </div>

            <!-- Top Selling Stores Table -->
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Top Selling Stores</h2>
                    <div class="relative">

                        <button id="dropdownDefaultButton2" data-dropdown-toggle="dropdown2"
                            class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button">This Month <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdown2"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton2">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">This Month</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Previous Month</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <table class="w-full mt-4">
                    <thead>
                        <tr class="text-gray-600 text-sm border-b">
                            <th class="text-left py-2">No.</th>
                            <th class="text-left py-2">Store</th>
                            <th class="text-left py-2">Earning</th>
                            <th class="text-left py-2">Item Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-2 py-2">1</td>
                            <td class="px-2 py-2 flex items-center space-x-3">
                                <img src="{{ asset('asset/Ellipse 2.png') }}" class="w-10 h-10 rounded-md"
                                    alt="Store Logo">
                                <div>
                                    <p class="font-semibold">Store Name</p>
                                </div>
                            </td>
                            <td class="px-2 py-2 text-blue-500 font-semibold">$35</td>
                            <td class="px-2 py-2 text-gray-600">498 pcs</td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-2 py-2">2</td>
                            <td class="px-2 py-2 flex items-center space-x-3">
                                <img src="{{ asset('asset/Ellipse 2.png') }}" class="w-10 h-10 rounded-md"
                                    alt="Store Logo">
                                <div>
                                    <p class="font-semibold">Store Name</p>
                                </div>
                            </td>
                            <td class="px-2 py-2 text-blue-500 font-semibold">$55</td>
                            <td class="px-2 py-2 text-gray-600">367 pcs</td>
                        </tr>
                        <tr>
                            <td class="px-2 py-2">3</td>
                            <td class="px-2 py-2 flex items-center space-x-3">
                                <img src="{{ asset('asset/Ellipse 2.png') }}" class="w-10 h-10 rounded-md"
                                    alt="Store Logo">
                                <div>
                                    <p class="font-semibold">Store Name</p>
                                </div>
                            </td>
                            <td class="px-2 py-2 text-blue-500 font-semibold">$48</td>
                            <td class="px-2 py-2 text-gray-600">255 pcs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-10">
            <!-- Top Selling Stores Table -->
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Latest Orders</h2>
                    <div class="relative">

                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                            class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button">Today <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdown"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <table class="w-full mt-4">
                    <thead>
                        <tr class="text-gray-600 text-sm border-b">
                            <th class="text-left py-3 px-4">Customer</th>
                            <th class="text-left py-3 px-4">Product</th>
                            <th class="text-left py-3 px-4">Seller</th>
                            <th class="text-left py-3 px-4">Qty</th>
                            <th class="text-left py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-4 py-3">John Doe</td>
                            <td class="px-4 py-3 flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold">Product A</p>
                                    <p class="text-sm text-gray-500">Category 1</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-blue-500 font-semibold">Seller 1</td>
                            <td class="px-4 py-3 text-gray-600">10 pcs</td>
                            <td class="px-4 py-3">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Delivered
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-4 py-3">Jane Smith</td>
                            <td class="px-4 py-3 flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold">Product B</p>
                                    <p class="text-sm text-gray-500">Category 2</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-blue-500 font-semibold">Seller 2</td>
                            <td class="px-4 py-3 text-gray-600">5 pcs</td>
                            <td class="px-4 py-3">
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Pending
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Michael Lee</td>
                            <td class="px-4 py-3 flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold">Product C</p>
                                    <p class="text-sm text-gray-500">Category 3</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-blue-500 font-semibold">Seller 3</td>
                            <td class="px-4 py-3 text-gray-600">20 pcs</td>
                            <td class="px-4 py-3">
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Cancelled
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border">
                <h2 class="text-lg font-semibold">Sales Data</h2>
                <div class="map-container">
                    <h2>Segmentation</h2>
                    <canvas id="worldMap"></canvas>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-geo"></script>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                        label: 'This Month',
                        data: [2200, 1500, 2300, 1700, 2100, 900, 2500],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 5,
                        hidden: false // Default visible
                    },
                    {
                        label: 'Prev Month',
                        data: [1800, 1100, 1500, 1300, 1600, 700, 2000],
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        borderRadius: 5,
                        hidden: false // Default visible
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Toggle visibility for "This Month"
        document.getElementById("toggleThisMonth").addEventListener("click", function() {
            salesChart.data.datasets[0].hidden = !salesChart.data.datasets[0].hidden;
            salesChart.update();
        });

        // Toggle visibility for "Prev Month"
        document.getElementById("togglePrevMonth").addEventListener("click", function() {
            salesChart.data.datasets[1].hidden = !salesChart.data.datasets[1].hidden;
            salesChart.update();
        });



        // dropdown

        const dropdownButton = document.getElementById("dropdownButton");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const selectedOption = document.getElementById("selectedOption");

        // Toggle dropdown visibility
        dropdownButton.addEventListener("click", () => {
            dropdownMenu.classList.toggle("hidden");
        });

        // Function to update selected filter
        function selectFilter(option) {
            selectedOption.innerText = option;
            dropdownMenu.classList.add("hidden");

            // You can trigger filtering logic here
            console.log("Filtering data for:", option);
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add("hidden");
            }
        });
    </script>
    <script>
        async function loadWorldMap() {
            const ctx = document.getElementById("worldMap").getContext("2d");

            // Fetch world GeoJSON data
            const countries = await fetch("https://unpkg.com/world-atlas@2/countries-50m.json")
                .then(res => res.json());

            const countryShapes = ChartGeo.topojson.feature(countries, countries.objects.countries).features;

            // Customer data for different countries
            const customerData = [{
                    country: "USA",
                    code: "USA",
                    customers: 4500,
                    lat: 37.0902,
                    lon: -95.7129
                },
                {
                    country: "China",
                    code: "CHN",
                    customers: 12000,
                    lat: 35.8617,
                    lon: 104.1954
                },
                {
                    country: "India",
                    code: "IND",
                    customers: 9000,
                    lat: 20.5937,
                    lon: 78.9629
                },
                {
                    country: "Japan",
                    code: "JPN",
                    customers: 5000,
                    lat: 36.2048,
                    lon: 138.2529
                },
                {
                    country: "Germany",
                    code: "DEU",
                    customers: 3000,
                    lat: 51.1657,
                    lon: 10.4515
                },
                {
                    country: "Brazil",
                    code: "BRA",
                    customers: 2000,
                    lat: -14.2350,
                    lon: -51.9253
                },
            ];

            new Chart(ctx, {
                type: "bubbleMap",
                data: {
                    labels: customerData.map(d => d.country),
                    datasets: [{
                        label: "Customers",
                        data: customerData.map(d => ({
                            feature: countryShapes.find(f => f.id === d.code),
                            r: Math.sqrt(d.customers) / 10, // Adjust bubble size
                        })),
                        backgroundColor: "rgba(59, 130, 246, 0.7)",
                        borderColor: "white",
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    let data = customerData[context.dataIndex];
                                    return `${data.country}: ${data.customers} customers`;
                                }
                            }
                        }
                    },
                    scales: {
                        projection: {
                            axis: "x",
                            projection: "equalEarth"
                        }
                    }
                }
            });
        }

        loadWorldMap();
    </script>
@endsection
