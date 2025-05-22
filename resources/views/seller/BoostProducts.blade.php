@extends('layout')
@section('title', 'Boost Products')
@section('nav-title', 'Boost Products')
@section('content')
    <div class="w-full pt-10 pb-10 min-h-[86vh]   rounded-lg custom-shadow">
        @if ($packageStatus == 'approved')
            <div class="px-4 md:px-8 tab-pane fade" id="profile-change-password">
                <div class="relative w-full min-h-[400px] bg-cover bg-center rounded-lg"
                    style="background-image: url('{{ asset('asset/Rectanglesettings.png') }}');">
                    <div
                        class="absolute inset-0 flex flex-col md:flex-row justify-center md:justify-evenly items-center gap-6 p-4 md:p-8">
                        <!-- Member Since -->
                        <div class="text-center md:text-left">
                            <h3 class="text-lg sm:text-xl md:text-3xl font-bold text-customBlack leading-snug">
                                Member since <br>
                                <span
                                    class="block">{{ \Carbon\Carbon::parse($packageDetail['package_start_date'])->format('d M, Y') }}</span>
                            </h3>
                        </div>

                        <!-- Payment Card -->
                        <div class="bg-white py-6 sm:py-8 px-6 sm:px-10 md:px-20 rounded-lg shadow-lg w-full max-w-md">
                            <form>
                                <div class="mb-4">
                                    <h2 class="text-xl sm:text-2xl font-bold">
                                        <span class="text-primary">Rs. 1000</span> / month
                                    </h2>
                                </div>

                                <div class="mb-4">
                                    <p class="text-gray-600 text-sm sm:text-base">Next payment</p>
                                    <p class="font-bold text-base sm:text-lg">
                                        {{ \Carbon\Carbon::parse($packageDetail['package_end_date'])->format('d M, Y') }}
                                    </p>
                                </div>

                                <button type="button"
                                    class="w-full text-sm sm:text-base bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                                    View Invoice
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <h3 class="mx-6 text-2xl font-semibold">
                Buy Package
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2  p-6 rounded-lg w-full  gap-6">
                <!-- Left Side (Benefits List) -->
                <div>
                    <h3 class="text-lg font-semibold">Benefits you will get:</h3>
                    <ul class="space-y-2 mt-3">
                        <li class="flex items-center gap-2 pb-2">
                            <svg width="23" height="25" viewBox="0 0 33 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.121 0C12.9326 0 9.81573 0.919839 7.16465 2.6432C4.51357 4.36656 2.4473 6.81603 1.22714 9.68187C0.00698496 12.5477 -0.312264 15.7012 0.309767 18.7436C0.931799 21.7859 2.46717 24.5805 4.72173 26.7739C6.97629 28.9673 9.84878 30.4611 12.9759 31.0662C16.1031 31.6714 19.3445 31.3608 22.2902 30.1737C25.2359 28.9867 27.7537 26.9764 29.5251 24.3973C31.2965 21.8181 32.242 18.7858 32.242 15.6838C32.242 13.6242 31.825 11.5847 31.0148 9.68187C30.2047 7.77902 29.0172 6.05006 27.5202 4.59368C26.0233 3.1373 24.2461 1.98204 22.2902 1.19386C20.3343 0.405674 18.238 0 16.121 0ZM23.053 11.9354L15.6857 21.3457C15.5355 21.5355 15.3427 21.6893 15.1219 21.7953C14.9012 21.9013 14.6584 21.9567 14.4122 21.9573C14.1673 21.9586 13.9254 21.9056 13.7047 21.8023C13.484 21.6991 13.2904 21.5483 13.1386 21.3613L9.20509 16.4837C9.07489 16.321 8.97891 16.1349 8.92262 15.9361C8.86634 15.7373 8.85085 15.5297 8.87705 15.325C8.90325 15.1204 8.97061 14.9228 9.0753 14.7435C9.17999 14.5642 9.31996 14.4068 9.48721 14.2801C9.82497 14.0243 10.2534 13.9095 10.6781 13.961C10.8885 13.9864 11.0916 14.052 11.2759 14.1538C11.4601 14.2557 11.622 14.3919 11.7522 14.5546L14.3799 17.8168L20.4737 9.9749C20.6028 9.81013 20.764 9.67172 20.9481 9.56757C21.1323 9.46342 21.3357 9.39557 21.5467 9.3679C21.7578 9.34023 21.9724 9.35328 22.1783 9.4063C22.3842 9.45932 22.5774 9.55127 22.7467 9.67691C22.9161 9.80254 23.0583 9.9594 23.1654 10.1385C23.2724 10.3177 23.3422 10.5155 23.3706 10.7209C23.3991 10.9262 23.3857 11.135 23.3312 11.3353C23.2767 11.5356 23.1821 11.7236 23.053 11.8883V11.9354Z"
                                    fill="#4A90E2" />
                            </svg>

                            <span>Lorem ipsum dolor sit amet, consectetur</span>
                        </li>
                        <li class="flex items-center gap-2 pb-2">
                            <svg width="23" height="25" viewBox="0 0 33 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.121 0C12.9326 0 9.81573 0.919839 7.16465 2.6432C4.51357 4.36656 2.4473 6.81603 1.22714 9.68187C0.00698496 12.5477 -0.312264 15.7012 0.309767 18.7436C0.931799 21.7859 2.46717 24.5805 4.72173 26.7739C6.97629 28.9673 9.84878 30.4611 12.9759 31.0662C16.1031 31.6714 19.3445 31.3608 22.2902 30.1737C25.2359 28.9867 27.7537 26.9764 29.5251 24.3973C31.2965 21.8181 32.242 18.7858 32.242 15.6838C32.242 13.6242 31.825 11.5847 31.0148 9.68187C30.2047 7.77902 29.0172 6.05006 27.5202 4.59368C26.0233 3.1373 24.2461 1.98204 22.2902 1.19386C20.3343 0.405674 18.238 0 16.121 0ZM23.053 11.9354L15.6857 21.3457C15.5355 21.5355 15.3427 21.6893 15.1219 21.7953C14.9012 21.9013 14.6584 21.9567 14.4122 21.9573C14.1673 21.9586 13.9254 21.9056 13.7047 21.8023C13.484 21.6991 13.2904 21.5483 13.1386 21.3613L9.20509 16.4837C9.07489 16.321 8.97891 16.1349 8.92262 15.9361C8.86634 15.7373 8.85085 15.5297 8.87705 15.325C8.90325 15.1204 8.97061 14.9228 9.0753 14.7435C9.17999 14.5642 9.31996 14.4068 9.48721 14.2801C9.82497 14.0243 10.2534 13.9095 10.6781 13.961C10.8885 13.9864 11.0916 14.052 11.2759 14.1538C11.4601 14.2557 11.622 14.3919 11.7522 14.5546L14.3799 17.8168L20.4737 9.9749C20.6028 9.81013 20.764 9.67172 20.9481 9.56757C21.1323 9.46342 21.3357 9.39557 21.5467 9.3679C21.7578 9.34023 21.9724 9.35328 22.1783 9.4063C22.3842 9.45932 22.5774 9.55127 22.7467 9.67691C22.9161 9.80254 23.0583 9.9594 23.1654 10.1385C23.2724 10.3177 23.3422 10.5155 23.3706 10.7209C23.3991 10.9262 23.3857 11.135 23.3312 11.3353C23.2767 11.5356 23.1821 11.7236 23.053 11.8883V11.9354Z"
                                    fill="#4A90E2" />
                            </svg>

                            <span>Lorem ipsum dolor sit amet, consectetur</span>
                        </li>
                        <li class="flex items-center gap-2 pb-2">
                            <svg width="23" height="25" viewBox="0 0 33 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.121 0C12.9326 0 9.81573 0.919839 7.16465 2.6432C4.51357 4.36656 2.4473 6.81603 1.22714 9.68187C0.00698496 12.5477 -0.312264 15.7012 0.309767 18.7436C0.931799 21.7859 2.46717 24.5805 4.72173 26.7739C6.97629 28.9673 9.84878 30.4611 12.9759 31.0662C16.1031 31.6714 19.3445 31.3608 22.2902 30.1737C25.2359 28.9867 27.7537 26.9764 29.5251 24.3973C31.2965 21.8181 32.242 18.7858 32.242 15.6838C32.242 13.6242 31.825 11.5847 31.0148 9.68187C30.2047 7.77902 29.0172 6.05006 27.5202 4.59368C26.0233 3.1373 24.2461 1.98204 22.2902 1.19386C20.3343 0.405674 18.238 0 16.121 0ZM23.053 11.9354L15.6857 21.3457C15.5355 21.5355 15.3427 21.6893 15.1219 21.7953C14.9012 21.9013 14.6584 21.9567 14.4122 21.9573C14.1673 21.9586 13.9254 21.9056 13.7047 21.8023C13.484 21.6991 13.2904 21.5483 13.1386 21.3613L9.20509 16.4837C9.07489 16.321 8.97891 16.1349 8.92262 15.9361C8.86634 15.7373 8.85085 15.5297 8.87705 15.325C8.90325 15.1204 8.97061 14.9228 9.0753 14.7435C9.17999 14.5642 9.31996 14.4068 9.48721 14.2801C9.82497 14.0243 10.2534 13.9095 10.6781 13.961C10.8885 13.9864 11.0916 14.052 11.2759 14.1538C11.4601 14.2557 11.622 14.3919 11.7522 14.5546L14.3799 17.8168L20.4737 9.9749C20.6028 9.81013 20.764 9.67172 20.9481 9.56757C21.1323 9.46342 21.3357 9.39557 21.5467 9.3679C21.7578 9.34023 21.9724 9.35328 22.1783 9.4063C22.3842 9.45932 22.5774 9.55127 22.7467 9.67691C22.9161 9.80254 23.0583 9.9594 23.1654 10.1385C23.2724 10.3177 23.3422 10.5155 23.3706 10.7209C23.3991 10.9262 23.3857 11.135 23.3312 11.3353C23.2767 11.5356 23.1821 11.7236 23.053 11.8883V11.9354Z"
                                    fill="#4A90E2" />
                            </svg>

                            <span>Lorem ipsum dolor sit amet, consectetur</span>
                        </li>
                        <li class="flex items-center gap-2 pb-2">
                            <svg width="23" height="25" viewBox="0 0 33 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.121 0C12.9326 0 9.81573 0.919839 7.16465 2.6432C4.51357 4.36656 2.4473 6.81603 1.22714 9.68187C0.00698496 12.5477 -0.312264 15.7012 0.309767 18.7436C0.931799 21.7859 2.46717 24.5805 4.72173 26.7739C6.97629 28.9673 9.84878 30.4611 12.9759 31.0662C16.1031 31.6714 19.3445 31.3608 22.2902 30.1737C25.2359 28.9867 27.7537 26.9764 29.5251 24.3973C31.2965 21.8181 32.242 18.7858 32.242 15.6838C32.242 13.6242 31.825 11.5847 31.0148 9.68187C30.2047 7.77902 29.0172 6.05006 27.5202 4.59368C26.0233 3.1373 24.2461 1.98204 22.2902 1.19386C20.3343 0.405674 18.238 0 16.121 0ZM23.053 11.9354L15.6857 21.3457C15.5355 21.5355 15.3427 21.6893 15.1219 21.7953C14.9012 21.9013 14.6584 21.9567 14.4122 21.9573C14.1673 21.9586 13.9254 21.9056 13.7047 21.8023C13.484 21.6991 13.2904 21.5483 13.1386 21.3613L9.20509 16.4837C9.07489 16.321 8.97891 16.1349 8.92262 15.9361C8.86634 15.7373 8.85085 15.5297 8.87705 15.325C8.90325 15.1204 8.97061 14.9228 9.0753 14.7435C9.17999 14.5642 9.31996 14.4068 9.48721 14.2801C9.82497 14.0243 10.2534 13.9095 10.6781 13.961C10.8885 13.9864 11.0916 14.052 11.2759 14.1538C11.4601 14.2557 11.622 14.3919 11.7522 14.5546L14.3799 17.8168L20.4737 9.9749C20.6028 9.81013 20.764 9.67172 20.9481 9.56757C21.1323 9.46342 21.3357 9.39557 21.5467 9.3679C21.7578 9.34023 21.9724 9.35328 22.1783 9.4063C22.3842 9.45932 22.5774 9.55127 22.7467 9.67691C22.9161 9.80254 23.0583 9.9594 23.1654 10.1385C23.2724 10.3177 23.3422 10.5155 23.3706 10.7209C23.3991 10.9262 23.3857 11.135 23.3312 11.3353C23.2767 11.5356 23.1821 11.7236 23.053 11.8883V11.9354Z"
                                    fill="#4A90E2" />
                            </svg>

                            <span>Lorem ipsum dolor sit amet, consectetur</span>
                        </li>
                        <li class="flex items-center gap-2 pb-2">
                            <svg width="23" height="25" viewBox="0 0 33 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.121 0C12.9326 0 9.81573 0.919839 7.16465 2.6432C4.51357 4.36656 2.4473 6.81603 1.22714 9.68187C0.00698496 12.5477 -0.312264 15.7012 0.309767 18.7436C0.931799 21.7859 2.46717 24.5805 4.72173 26.7739C6.97629 28.9673 9.84878 30.4611 12.9759 31.0662C16.1031 31.6714 19.3445 31.3608 22.2902 30.1737C25.2359 28.9867 27.7537 26.9764 29.5251 24.3973C31.2965 21.8181 32.242 18.7858 32.242 15.6838C32.242 13.6242 31.825 11.5847 31.0148 9.68187C30.2047 7.77902 29.0172 6.05006 27.5202 4.59368C26.0233 3.1373 24.2461 1.98204 22.2902 1.19386C20.3343 0.405674 18.238 0 16.121 0ZM23.053 11.9354L15.6857 21.3457C15.5355 21.5355 15.3427 21.6893 15.1219 21.7953C14.9012 21.9013 14.6584 21.9567 14.4122 21.9573C14.1673 21.9586 13.9254 21.9056 13.7047 21.8023C13.484 21.6991 13.2904 21.5483 13.1386 21.3613L9.20509 16.4837C9.07489 16.321 8.97891 16.1349 8.92262 15.9361C8.86634 15.7373 8.85085 15.5297 8.87705 15.325C8.90325 15.1204 8.97061 14.9228 9.0753 14.7435C9.17999 14.5642 9.31996 14.4068 9.48721 14.2801C9.82497 14.0243 10.2534 13.9095 10.6781 13.961C10.8885 13.9864 11.0916 14.052 11.2759 14.1538C11.4601 14.2557 11.622 14.3919 11.7522 14.5546L14.3799 17.8168L20.4737 9.9749C20.6028 9.81013 20.764 9.67172 20.9481 9.56757C21.1323 9.46342 21.3357 9.39557 21.5467 9.3679C21.7578 9.34023 21.9724 9.35328 22.1783 9.4063C22.3842 9.45932 22.5774 9.55127 22.7467 9.67691C22.9161 9.80254 23.0583 9.9594 23.1654 10.1385C23.2724 10.3177 23.3422 10.5155 23.3706 10.7209C23.3991 10.9262 23.3857 11.135 23.3312 11.3353C23.2767 11.5356 23.1821 11.7236 23.053 11.8883V11.9354Z"
                                    fill="#4A90E2" />
                            </svg>

                            <span>Lorem ipsum dolor sit amet, consectetur</span>
                        </li>
                    </ul>
                </div>

                <!-- Right Side (Account Details & Upload) -->
                <div class="md:pl-6">
                    <h3 class="text-lg font-semibold">Account Details</h3>
                    <p class="text-gray-700 mt-2">Send 20% Payment in the following Bank Account:</p>

                    <!-- Table for Account Details -->
                    <table class="w-full mt-2 border-collapse">
                        <tbody>
                            <tr>
                                <td class="w-[25%] font-semibold py-2 pr-3">Account Name:</td>
                                <td>
                                    <span class="px-2 py-1">
                                        HiBuy0
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-[25%] font-semibold py-2 pr-3">Account Number:</td>
                                <td class="flex items-center gap-2">
                                    <span class="text-primary  px-2 py-1" id="accountNumber">
                                        1234567890
                                    </span>
                                    <button onclick="copyToClipboard()"
                                        class="bg-gray-200 px-2 py-1 text-sm rounded hover:bg-gray-300">
                                        Copy
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-[25%] font-semibold py-2 pr-3">Bank Name:</td>
                                <td>
                                    <span class="px-2 py-1">
                                        Bank Name
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="mt-4 font-semibold">Attach screenshot after sending payment.</p>

                    <form id="transactionImageForm" method="POST" action="/save-transaction-image"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center mt-2 gap-2">
                            <input type="file" name="transaction_image" class="border rounded px-2 text-sm"
                                accept="image/*">

                            @if (in_array($packageStatus, ['approved', 'pending']))
                                <span class="bg-blue-500 text-white px-4 py-2 rounded">Loading...</span>
                            @else
                                <button type="submit" id="submitBtn"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Done</button>
                            @endif
                        </div>

                    </form>


                </div>
            </div>
        @endif
    </div>

@endsection
@section('js')
    <script>
        function copyToClipboard() {
            const text = document.getElementById("accountNumber").innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("Account Number copied!");
            }).catch(err => {
                console.error("Failed to copy: ", err);
            });
        }

        $(document).ready(function() {

            $('#transactionImageForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                // $('#submitBtn').prop('disabled', true).text('Uploading...');

                $.ajax({
                    url: '/save-transaction-image',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Image uploaded successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        location.reload();
                        $('#transactionImage').val('');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            html: errorMessage
                        });
                    }
                });
            });
        });
    </script>



@endsection
