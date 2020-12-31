<?php
require 'condb.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>

<body>

    <?php
    require './components/header.php';
    if (!isset($_SESSION['id'])) {
    ?>

        <div class="background_fix background_filter">

        </div>

        <div class="main_slider" style="background-image:url(images/header-bg.jpg)">
            <div class="container fill_height">
                <div class="row align-items-center fill_height">
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>

        <div style="height: 300px;">
            <div class="container fill_height">
                <div class="row align-items-center fill_height">
                    <div class="col order_background">
                        <div class="row">
                            <div class="col-12 text-right">
                                <h1>สั่งอาหารคลีนกับเรา สั่งเลย!
                                    <?= $_SESSION['id'] ?></h1>
                                <!-- <p>asdahsiudhu</p> -->
                                <button class="btn btn-primary">สั่งเลย</button>
                            </div>
                            <!-- <div class="col-6 text-right">
                        </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div></div>
        <!-- <div style="margin-top: 0;height: 300px">
        <div class="restaurant_background background_filter"></div>
        <div class="container fill_height">
            <div class="row align-items-center fill_height">
                <div class="col ">
                    <h1>มาร่วมเป็นร้านค้ากับเรา</h1>
                    <p>asdahsiudhu</p>
                    <div class="row">
                        <div class="col-6">asdasdasdasd</div>
                        <div class="col-6 text-right">
                            <button class="btn btn-primary">asdasodho</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


        <div class="banner backgrund_w_content" style="padding-top: 40px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="banner_item align-items-center" style="background-image:url(https://paapaii.com/wp-content/uploads/2018/12/PB110193.jpg)">
                            <div class="banner_category text-right">
                                <h3>สมัครเป็นร้านอาหารกับเรา</h3>
                                <a href="register-restaurant.php" class="text-right">
                                    <button class="btn btn-primary">สมัครเลย</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="banner_item align-items-center" style="background-image:url(images/chinaherb.jpg)">
                            <div class="banner_category  text-right">
                                <h3>มาร่วมเป็นส่วนนึงในครอบครัวพนักงานขนส่งของเรา</h3>
                                <a href="register-rider.php" class="text-right">
                                    <button class="btn btn-primary">สมัครเลย</button>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <?php
    } else {

        $products = [];
        // Check new product
        $timeNewProduct = strtotime('-1 day', time());
        // Query data
        $sql = "SELECT * FROM `product` ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product = new Product;
                    $product->id = $row['id'];
                    $product->name = $row['name'];
                    $product->img = $row['img'];
                    $product->genre = $row['genre'];
                    $product->price = $row['price'];
                    $product->description = $row['description'];
                    $product->created_at = $row['created_at'];
                    $products[$row['id']] = $product;
                }
            }
        }
    ?>



        <div class="best_sellers">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <div class="section_title new_arrivals_title">
                            <h2> ทานง่าย ไม่เพิ่มพุง </h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="product_slider_container">
                            <div class="owl-carousel owl-theme product_slider">

                                <?php
                                foreach ($products as $product) {
                                    if ($product->genre == "food") {
                                ?>

                                        <!-- Slide 2 -->

                                        <div class="owl-item product_slider_item">
                                            <div class="product-item women">
                                                <div class="product">
                                                    <div class="product_image">
                                                        <img src="images/product/<?= $product->img ?>" alt="">
                                                    </div>
                                                    <div class="favorite"></div>
                                                    <div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
                                                    <div class="product_info">
                                                        <h6 class="product_name"><a href="cart.php?product_id=<?= $product->id ?>  "> <?= $product->name ?> </a></h6>
                                                        <div class="product_price"><?= $product->price ?> บาท</div>

                                                    </div>
                                                    <div class="red_button add_to_cart_button"><a href="cart.php?product_id=<?= $product->id ?>  "> add to cart</a></div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
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
        <!-- 
        <div class="new_arrivals backgrund_w_content">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <div class="section_title new_arrivals_title">
                            <h2>อ้วนน้อยอร่อยหนัก</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>



                            <?php
                            foreach ($products as $product) {
                                if ($product->genre == "sweet") {
                            ?>

                                    <div class="product-item men">
                                        <div class="product discount product_filter">
                                            <div class="product_image">
                                                <img src="images/product/<?= $product->img ?>" alt="">
                                            </div>

                                            <div class="favorite favorite_left"></div>
                                            <?php
                                            if (strtotime($product->created_at) > $timeNewProduct) {
                                            ?>
                                                <div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
                                            <?php
                                            }
                                            ?>

                                            <div class="product_info">
                                                <h6 class="product_name"><a href="cart.php?product_id=<?= $product->id ?>"> <?= $product->name ?> </a></h6>
                                                <div class="product_price">
                                                    <?= $product->price ?>
                                                    บาท</div>
                                            </div>
                                        </div>
                                        <div class="red_button add_to_cart_button"><a href="cart.php?product_id=<?= $product->id ?>  "> add to cart</a>
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
        </div> -->

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
                                foreach ($products as $product) {
                                    if ($product->genre == "sweet") {
                                ?>

                                        <!-- Slide 2 -->

                                        <div class="owl-item product_slider_item">
                                            <div class="product-item women">
                                                <div class="product">
                                                    <div class="product_image">
                                                        <img src="images/product/<?= $product->img ?>" alt="">
                                                    </div>
                                                    <div class="favorite"></div>
                                                    <div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
                                                    <div class="product_info">
                                                        <h6 class="product_name"><a href="cart.php?product_id=<?= $product->id ?>  "> <?= $product->name ?> </a></h6>
                                                        <div class="product_price"><?= $product->price ?> บาท</div>

                                                    </div>
                                                    <div class="red_button add_to_cart_button"><a href="cart.php?product_id=<?= $product->id ?>  "> add to cart</a></div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
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

    <?php
    }
    ?>







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