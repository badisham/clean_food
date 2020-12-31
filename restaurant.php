<?php
require 'condb.php';

if (!isset($_SESSION['id'])) {
    header("Refresh:0; login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>

<body>

    <?php
    require './components/header.php';


    $restaurants = [];
    // Check new product
    $timeNewProduct = strtotime('-1 day', time());
    // Query data
    $sql = "SELECT * FROM `restaurant` ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $restaurant = new Restaurant;
                $restaurant->id = $row['id'];
                $restaurant->name = $row['name'];
                $restaurant->img = $row['img'];
                $restaurant->genre = $row['genre'];
                $restaurant->description = $row['description'];
                $restaurant->created_at = $row['created_at'];
                $restaurants[$row['id']] = $restaurant;
            }
        }
    }
    ?>

    <div class="new_arrivals backgrund_w_content" style="margin-top: 150px;">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section_title new_arrivals_title">
                        <h2>ร้านอาหาร</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>
                        <?php
                        foreach ($restaurants as $restaurant) {
                            if ($restaurant->genre == "food") {
                        ?>
                                <div class="product-item men">
                                    <div class="product discount product_filter">
                                        <div class="product_image">
                                            <img src="images/restaurant/<?= $restaurant->img ?>" alt="">
                                        </div>
                                        <div class="favorite favorite_left"></div>
                                        <?php
                                        if (strtotime($restaurant->created_at) > $timeNewProduct) {
                                        ?>
                                            <div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
                                        <?php
                                        }
                                        ?>
                                        <div class="product_info">
                                            <h6 class="product_name">
                                                <a href="cart.php?product_id=<?= $restaurant->id ?>">
                                                    <?= $restaurant->name ?>
                                                </a>
                                            </h6>
                                            <!-- <div class="product_price">
                                                <?= $restaurant->price ?>
                                                บาท</div> -->
                                        </div>
                                    </div>
                                    <div class="red_button add_to_cart_button"><a href="cart.php?product_id=<?= $restaurant->id ?>  "> add to cart</a>
                                    </div>

                                </div>
                        <?php
                            }
                        }

                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="benefit backgrund_w_content" style="padding: 100px 0;">
        <div class="container">
            <div class="row benefit_row">
                <div class="col-lg-3 benefit_col">
                    <div class="benefit_item d-flex flex-row align-items-center">
                        <div class="benefit_icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
                        <div class="benefit_content">
                            <h6>free shipping</h6>
                            <p>Suffered Alteration in Some Form</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 benefit_col">
                    <div class="benefit_item d-flex flex-row align-items-center">
                        <div class="benefit_icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                        <div class="benefit_content">
                            <h6>cach on delivery</h6>
                            <p>The Internet Tend To Repeat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 benefit_col">
                    <div class="benefit_item d-flex flex-row align-items-center">
                        <div class="benefit_icon"><i class="fa fa-undo" aria-hidden="true"></i></div>
                        <div class="benefit_content">
                            <h6>45 days return</h6>
                            <p>Making it Look Like Readable</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 benefit_col">
                    <div class="benefit_item d-flex flex-row align-items-center">
                        <div class="benefit_icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                        <div class="benefit_content">
                            <h6>opening all week</h6>
                            <p>24 HOUR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!--             Newsletter 

            <div class="newsletter">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="newsletter_text d-flex flex-column justify-content-center align-items-lg-start align-items-md-center text-center">
                                <h4>Newsletter</h4>
                                <p>Subscribe to our newsletter and get 20% off your first purchase</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <form action="post">
                                <div class="newsletter_form d-flex flex-md-row flex-column flex-xs-column align-items-center justify-content-lg-end justify-content-center">
                                    <input id="newsletter_email" type="email" placeholder="Your email" required="required" data-error="Valid email is required.">
                                    <button id="newsletter_submit" type="submit" class="newsletter_submit_btn trans_300" value="Submit">subscribe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

             Footer 
            <br><br>
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer_nav_container">
                                <div class="cr">©2018 All Rights Reserverd. This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="#">Colorlib</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>-->

    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="styles/bootstrap4/popper.js"></script>
    <script src="styles/bootstrap4/bootstrap.min.js"></script>
    <script src="plugins/Isotope/isotope.pkgd.min.js"></script>
    <script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
    <script src="plugins/easing/easing.js"></script>
    <!-- <script src="js/custom.js"></script> -->
</body>

</html>