<?php
require 'condb.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>

<body>
    <div class="super_container">

        <!-- Header -->

        <header class="header trans_300">

            <div class="top_nav">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">

                            <!--ใส่ได้-->


                        </div>
                        <div class="col-md-6 text-right">
                            <div class="top_nav_right">
                                <ul class="top_nav_menu">


                                    <li class="account">
                                        <a href="#">
                                            My Account
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="account_selection">
                                            <?php
                                            require_once 'components/nav.php';
                                            ?>

                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->

            <?php
            require_once 'components/subheader.php';
            ?>

        </header>
    </div>


    <!-- Slider -->

    <div class="main_slider" style="background-image:url(images/bgg1.png)">
        <div class="container fill_height">
            <div class="row align-items-center fill_height">
                <div class="col">
                    <div class="main_slider_content">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
             Banner 

            <div class="banner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="banner_item align-items-center" style="background-image:url(images/thaiherb.png)">
                                <div class="banner_category">
                                    <a href="Food.php">Clean Food</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner_item align-items-center" style="background-image:url(images/chinaherb.jpg)">
                                <div class="banner_category">
                                    <a href="Desserts.php">Clean Desserts</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>-->

    <!-- New Arrivals -->

    <div class="new_arrivals">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section_title new_arrivals_title">
                        <h2>ทานง่าย ไม่เพิ่มพุง</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>

                        <?php
                        $sql = "SELECT * FROM item WHERE type_disease = 'อาหารคลีน' ORDER by id limit 5";
                        $result = mysqli_query($conn, $sql);
                        if ($result) { // ->query($sql)) {
                            echo mysqli_num_rows($result) . "adawdwad";
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                    <div class="product-item men">
                                        <div class="product discount product_filter">
                                            <div class="product_image">
                                                <img src="images/food/<?php echo $row['item_image'] ?> " alt="">
                                            </div>
                                            <div class="favorite favorite_left"></div>
                                            <!--                                                    <div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span>-$20</span></div>-->
                                            <div class="product_info">
                                                <h6 class="product_name"><a href="single.php?id=<?php echo $row['id'] ?>  "> <?php echo $row['item_name'] ?> </a></h6>
                                                <div class="product_price"><?php echo $row['item_price'] ?> บาท</div>
                                            </div>
                                        </div>
                                        <div class="red_button add_to_cart_button"><a href="single.php?id=<?php echo $row['id'] ?>  "> add to cart</a></div>

                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Best Sellers -->

    <div class="best_sellers">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section_title new_arrivals_title">
                        <h2> อ้วนน้อยอร่อยหนัก </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="product_slider_container">
                        <div class="owl-carousel owl-theme product_slider">

                            <?php
                            $sql = "SELECT * FROM item WHERE type_disease = 'ขนมคลีน' ORDER by id limit 8 ";
                            $result = mysqli_query($conn, $sql); //->query($sql);

                            if ($result) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                            ?>

                                        <!-- Slide 2 -->

                                        <div class="owl-item product_slider_item">
                                            <div class="product-item women">
                                                <div class="product">
                                                    <div class="product_image">
                                                        <img src="images/dessert/<?php echo $row['item_image'] ?>" alt="">
                                                    </div>
                                                    <div class="favorite"></div>
                                                    <div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
                                                    <div class="product_info">
                                                        <h6 class="product_name"><a href="single.php?id=<?php echo $row['id'] ?>  "> <?php echo $row['item_name'] ?> </a></h6>
                                                        <div class="product_price"><?php echo $row['item_price'] ?> บาท</div>

                                                    </div>
                                                    <div class="red_button add_to_cart_button"><a href="single.php?id=<?php echo $row['id'] ?>  "> add to cart</a></div>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    }
                                }
                            }
                            ?>

                        </div>

                        <!-- Slider Navigation -->

                        <div class="product_slider_nav_left product_slider_nav d-flex align-items-center justify-content-center flex-column">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </div>
                        <div class="product_slider_nav_right product_slider_nav d-flex align-items-center justify-content-center flex-column">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefit -->

    <div class="benefit">
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

    <br><br><br>




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



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="styles/bootstrap4/popper.js"></script>
    <script src="styles/bootstrap4/bootstrap.min.js"></script>
    <script src="plugins/Isotope/isotope.pkgd.min.js"></script>
    <script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
    <script src="plugins/easing/easing.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>