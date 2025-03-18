function previewFile(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = event.target.closest('.file-upload-label').querySelector('.file-preview');
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
$(document).ready(function () {
    // Post Request Delete Data variable

    // submit btn loading spinner show function
    function BtnSpinnerShow() {
        $("#btnSpinner").removeClass("hidden");
        $("#btnText").addClass("hidden");
        $("#submitBtn").attr("disabled", true);
    }

    // submit btn loading spinner hide function
    function BtnSpinnerHide() {
        $("#btnSpinner").addClass("hidden");
        $("#btnText").removeClass("hidden");
        $("#submitBtn").attr("disabled", false);
    }

    // Success response sweet alert
    function SuccessAlert(message) {
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Success",
            text: message,
            showConfirmButton: false,
            timer: 2000,
        });
    }
    // Error response sweet alert

    function WarningAlert(message) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Error",
            text: message,
            showConfirmButton: false,
            timer: 2000,
        });
    }

    // Reload dataTable function
    function reloadDataTable() {
        // Destroy the existing DataTable instance
        let table = $("#datatable").DataTable();
        table.destroy();
        $("#loading").show();

        // Reload the table body content
        $("#tableBody").load(" #tableBody > *", function () {
            // Reinitialize DataTable after loading new data
            delDataFun();
            updateDatafun();
            $("#datatable").DataTable();
            $("#loading").hide();
        });
    }

    function delDataFun() {
        $(".deleteDataBtn").click(function () {
            let csrfToken = $('meta[name="csrf-token"]').attr("content");
            let id = $(this).attr("delId");
            let dynamicKey = $(this).attr("name");
            let delData = {};
            delData[dynamicKey] = id;
            // Show SweetAlert  confirmation dialog
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#D42929FF",
                cancelButtonColor: "gray",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with AJAX request to delete
                    $.ajax({
                        type: "POST",
                        url: $(this).attr("delUrl"),
                        data: delData,
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        beforeSend: function () {
                            $("#loading").show();
                        },
                        success: function (response) {
                            $("#loading").hide();
                            reloadDataTable();

                            const alert = Swal.fire({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                            });

                            $(document).trigger("formSubmissionResponse", [
                                response,
                                alert,
                            ]);
                        },
                        error: function (xhr) {
                            $("#loading").hide();
                            Swal.fire({
                                title: "Error!",
                                text: "There was an error deleting data.",
                                icon: "error",
                            });
                        },
                    });
                }
            });
        });
    }

    delDataFun();

    // post data ajax request
    $("#postDataForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: $(this).attr("url"),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                BtnSpinnerShow();
            },
            success: function (response) {
                $("#postDataForm")[0].reset();
                BtnSpinnerHide();
                // Reload the table body content
                $(document).trigger("formSubmissionResponse", [
                    response,
                    SuccessAlert(response.message),
                ]);
                reloadDataTable();
            },

            error: function (jqXHR) {
                BtnSpinnerHide();
                let response = JSON.parse(jqXHR.responseText);

                $(document).trigger("formSubmissionResponse", [
                    response,
                    WarningAlert(response.message),
                ]);
            },
        });
    });
    $(".dataTable").on("draw", function () {
        updateDatafun();
        delDataFun();
    });
});

$(document).ready(function(){
    $("#myFormNew").submit(function(e){
        e.preventDefault(); // Prevent default form submission

        let form = $(this);
        let actionUrl = form.attr('action'); // Get form action URL
        let formData = new FormData(this); // Properly create FormData object

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: formData,
            contentType: false, // Important for FormData
            processData: false, // Prevent jQuery from processing data
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success"
                    });
                    form[0].reset(); // Reset form after success
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error"
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = "An error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join("\n");
                }
                Swal.fire({
                    title: "Validation Error!",
                    text: errorMessage,
                    icon: "warning"
                });
            }
        });
    });
});

