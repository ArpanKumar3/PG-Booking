<?php
session_start();
require "includes/database_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    die();
}
$user_id = $_SESSION['user_id'];

$sql_1 = "SELECT * FROM users WHERE id = $user_id";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo "Something went wrong!";
    return;
}
$user = mysqli_fetch_assoc($result_1);
if (!$user) {
    echo "Something went wrong!";
    return;
}

$sql_2 = "SELECT * 
            FROM interested_users_properties iup
            INNER JOIN properties p ON iup.property_id = p.id
            WHERE iup.user_id = $user_id";
$result_2 = mysqli_query($conn, $sql_2);
if (!$result_2) {
    echo "Something went wrong!";
    return;
}
$interested_properties = mysqli_fetch_all($result_2, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <?php
    include "includes/head_links.php";
    ?>

    <link rel="stylesheet" href="./CSS/dashboard.css" />
</head>
<body>
  <?php
    include "includes/header.php";
    ?>

      <div aria-label="breadcrumb">
        <ol class="breadcrumb bread-dashboard">
          <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>

      <div class="profile-container">
        <h1 class="profile-heading">My Profile</h1>
        <div class="row" style="align-items: center;">
        <div class="profile-icon col-md-4">
            <i class="fa fa-user profile-img rounded-circle"></i>
        </div>
        <div class="profile-details row col-md-8">
            <div class="row details">
                <strong ><?= $user['full_name'] ?></strong>
                <span ><?= $user['email'] ?></span>
                <span ><?= $user['phone'] ?></span>
                <span ><?= $user['college_name'] ?></span>
            </div>
            <div class="profile-edit">
                <span><a href="edit_profile.php?id=<?= $user['id'] ?>" class="edit-text">Edit Profile</a></span>
            </div>
        </div>
      </div>
      </div>

      <?php
    if (count($interested_properties) > 0) {
    ?>
      <div class="fav-property-container">
        <h2 class="fav-prop-heading">My Interested Properties</h2>
        <div class="fav-prop-card">

        <?php
                foreach ($interested_properties as $property) {
                    $property_images = glob("img/properties/" . $property['id'] . "/*");
                ?>
        <div class="card-property row property-id-<?= $property['id'] ?>">
          <div class="col-md-4">
            <img src="<?= $property_images[0] ?>" class="card-img-top" alt="fav1">
          </div>
            <div class="details-body col-md-8">
              <div class="row rate">
              <?php
                                $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                                $total_rating = round($total_rating, 1);
                                ?>
                <div class="col-10" title="<?= $total_rating ?>">
                <?php
                                    $rating = $total_rating;
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($rating >= $i + 0.8) {
                                    ?>
                                            <i class="fa-solid fa-star"></i>
                                        <?php
                                        } elseif ($rating >= $i + 0.3) {
                                        ?>
                                             <i class="fa-solid fa-star-half-stroke"></i>
                                        <?php
                                        } else {
                                        ?>
                                            <i class="fa-regular fa-star"></i>
                                    <?php
                                        }
                                    }
                                    ?>
                </div>
                <div class="col-2">
                  <span><i class="fa fa-heart" property_id="<?= $property['id'] ?>"></i></span>
                </div>
              </div>
              <h5 class="card-title"><?= $property['name'] ?></h5>
              <p class="card-text">P<?= $property['address'] ?></p>
              <div class="gender">
              <?php
                  if ($property['gender'] == "male") {
                  ?>
                      <img src="img/male.png" class="unisex">
                  <?php
                  } elseif ($property['gender'] == "female") {
                  ?>
                      <img src="img/female.png" class="unisex">
                  <?php
                  } else {
                  ?>
                      <img src="img/unisex.png" class="unisex">
                   <?php
                  }
                  ?>
              </div>
              <div class="row rate-prop">
                <div class="col-8 price"><strong>₹ <?= number_format($property['rent']) ?>/-</strong> per month
                </div>
                <div class="col-4 view"><a href="property_detail.php?property_id=<?= $property['id'] ?>" class="btn btn-info">View</a>
                </div>
              </div>
            </div>
          </div>
          <?php
                }
                ?>
        </div>
      </div>
      <?php
    }
    ?>


      <?php
      include "includes/footer.php";
      ?>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>