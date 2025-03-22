function previewFile(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewImg = event.target
                .closest(".file-upload-label")
                .querySelector(".file-preview");
            previewImg.src = e.target.result;
            previewImg.classList.remove("hidden");
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
            let delurl = $(this).attr("delurl");

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
                        type: "GET",
                        url: delurl,
                        beforeSend: function () {
                            $("#loading").show();
                        },
                        success: function (response) {
                            // $("#loading").hide();
                            // reloadDataTable();
                            window.location.reload();
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

$(document).ready(function () {
    $(".myFormNew").submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        let form = $(this);
        let actionUrl = form.attr("action"); // Get form action URL
        let formData = new FormData(this); // Properly create FormData object
        let currentTabButton = form
            .closest(".tab-content")
            .prev()
            .find("button[aria-selected='true']"); // Find active tab button
        let nextTabListItem = currentTabButton
            .parent()
            .nextAll()
            .filter(function () {
                return (
                    $(this).find("button").length > 0 &&
                    !$(this).find("button").prop("disabled")
                );
            })
            .first(); // Find the next enabled tab

        let nextTabButton = nextTabListItem.find("button");

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: formData,
            contentType: false, // Important for FormData
            processData: false, // Prevent jQuery from processing data
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                    });

                    form[0].reset(); // Reset form after success
                    alert(nextTabButton.length);
                    if (nextTabButton.length > 0) {
                        // Enable and move to next tab
                        nextTabButton.prop("disabled", false); // Remove disabled property
                        nextTabButton.removeAttr("disabled"); // Ensure attribute is removed
                        nextTabButton.removeClass("cursor-not-allowed"); // Remove any styling that prevents clicking

                        // Move to next tab
                        nextTabButton.click();
                    } else {
                        // Last tab is filled, so check if all are complete before redirecting
                        if ("{{ $tabsStatus['business'] }}" === "1") {
                            setTimeout(function () {
                                window.location.href = "/your-new-route"; // Replace with actual route
                            }, 2000);
                        }
                    }
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                    });
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join(
                        "\n"
                    );
                }
                Swal.fire({
                    title: "Validation Error!",
                    text: errorMessage,
                    icon: "warning",
                });
            },
        });
    });
});

// $(document).ready(function(){
//     $(".myFormNew").submit(function(e){
//         e.preventDefault(); // Prevent default form submission

//         let form = $(this);
//         let actionUrl = form.attr('action'); // Get form action URL
//         let formData = new FormData(this); // Properly create FormData object

//         $.ajax({
//             url: actionUrl,
//             type: "POST",
//             data: formData,
//             contentType: false, // Important for FormData
//             processData: false, // Prevent jQuery from processing data
//             dataType: "json",            success: function(response) {
//                 if (response.success) {
//                     Swal.fire({
//                         title: "Success!",
//                         text: response.message,
//                         icon: "success"
//                     });
//                     form[0].reset(); // Reset form after success
//                 } else {
//                     Swal.fire({
//                         title: "Error!",
//                         text: response.message,
//                         icon: "error"
//                     });
//                 }
//             },
//             error: function(xhr) {
//                 let errorMessage = "An error occurred.";
//                 if (xhr.responseJSON && xhr.responseJSON.errors) {
//                     errorMessage = Object.values(xhr.responseJSON.errors).join("\n");
//                 }
//                 Swal.fire({
//                     title: "Validation Error!",
//                     text: errorMessage,
//                     icon: "warning"
//                 });
//             }
//         });
//     });
// });
