<div class="header sticky-top">
      <nav class="navbar bg-body-tertiary navbar-expand-md">
        <div>
          <a class="navbar-brand" href="./index.php">
            <img src="./img/logo.png" alt="pglife" width="120px" />
          </a>
        </div>
        <div class="my-toggle">
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#my-navbar"
            
          >
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>

        <div class="collapse navbar-collapse justify-content-end my-login" id="my-navbar">
          <ul class="navbar-nav" style="align-items: center;">
          <?php
            //Check if user is logged-in or not
            if (!isset($_SESSION["user_id"])) {
            ?>
              <li class="nav-item">
                  <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#signup-modal">
                    <i class="fa-regular fa-user"></i>Signup
                  </a>
              </li>
              <div class="nav-vl"></div>
              <li class="nav-item">
                  <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#login-modal">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>Login
                  </a>
              </li>
              <?php
              } else {
                ?>
                    <div class='nav-name'>
                        Hi, <?php echo $_SESSION["full_name"] ?>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fa fa-user"></i>Dashboard
                        </a>
                    </li>
                    <div class="nav-vl"></div>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fa fa-sign-out-alt"></i>Logout
                        </a>
                    </li>
                    <?php
                  }?>
          </ul>
      </div>
      </nav>
    </div>