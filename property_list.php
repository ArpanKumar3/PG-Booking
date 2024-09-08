<?php
session_start();
require "includes/database_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

$city_name = $_GET["city"];


$sql_1 = "SELECT * FROM cities WHERE name = '$city_name'";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo "Something went wrong!";
    return;
}
$city = mysqli_fetch_assoc($result_1);
if (!$city) {
    echo "Sorry! We do not have any PG listed in this city.";
    return;
}
$city_id = $city['id'];

$sql_2 = "SELECT * FROM properties WHERE city_id = $city_id";
$result_2 = mysqli_query($conn, $sql_2);
if (!$result_2) {
    echo "Something went wrong!";
    return;
}
$properties = mysqli_fetch_all($result_2, MYSQLI_ASSOC);

$sql_3 = "SELECT * 
            FROM interested_users_properties iup
            INNER JOIN properties p ON iup.property_id = p.id
            WHERE p.city_id = $city_id";

$result_3 = mysqli_query($conn, $sql_3);
if (!$result_3) {
    echo "Something went wrong!";
    return;
}
$interested_users_properties = mysqli_fetch_all($result_3, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Property List</title>

    <?php
    include "includes/head_links.php";
    ?>

    <link rel="stylesheet" href="./CSS/property_list.css" />
</head>

<body>
    <?php
    include "includes/header.php";
    ?>

    <?php
    include "includes/signup_modal.php";
    include "includes/login_modal.php";
    ?>

    <div aria-label="breadcrumb">
        <ol class="breadcrumb bread-dashboard">
            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $city_name; ?>
            </li>
        </ol>
    </div>

    <div class="row rent-opt">
        <div class="col-2 filter-con" data-bs-toggle="modal" data-bs-target="#filter-modal">
            <div class="filter">
                <img src="./img/filter.png" alt="filter" class="img-filter rounded-circle">
            </div>
            <span>Filter</span>
        </div>
        <div class="col-5 filter-con">
            <div>
                <img src="./img/asc.png" alt="filter" class="img-filter rounded-circle">
            </div>
            <span>Lowest Rent First</span>
        </div>
        <div class="col-5 filter-con">
            <div>
                <img src="./img/desc.png" alt="filter" class="img-filter rounded-circle">
            </div>
            <span>Highest Rent First</span>
        </div>
    </div>

    <?php
    foreach ($properties as $property) {
        $property_images = glob("img/properties/" . $property['id'] . "/*");
    ?>

        <div class="fav-prop-card">
            <div class="card-property row">
                <div class="col-md-4">
                    <img src="<?= $property_images[0] ?>" class="card-img-top" alt="fav1">
                </div>
                <div class="details-body col-md-8">
                    <div class="row rate">
                        <?php
                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                        $total_rating = round($total_rating, 1);
                        ?>
                        <div class="col-8" title="<?= $total_rating ?>">
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
                        <div class="col-4 fav">
                            <span class="heart">
                                <?php
                                $interested_users_count = 0;
                                $is_interested = false;
                                foreach ($interested_users_properties as $interested_user_property) {
                                    if ($interested_user_property['property_id'] == $property['id']) {
                                        $interested_users_count++;
                                    if ($interested_user_property['user_id'] == $user_id) {
                                        $is_interested = true;
                                        }
                                    }
                                }
                                ?>

                                <?php
                                
                                if ($is_interested) {
                                ?>
                                    <i class="fa-regular fa-heart"></i>
                                    <?php
                                }else {
                                    ?>
                                        <i class="fa-regular fa-heart"></i>
                                        <?php
                                    }
                                        ?>
                                    
                            </span>
                            <div class="interested"><?= $interested_users_count ?> interested</div>
                        </div>
                    </div>
                    <h5 class="card-title"><?= $property['name'] ?></h5>
                    <p class="card-text"><?= $property['address'] ?></p>
                    <div class="gender">
                        <?php
                        if ($property['gender'] == "male") {
                        ?>
                            <img src="img/male.png" class="unisex" />
                        <?php
                        } elseif ($property['gender'] == "female") {
                        ?>
                            <img src="img/female.png" class="unisex" />
                        <?php
                        } else {
                        ?>
                            <img src="img/unisex.png" class="unisex" />
                        <?php
                        }
                        ?>
                    </div>
                    <div class="row rate-prop">
                        <div class="col-8 price"><strong>₹ <?= number_format($property['rent']) ?>/-</strong> per month
                        </div>
                        <div class="col-4 view">
                            <a href="property_detail.php?property_id=<?= $property['id'] ?>" class="btn btn-info">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    if (count($properties) == 0) {
        ?>
            <div class="no-property-container">
                <p>No PG to list</p>
            </div>
        <?php
    }
        ?>

        </div>

        <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filter-heading" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="filter-heading">Filters</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body">
                        <h5>Gender</h5>
                        <hr />
                        <div>
                            <button class="btn btn-outline-dark btn-active">
                                No Filter
                            </button>
                            <button class="btn btn-outline-dark">
                                <i class="fas fa-venus-mars"></i>Unisex
                            </button>
                            <button class="btn btn-outline-dark">
                                <i class="fas fa-mars"></i>Male
                            </button>
                            <button class="btn btn-outline-dark">
                                <i class="fas fa-venus"></i>Female
                            </button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button data-bs-dismiss="modal" class="btn btn-success" data-bs-toggle="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include "includes/footer.php";
        ?>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>