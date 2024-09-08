window.addEventListener("load", function () {
    var edit_form = document.getElementById("edit-form");
    edit_form.addEventListener("submit", function (event) {
        var XHR = new XMLHttpRequest();
        var form_data = new FormData(edit_form);

        // On success
        XHR.addEventListener("load", editSuccess);

        // On error
        XHR.addEventListener("error", on_error);

        // // Set up request
        XHR.open("POST", "edit_profile.php");

        // // Form data is sent with request
        XHR.send(form_data);

        event.preventDefault();
    });
});

var editSuccess = function (event) {
    var response = JSON.parse(event.target.responseText);
    if (response.success) {
        alert(response.message);
        window.location.href = "dashboard.php";
    } else {
        alert(response.message);
    }
};

var on_error = function (event) {
    alert('Oops! Something went wrong.');
};