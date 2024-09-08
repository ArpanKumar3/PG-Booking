<?php
session_start();
require "includes/database_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
$property_id = $_GET["property_id"];

$sql_1 = "SELECT *, p.id AS property_id, p.name AS property_name, c.name AS city_name 
            FROM properties p
            INNER JOIN cities c ON p.city_id = c.id 
            WHERE p.id = $property_id";
$result_1 = mysqli_query($conn, $sql_1);
if (!$result_1) {
    echo "Something went wrong!";
    return;
}
$property = mysqli_fetch_assoc($result_1);
if (!$property) {
    echo "Something went wrong!";
    return;
}


$sql_2 = "SELECT * FROM testimonials WHERE property_id = $property_id";
$result_2 = mysqli_query($conn, $sql_2);
if (!$result_2) {
    echo "Something went wrong!";
    return;
}
$testimonials = mysqli_fetch_all($result_2, MYSQLI_ASSOC);


$sql_3 = "SELECT a.* 
            FROM amenities a
            INNER JOIN properties_amenities pa ON a.id = pa.amenity_id
            WHERE pa.property_id = $property_id";
$result_3 = mysqli_query($conn, $sql_3);
if (!$result_3) {
    echo "Something went wrong!";
    return;
}
$amenities = mysqli_fetch_all($result_3, MYSQLI_ASSOC);


$sql_4 = "SELECT * FROM interested_users_properties WHERE property_id = $property_id";
$result_4 = mysqli_query($conn, $sql_4);
if (!$result_4) {
    echo "Something went wrong!";
    return;
}
$interested_users = mysqli_fetch_all($result_4, MYSQLI_ASSOC);
$interested_users_count = mysqli_num_rows($result_4);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $property['property_name']; ?> | PG Life</title>
    
    <?php
    include "includes/head_links.php";
    ?>

    <link rel="stylesheet" href="./CSS/property_detail.css" />
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
          <li class="breadcrumb-item">
          <a href="property_list.php?city=<?= $property['city_name']; ?>"><?= $property['city_name']; ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page"><?= $property['property_name']; ?></li>
        </ol>
      </div>

      <div id="property-images" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#property-images" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#property-images" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#property-images" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="img/properties/1/1d4f0757fdb86d5f.jpg" class="d-block w-100" alt="slide">
          </div>
          <div class="carousel-item">
            <img src="img/properties/1/46ebbb537aa9fb0a.jpg" alt="slide" class="d-block w-100" alt="slide">
          </div>
          <div class="carousel-item">
            <img src="img/properties/1/eace7b9114fd6046.jpg" class="d-block w-100" alt="slide">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#property-images" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#property-images" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>

    <div class="property-summary">
        <div class="card-body">
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
                $is_interested = false;
                foreach ($interested_users as $interested_user) {
                    if ($interested_user['user_id'] == $user_id) {
                        $is_interested = true;
                    }
                }

                if ($is_interested) {
                ?>
                    <i class="is-interested-image fa-regular fa-heart"></i>
                <?php
                } else {
                ?>
                    <i class="is-interested-image fa-regular fa-heart"></i>
                <?php
                }
                ?>
                </span>
                <div class="interested"><?= $interested_users_count ?> interested</div>
              </div>
            </div>
            <h5 class="card-title"><?= $property['property_name'] ?></h5>
            <p class="card-text"><?= $property['address'] ?></p>
            <div class="gender">
            <?php
                if ($property['gender'] == "male") {
                ?>
                    <img src="img/male.png" class="sex">
                <?php
                } elseif ($property['gender'] == "female") {
                ?>
                    <img src="img/female.png" class="sex">
                <?php
                } else {
                ?>
                    <img src="img/unisex.png" class="sex">
                <?php
                }
                ?>
            </div>
            <div class="row rate-prop">
                <div class="col-6 price"><strong>₹ <?= number_format($property['rent']) ?>/-</strong> per month
                </div>
              <div class="col-6 view"><a href="#" class="btn btn-info">Book Now</a></div>
            </div>
          </div>
    </div>

    <div class="property-amenities">
        <div class="amenities-container">
            <h1>Amenities</h1>
            <div class="row justify-content-between">
                <div class="amen-sec col-md-auto">
                    <h5>Building</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Building") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="col-md-auto amen-sec ">
                    <h5>Common Area</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Common Area") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="amen-sec  col-md-auto">
                    <h5>Bedroom</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Bedroom") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="col-md-auto">
                    <h5>Washroom</h5>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($amenity['type'] == "Washroom") {
                    ?>
                            <div class="amenity-container">
                                <img src="img/amenities/<?= $amenity['icon'] ?>.svg">
                                <span><?= $amenity['name'] ?></span>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="property-about about-container">
        <h1>About the Property</h1>
        <p><?= $property['description'] ?></p>
    </div>

    <div class="property-rating">
      <div class="rating-container">
          <h1>Property Rating</h1>
          <div class="row align-items-center justify-content-between">
              <div class="col-md-6">
                  <div class="rating-criteria row">
                      <div class="col-6">
                          <i class="rating-criteria-icon fas fa-broom"></i>
                          <span class="rating-criteria-text">Cleanliness</span>
                      </div>
                      <div class="rating-criteria-star-container col-6" title="<?= $property['rating_clean'] ?>">
                          <?php
                            $rating = $property['rating_clean'];
                            for ($i = 0; $i < 5; $i++) {
                                if ($rating >= $i + 0.8) {
                            ?>
                                    <i class="fas fa-star"></i>
                                <?php
                                } elseif ($rating >= $i + 0.3) {
                                ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php
                                } else {
                                ?>
                                    <i class="far fa-star"></i>
                            <?php
                                }
                            }
                            ?>
                      </div>
                  </div>

                  <div class="rating-criteria row">
                      <div class="col-6">
                          <i class="rating-criteria-icon fas fa-utensils"></i>
                          <span class="rating-criteria-text">Food Quality</span>
                      </div>
                      <div class="rating-criteria-star-container col-6" title="<?= $property['rating_food'] ?>">
                      <?php
                            $rating = $property['rating_food'];
                            for ($i = 0; $i < 5; $i++) {
                                if ($rating >= $i + 0.8) {
                            ?>
                                    <i class="fas fa-star"></i>
                                <?php
                                } elseif ($rating >= $i + 0.3) {
                                ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php
                                } else {
                                ?>
                                    <i class="far fa-star"></i>
                            <?php
                                }
                            }
                            ?>
                      </div>
                  </div>

                  <div class="rating-criteria row">
                      <div class="col-6">
                          <i class="rating-criteria-icon fa fa-lock"></i>
                          <span class="rating-criteria-text">Safety</span>
                      </div>
                      <div class="rating-criteria-star-container col-6" title="<?= $property['rating_safety'] ?>">
                      <?php
                            $rating = $property['rating_safety'];
                            for ($i = 0; $i < 5; $i++) {
                                if ($rating >= $i + 0.8) {
                            ?>
                                    <i class="fas fa-star"></i>
                                <?php
                                } elseif ($rating >= $i + 0.3) {
                                ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php
                                } else {
                                ?>
                                    <i class="far fa-star"></i>
                            <?php
                                }
                            }
                            ?>
                      </div>
                  </div>
              </div>

              <div class="col-md-4">
                  <div class="rating-circle">
                  <?php
                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                        $total_rating = round($total_rating, 1);
                        ?>
                      <div class="total-rating"><?= $total_rating ?></div>
                      <div class="rating-circle-star-container">
                      <?php
                            $rating = $total_rating;
                            for ($i = 0; $i < 5; $i++) {
                                if ($rating >= $i + 0.8) {
                            ?>
                                    <i class="fas fa-star"></i>
                                <?php
                                } elseif ($rating >= $i + 0.3) {
                                ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php
                                } else {
                                ?>
                                    <i class="far fa-star"></i>
                            <?php
                                }
                            }
                            ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="property-testimonials testimonial-container">
    <h1>What people say</h1>
    <?php
        foreach ($testimonials as $testimonial) {
        ?>
    <div class="testimonial-block">
        <div class="testimonial-image-container">
            <img class="testimonial-img" src="img/man.png">
        </div>
        <div class="testimonial-text">
            <i class="fa fa-quote-left" aria-hidden="true"></i>
            <p><?= $testimonial['content'] ?></p>
        </div>
        <div class="testimonial-name">- <?= $testimonial['user_name'] ?></div>
    </div>
    <?php
        }
        ?>
    </div>

    <?php
    include "includes/footer.php";
    ?>


      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>