<div class="main_nav_container">
    <div class="container">
        <div class="row">
            <div class="navbar navbar-expand-lg navbar-light ">
                <div class="col-5">
                    <a href="index.php"><span><img src="images/logo_home.png" style="height: 100px;"></span></a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBurger" aria-controls="navBurger" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="my-2 my-lg-0 col-md-6" style="background-color: #fff;">
                    <div class="collapse navbar-collapse" id="navBurger">
                        <ul class="navbar-nav navbar_menu">

                            <a href="index.php">
                                <li class="subhead_menu">Home</li>
                            </a>
                            <a href="restaurant.php">
                                <li class="subhead_menu">Market</li>
                            </a>
                            <a href="Food.php">
                                <li class="subhead_menu">Clean Food</li>
                            </a>
                            <a href="Desserts.php">
                                <li class="subhead_menu">Clean Desserts</li>
                            </a>
                            <?php
                            if (isset($_SESSION['id'])) {
                                if ($_SESSION['type'] == 4) {
                            ?>
                                    <a href="admin.php">
                                        <li class="subhead_menu">ADMIN</li>
                                    </a>
                                <?php
                                } else if ($_SESSION['type'] == 2) {
                                ?>
                                    <a href="restaurant-profile.php">
                                        <li class="subhead_menu">Profile Restaurant</li>
                                    </a>
                            <?php
                                }
                            }
                            ?>
                            <a href="cart.php">
                                <li>
                                    <div class="checkout">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    </div>
                                </li>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>