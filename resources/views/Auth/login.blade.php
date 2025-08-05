@extends('Auth.layout')
@section('title', 'Login')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <div
        class="w-full max-w-sm  p-4 mx-auto mt-5 bg-white shadow-lg rounded-tr-[40px] rounded-tl-[100px]  rounded-br-[100px]  rounded-bl-[20px] lg:px-6 lg:py-20  lg:max-w-lg">
        <h2 class="text-4xl font-medium text-center ">LOG IN</h2>
        <p class="mt-3 text-sm font-medium text-center">Fill in your details below and Sign up</p>

        <form id="loginForm">
            @csrf
            <div class="mt-4">
                <x-input id="email" value="" label="Email" placeholder="Enter Email" name='user_email'
                    type="email"></x-input>
            </div>
            <div class=" mt-6">
                <div class="relative">
                    <x-input id="mediaTitle" value="" label="Password" placeholder="Enter Password"
                        name='user_password' type="password"></x-input>

                    <span class="absolute right-4 top-11 transform -translate-y-1/2 cursor-pointer">
                        <i class="fa-solid fa-eye-slash text-customGrayColorDark"></i>
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <button class="w-full px-4 py-2 font-semibold text-white rounded-md bg-primary">
                    <div id="btnSpinner" class="hidden flex gap-2 items-center mx-auto justify-center  z-30 ">
                        <svg aria-hidden="true"
                            class="w-5 h-5  text-center text-gray-200 animate-spin fill-customOrangeLight"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>

                    </div>
                    <div id="btnText" class="">
                        Log In
                    </div>
                </button>
            </div>
        </form>
        <div class="flex justify-center mt-6">
            <h1 class="">Forgot Password?
                <a class="text-primary font-bold ml-2" href="{{ route('forgot_Password') }}">Reset here</a>
            </h1>
        </div>
        {{-- <h1 class="text-center mt-3">Join Us
            <a href="{{ route('signup') }}" class="text-primary font-bold ml-2">Create
                Store</a>
        </h1> --}}
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $('.fa-eye-slash').on('click', function () {
            let $icon = $(this);
            let $input = $icon.closest('div').find('input');
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

        $("#loginForm").submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            const url = "../login";

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: "json",
                beforeSend: function () {
                    $("#btnSpinner").removeClass("hidden");
                    $("#btnText").addClass("hidden");
                    $("#submitBtn").attr("disabled", true);
                    $(".error-text").remove();
                    $("input").removeClass("border-red-500");
                },
                success: function (response) {
                    $("#btnSpinner").addClass("hidden");
                    $("#btnText").removeClass("hidden");
                    $("#submitBtn").attr("disabled", false);

                    Swal.fire({
                        icon: "success",
                        title: "Login Successful",
                        text: "Redirecting...",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        if (response.user.user_role === 'admin' || response.seller_status === 'approved') {
                            window.location.href = "../";
                        } else {
                            window.location.href = "../create-profile";
                        }
                    });
                },
                error: function (jqXHR) {
                    $("#btnSpinner").addClass("hidden");
                    $("#btnText").removeClass("hidden");
                    $("#submitBtn").attr("disabled", false);

                    let response = JSON.parse(jqXHR.responseText);

                    if (response.status === 'disabled') {
                        Swal.fire({
                            icon: "warning",
                            title: "Account Disabled",
                            text: response.message + (response.disabled_reason ? ` (${response.disabled_reason})` : '')
                        });
                    } else if (response.errors) {
                        $.each(response.errors, function (key, value) {
                            let inputField = $(`input[name=${key}]`);
                            inputField.addClass("border-red-500");
                            inputField.after(
                                `<p class="error-text text-red-500 text-sm mt-1">${value}</p>`
                            );
                        });

                        Swal.fire({
                            icon: "error",
                            title: "Login Failed",
                            text: response.message || 'Please check your input.'
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Login Failed",
                            text: "Something went wrong. Please try again!"
                        });
                    }
                }
            });
        });

        $("input").on("input", function () {
            $(this).removeClass("border-red-500");
            $(this).next(".error-text").remove();
        });
    });
</script>
@endsection

