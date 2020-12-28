<?php
if (isset($_POST['keyword']))
    $keyword = $_POST['keyword'];
else
    $keyword = "";
?>
<div class="main_nav_container">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-right">
                <div class="logo_container">
                    <a href="Homepage.php"><span><img src="images/logo_home.png"></span></a>
                </div>
                <nav class="navbar">
                    <ul class="navbar_menu">
                        <li><a href="Homepage.php">Home</a></li>

                    </ul>

                    <ul class="navbar_menu">
                        <li><a href="post_item.php">Market</a></li>

                    </ul>
                    <ul class="navbar_menu">
                        <li><a href="Food.php">Clean Food</a></li>

                    </ul>
                    <ul class="navbar_menu">
                        <li><a href="Desserts.php">Clean Desserts</a></li>
                    </ul>

                    <?php

                    if (isset($_SESSION['username']) && $_SESSION['type'] == 2) {

                    ?>
                        <ul class="navbar_menu">
                            <li><a href="admin.php">ADMIN</a></li>
                        </ul>
                    <?php
                    }
                    ?>
                    <!--
                    <ul>
                        <form class="form-inline my-2 my-lg-0" method="post" action="search.php"> 
                            <input class="form-control mr-sm-2" type="text" id="txtKeyword" value="<?= $keyword ?>" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0"   type="submit">Search</button>
                           
                        </form>
                    </ul>-->
                    <ul class="navbar_user">


                        <li class="checkout">

                            <a href="cart.php">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <!--                                <span id="checkout_items" class="checkout_items">2</span>-->
                            </a>

                        </li>



                    </ul>
                    <div class="hamburger_container">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>