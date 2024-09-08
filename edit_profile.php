<?php
session_start();
require "includes/database_connect.php";
if (isset($_GET['id'])) {
    extract($_GET);
    $sql = "select * from users where id=" . $id;
    $result = mysqli_query($conn, $sql);
    if (!mysqli_num_rows($result)) {
        $response = array("success" => false, "message" => "Something went wrong!");
        echo json_encode($response);
        return;
    }
    $user = mysqli_fetch_assoc($result);
} else {
    $response = array("success" => false, "message" => "Invalid User. Please try again...");
    echo json_encode($response);
    return;
}

if (isset($_POST['submit'])) {
    extract($_POST);
    $sql = "update users set full_name='$full_name', phone='$phone', email='$email', college_name='$college_name', gender='$gender' where id=" . $_GET['id'];
    $result2 = mysqli_query($conn, $sql);

    if (!mysqli_num_rows($result)) {
        $response = array("success" => false, "message" => "User not updated. Please try again...");
        echo json_encode($response);
        return;
    } else {
        $response = array("success" => true, "message" => "Your account has been updated successfully!");
        echo json_encode($response);
        mysqli_close($conn);
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <?php
    include "includes/head_links.php";
    ?>

    <link rel="stylesheet" href="./CSS/edit_profile.css">

</head>

<body>

    <?php
    include "includes/header.php";
    ?>

    <div>
        <h5 class="modal-title" id="signup-heading">Edit Profile</h5>
        <form id="edit-form" class="form" role="form" method="post" action="edit_profile.php?id=<?= $_GET['id'] ?>">
            <div class="input-group form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                </div>
                <input type="text" class="form-control" name="full_name" placeholder="Full Name" maxlength="30" value="<?= $user['full_name'] ?>">
            </div>

            <div class="input-group form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-phone-alt"></i>
                    </span>
                </div>
                <input type="text" class="form-control" name="phone" placeholder="Phone Number" maxlength="10" minlength="10" value="<?= $user['phone'] ?>">
            </div>

            <div class="input-group form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?= $user['email'] ?>">
            </div>

            <div class="input-group form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-university"></i>
                    </span>
                </div>
                <input type="text" class="form-control" name="college_name" placeholder="College Name" maxlength="150" value="<?= $user['college_name'] ?>">
            </div>

            <div class="form-group">
                <span>I'm a</span>
                <input type="radio" class="ml-3" id="gender-male" name="gender" value="male" required />
                <label for="gender-male">
                    Male
                </label>
                <input type="radio" class="ml-3" id="gender-female" name="gender" value="female" required />
                <label for="gender-female">
                    Female
                </label>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-block btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <!-- <script src="./js/edit_profile.js"></script> -->

</body>

</html>