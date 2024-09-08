<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PG Life</title>
    
    <?php
    include("includes/head_links.php");
    ?>

    <link rel="stylesheet" href="./CSS/home.css" />
  </head>
  <body>
    
    <?php
    include "includes/header.php";
    ?>
    <?php
    include "includes/signup_modal.php";
    include "includes/login_modal.php";
    ?>

    <div class="banner-container" style="background-image: url(./img/bg.png);">
      <h1 class="heading">Happiness Per Square Foot</h1>
      <form class="d-flex pg-search" role="search" method="GET" action="property_list.php">
        <input
          class="form-control me-2"
          type="search"
          placeholder="Enter your city for PGs"
          aria-label="Search"
          id='city' name='city'
        />
        <button class="btn btn-secondary" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </form>
    </div>

    <div class="page-container">
      <h2 class="cities">Major Cities</h2>
      <div class="major-city row">
        <div class="city col-md">
          <a href="./property_list.php?city=Delhi"
            ><div class="city-div rounded-circle">
              <img src="./img/delhi.png" alt="delhi" class="city-image" />
            </div>
          </a>
        </div>
        <div class="city col-md">
          <a href="./property_list.php?city=Mumbai">
            <div class="city-div rounded-circle">
              <img src="./img/mumbai.png" alt="mumbai" class="city-image" /></div
          ></a>
        </div>
        <div class="city col-md">
          <a href="./property_list.php?city=Bengaluru">
            <div class="city-div rounded-circle">
              <img
                src="./img/bangalore.png"
                alt="bangalore"
                class="city-image"
              /></div
          ></a>
        </div>
        <div class="city col-md">
          <a href="./property_list.php?city=Hyderabad"
            ><div class="city-div rounded-circle">
              <img
                src="./img/hyderabad.png"
                alt="hyderabad"
                class="city-image"
              /></div
          ></a>
        </div>
      </div>
    </div>

    
    <?php
    include "includes/footer.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
