@extends('Auth.layout')
@section('title', 'Login')
@section('content')
    <div
        class="w-full max-w-sm p-10 mx-auto mt-5 bg-white shadow-lg rounded-tr-[40px] rounded-tl-[100px] rounded-br-[100px] rounded-bl-[20px] lg:px-6 lg:py-20 lg:max-w-lg">
        <h2 class="text-4xl font-medium text-center">Forgot Password?</h2>
        <p class="mt-3 text-sm font-medium text-center">Enter your Email For Reset Your Password</p>

        <form id="forgotForm" action="{{ route('forgot.password') }}" method="POST">
            @csrf
            <div class="mt-4">
                <x-input id="email" value="" label="Email" placeholder="Enter Email" name="user_email"
                    type="email" />
                @error('user_email')
                    <span class="text-sm text-red-500"> {{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6">
                <button id="submitBtn" class="w-full px-4 py-2 font-semibold text-white rounded-md bg-primary"
                    type="submit">
                    <div id="btnSpinner" class="hidden flex gap-2 items-center mx-auto justify-center z-30">
                        <svg aria-hidden="true"
                            class="w-5 h-5 text-center text-gray-200 animate-spin fill-customOrangeLight"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                    </div>
                    <div id="btnText">Submit</div>
                </button>
            </div>
        </form>
        <div class="flex justify-center mt-6 text-sm sm:text-base">
            <p class="text-gray-700">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline ml-1">
                    Login here
                </a>
            </p>
        </div>

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $("#forgotForm").submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                console.log(formData);


                $.ajax({
    type: "POST",
    url: $(this).attr('action'),
    data: formData,
    dataType: "json",
    beforeSend: function() {
        $("#btnSpinner").removeClass("hidden");
        $("#btnText").addClass("hidden");
        $("#submitBtn").attr("disabled", true);
        $(".error-text").remove();
        $("input").removeClass("border-red-500");
    },
    success: function(response) {
        $("#btnSpinner").addClass("hidden");
        $("#btnText").removeClass("hidden");
        $("#submitBtn").attr("disabled", false);

        $("#forgotForm")[0].reset();

        Swal.fire({
            icon: "success",
            title: "Email Sent",
            text: response.message,
            timer: 2000,
            showConfirmButton: false
        });
    },
    error: function(jqXHR) {
        $("#btnSpinner").addClass("hidden");
        $("#btnText").removeClass("hidden");
        $("#submitBtn").attr("disabled", false);

        let response = JSON.parse(jqXHR.responseText);

        if (jqXHR.status === 403 && response.message) {
            // Role check failed
            Swal.fire({
                icon: "error",
                title: "Access Denied",
                text: response.message
            });
        } else if (response.errors) {
            $.each(response.errors, function(key, value) {
                let inputField = $(`input[name=${key}]`);
                inputField.addClass("border-red-500");
                inputField.after(
                    `<p class="error-text text-red-500 text-sm mt-1">${value}</p>`
                );
            });

            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: response.message,
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: response.message || "Something went wrong. Please try again!"
            });
        }
    }
});
 });

            $("input").on("input", function() {
                $(this).removeClass("border-red-500");
                $(this).next(".error-text").remove();
            });
        });
    </script>

@endsection
