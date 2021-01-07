<?php
require 'condb.php';

if (!isset($_SESSION['id'])) {
    header("Refresh:0; login.php");
    return;
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


    if (!isset($_GET['restaurant_id'])) {
        $my_restauran_id = $_SESSION['restaurant_id'];

        $restaurants = [];
        // Check new product
        $timeNewProduct = strtotime('-1 day', time());
        // Query data
        $sql = "SELECT * FROM `restaurant` WHERE id != '$my_restauran_id' ORDER BY id DESC";
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

        <div class="best_sellers" style="margin-top: 150px;">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <div class="section_title new_arrivals_title">
                            <h2> ร้านอาหารใหม่ </h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="product_slider_container">
                            <div class="owl-carousel owl-theme product_slider">

                                <?php
                                foreach ($restaurants as $restaurant) {
                                ?>

                                    <!-- Slide 2 -->

                                    <div class="owl-item product_slider_item">
                                        <div class="product-item women">
                                            <a href="restaurant.php?restaurant_id=<?= $restaurant->id ?>">
                                                <div class="product">
                                                    <div class="slide_image" style="background-image: url(images/restaurant/<?= $restaurant->img ?>);">
                                                    </div>
                                                    <div class="favorite"></div>
                                                    <?= CheckNewByDate($restaurant->created_at); ?>
                                                    <div class="product_info">
                                                        <h4 style="word-wrap: break-word;margin-top: 20px;">
                                                            <?= $restaurant->name ?> </h4>
                                                        <!-- <div class="product_price"><?= $restaurant->name ?> </div> -->

                                                    </div>
                                                    <div class="red_button add_to_cart_button"><a href="restaurant.php?restaurant_id=<?= $restaurant->id ?>"> สั่งเลย</a></div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php
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
    } else {
        $restarant = new Restaurant();
        $sql = "SELECT * FROM `restaurant` WHERE id = '" . $_GET['restaurant_id'] . "'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $restarant->id = $row['id'];
                    $restarant->name = $row['name'];
                    $restarant->description = $row['description'];
                    $restarant->img = $row['img'];
                }
            }
        }
        $products_clean = [];
        $products_sweet = [];
        $sql = "SELECT * FROM product WHERE restaurant_id = '" . $_GET['restaurant_id'] . "'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product = new Product();
                    $product->id = $row['id'];
                    $product->name = $row['name'];
                    $product->img = $row['img'];
                    $product->day = $row['day'];
                    $product->genre = $row['genre'];
                    $product->price = $row['price'];
                    $product->description = $row['description'];
                    if ($product->genre == "food") {
                        $products_clean[$row['id']] = $product;
                    } elseif ($product->genre == "sweet") {
                        $products_sweet[$row['id']] = $product;
                    }
                }
            }
        }
        echo $restarant->img;
    ?>
        <div class="restaurant_detail" style="margin-top: 150px;">

            <div class="container">
                <div class="restaurant_img_cover" style="background-image: url(images/restaurant/<?= $restarant->img ?>);"></div>
                <div class="row">
                    <h2><?= $restarant->name ?></h2>
                    <p><?= $restarant->description ?></p>
                </div>
                <?php
                if (count($products_clean) > 0) {
                ?>
                    <div class="row">
                        <h4>อาหารคลีน</h4>
                        <table class="table">
                            <tbody>
                                <?php
                                foreach ($products_clean as $product) {
                                    if ($product->day != "") {
                                ?>
                                        <tr>
                                            <td width="200">
                                                <div class="restaurant_img_cover" style="background-image: url(images/product/<?= $product->img ?>);"></div>
                                            </td>
                                            <td width="300" style="vertical-align: middle;">
                                                <h3><?= $product->name ?></h3>
                                                <p><?= LimitText($product->description, 100) ?></p>
                                            </td>
                                            <td width="300" style="vertical-align: middle;">
                                                <p><?= $product->price ?> บาท</p>
                                                <p>ส่งออเดอร์ครั้งต่อไปวันที่ <?= GetNextDay($product->day) ?></p>
                                            </td>
                                            <td width="50" class="text-right">
                                                <a href="cart.php?product_id=<?= $product->id ?>" class="btn btn-primary">+</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                if (count($products_sweet) > 0) {
                ?>
                    <div class="row">
                        <h4>ของหวาน</h4>
                        <table class="table">
                            <tbody>
                                <?php
                                foreach ($products_sweet as $product) {
                                    if ($product->day != "") {

                                ?>
                                        <tr>
                                            <td width="200">
                                                <div class="restaurant_img_cover" style="background-image: url(images/product/<?= $product->img ?>);"></div>
                                            </td>
                                            <td width="300" style="vertical-align: middle;">
                                                <h3><?= $product->name ?></h3>
                                                <p><?= LimitText($product->description, 100) ?></p>
                                            </td>
                                            <td width="300" style="vertical-align: middle;">
                                                <p><?= $product->price ?> บาท</p>
                                                <p>ส่งออเดอร์ครั้งต่อไปวันที่ <?= GetNextDay($product->day) ?></p>
                                            </td>
                                            <td width="50" class="text-right">
                                                <a href="cart.php?product_id=<?= $product->id ?>" class="btn btn-primary">+</a>
                                            </td>
                                        </tr>

                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>

    <?php
    }
    require 'components/benefit.php';
    ?>
    <style>
        .restaurant_img_cover {

            width: 100%;
            height: 200px !important;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .restaurant_detail tr {
            height: 150px;
        }

        .restaurant_detail tr img {
            height: 150px;
        }
    </style>

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