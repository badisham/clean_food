<?php
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
    $restaurant_id = $_SESSION['restaurant_id'];
    $sql = "SELECT COUNT(order_p.id) as order_amount FROM `order_product_list` as order_p
        INNER JOIN order_product ON order_product.id = order_p.order_id
        WHERE order_p.status = 'wait' AND order_p.product_id in (SELECT id FROM `product` WHERE `restaurant_id` = '$restaurant_id') ORDER BY order_p.id DESC";
    $result = mysqli_query($conn, $sql);
    $order_amount = mysqli_fetch_assoc($result)['order_amount'];
}

?>
<div class="main_nav_container">
    <div class="container">
        <div class="row">
            <div class="navbar navbar-expand-lg navbar-light ">
                <div class="col-3">
                    <a href="index.php"><span><img src="images/logo_home.png" style="height: 100px;"></span></a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBurger" aria-controls="navBurger" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="my-2 my-lg-0 col-md-9" style="background-color: #fff;">
                    <div class="collapse navbar-collapse" id="navBurger" style="float: right;">
                        <ul id="nav_sub_menu" class="navbar-nav navbar_menu">

                            <a href="index.php">
                                <li class="subhead_menu">หน้าแรก</li>
                            </a>
                            <a href="restaurant.php">
                                <li class="subhead_menu">ร้านค้า</li>
                            </a>
                            <!-- <a href="Food.php">
                                <li class="subhead_menu">Clean Food</li>
                            </a>
                            <a href="Desserts.php">
                                <li class="subhead_menu">Clean Desserts</li>
                            </a> -->
                            <?php
                            if (isset($_SESSION['id'])) {
                                if ($_SESSION['type'] == 4) {
                            ?>
                                    <a href="backend/">
                                        <li class="subhead_menu">BackOffice</li>
                                    </a>
                                <?php
                                } else if ($_SESSION['type'] == 2) {
                                ?>
                                    <a href="restaurant-profile.php">
                                        <li class="subhead_menu">ร้านค้าของฉัน </li>
                                    </a>
                                    <a href="restaurant-order.php">
                                        <li class="subhead_menu">รายการสั่งซื้อเข้ามา
                                            <?php if ($order_amount > 0) { ?> <span class="badge bg-danger badge-alert"><?= $order_amount ?></span><?php } ?>
                                        </li>
                                    </a>
                                    <a href="restaurant-report.php">
                                        <li class="subhead_menu">ข้อมูลการขาย </li>
                                    </a>

                                <?php
                                } else if ($_SESSION['type'] == 3) {
                                ?>
                                    <a href="rider-order.php">
                                        <li class="subhead_menu">รายการส่งออเดอร์</li>
                                    </a>
                                    <a href="rider-profile.php">
                                        <li class="subhead_menu">ประวัติการส่ง</li>
                                    </a>
                            <?php
                                }
                            }
                            ?>
                            <a href="order-list.php">
                                <li class="subhead_menu">รายการสั่งซื้อ</li>
                            </a>
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