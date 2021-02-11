<?php
require 'condb.php';

$purchase_success = false;
require './service/order.php';

if (!isset($_SESSION['id']) || $_SESSION['type'] != 3) {
    header("Refresh:0; login.php");
    return;
}

$month_name = [
    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
];


$rider_id = $_SESSION['rider_id'];
if (isset($_GET['method']) && isset($_GET['order_product_list_id'])) {
    $order_product_list_id = $_GET['order_product_list_id'];
    $sql = "UPDATE `order_product_list` SET status = '" . $_GET['method'] . "' WHERE id = '$order_product_list_id'";
    mysqli_query($conn, $sql);
}

$orders_working = [];
$orders_history = [];
$sql = "SELECT *
    ,order_p.id as order_product_list_id
    , order_product.created_at as order_created_at
    , product.name as product_name
    , product.img as product_img
    , restaurant.name as restaurant_name
    , restaurant.img as restaurant_img
    , order_p.price as order_price
    , (SELECT address.address FROM address WHERE id = restaurant.address_id) as restaurant_address
    , (SELECT address.district FROM address WHERE id = restaurant.address_id) as restaurant_district
    , (SELECT address.amphure FROM address WHERE id = restaurant.address_id) as restaurant_amphure
    , (SELECT address.zip_code FROM address WHERE id = restaurant.address_id) as restaurant_zip_code
    , (SELECT address.address FROM address WHERE id = order_product.address_id) as user_address
    , (SELECT address.district FROM address WHERE id = order_product.address_id) as user_district
    , (SELECT address.amphure FROM address WHERE id = order_product.address_id) as user_amphure
    , (SELECT address.zip_code FROM address WHERE id = order_product.address_id) as user_zip_code
    FROM `order_product_list` as order_p 
    INNER JOIN order_product ON order_product.id = order_p.order_id 
    INNER JOIN user ON order_product.user_id = user.id 
    INNER JOIN product ON product.id = order_p.product_id 
    INNER JOIN restaurant ON restaurant.id = product.restaurant_id
    WHERE order_p.status in ('rider_recieve','success','sent_success','sent_success_cash') AND order_p.rider_id = '$rider_id' ORDER BY order_product.created_at DESC";
// echo $sql;

$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $order = new Order();
        $order->id = $row['order_id'];
        $order->created_at = $row['order_created_at'];
        $order->order_product_list_id = $row['order_product_list_id'];
        $order->status = $row['status'];
        $order->express_datetime = ThaiDatetime($row['express_datetime']);
        $order->payment_chanel = $row['payment_chanel'];
        $order->shipping = $row['shipping'];

        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['product_name'];
        $product->genre = $row['genre'];
        $product->img = $row['product_img'];
        $product->day = $row['day'];
        $product->price = $row['order_price'];
        $product->amount = $row['amount'];
        $product->price_total = $row['order_price'] * $row['amount'];
        $order->product = $product;

        $user = new User();
        $user->id = $row['user_id'];
        $user->first_name = $row['first_name'];
        $user->last_name = $row['last_name'];
        $user->tel = $row['tel'];
        $order->user = $user;

        $restaurant = new Restaurant();
        $restaurant->name = $row['restaurant_name'];
        $restaurant->img = $row['restaurant_img'];
        $restaurant->address = new Address();
        $restaurant->address->address = $row['restaurant_address'];
        $restaurant->address->amphure = $row['restaurant_amphure'];
        $restaurant->address->district = $row['restaurant_district'];
        $restaurant->address->zip_code = $row['restaurant_zip_code'];
        $order->restaurant = $restaurant;

        $address = new Address();
        $address->address = $row['user_address'];
        $address->amphure = $row['user_amphure'];
        $address->district = $row['user_district'];
        $address->zip_code = $row['user_zip_code'];
        $order->user_address = $address;

        if ($order->status == 'rider_recieve') {
            array_push($orders_working, $order);
        } else if ($order->status == 'success' || $order->status == 'sent_success' || $order->status == 'sent_success_cash') {
            array_push($orders_history, $order);
        }
    }
}

if (isset($_POST['product_name'])) {
    $is_upsert = true;
    $upsert_success = CreateProduct($conn);
}
$salaries = [];
$sql = "SELECT MONTH(express_datetime) as month_index ,SUM(shipping) as salary FROM order_product_list WHERE rider_id = '$rider_id' GROUP BY MONTH(express_datetime) ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sal->salary = $row['salary'];
        $sal->month_index = $row['month_index'];
        array_push($salaries, $sal);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>
<style>
    .card img {
        /* height: 80px; */
    }

    .vertical-mid {
        padding: 20px 20px 20px 0;
        vertical-align: middle;
        text-align: right;
    }

    .layout-1 {
        padding-bottom: 160px;
    }

    .status_free {
        text-align: center;
        color: #ccc;
        margin: 20px 0;
    }

    p {
        margin-bottom: 5px;
    }

    .layout-1 {
        padding-bottom: 0px;
    }

    .over-data {
        overflow-y: scroll;
        max-height: 70vh;
        overflow-x: hidden;
    }
</style>

<body>

    <?php
    require './components/header.php';
    ?>
    <div class="container layout-1">
        <div class="row mt-4">
            <div class="col-md-3">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                เดือน
                            </div>
                            <div class="col-6 text-right">
                                ยอดค่าส่ง
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                foreach ($salaries as $sal) {
                ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <?= $month_name[$sal->month_index - 1]  ?>
                                </div>
                                <div class="col-6 text-right">
                                    <?= $sal->salary ?> บาท
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="col-md-9">
                <h3>กำลังดำเนินการ</h3>
                <hr>
                <div class="over-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-2">
                                            ข้อมูลร้านอาหาร
                                        </div>
                                        <div class="col-3 text-left">
                                        </div>
                                        <div class="col-3 text-right">
                                            ที่จัดส่ง
                                        </div>
                                        <div class="col-3 text-right">
                                            ราคา
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (COUNT($orders_working) > 0) {
                                foreach ($orders_working as $order) {
                            ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img style="width: 100%;" src="images/restaurant/<?= $order->restaurant->img ?>" alt="">
                                                </div>
                                                <div class="col-5 vertical-mid text-left">
                                                    <h5>ร้าน : <?= $order->restaurant->name ?></h5>
                                                    <p>
                                                        <?= $order->restaurant->address->address ?>
                                                        <?= $order->restaurant->address->district ?>
                                                        <?= $order->restaurant->address->amphure ?>
                                                        <?= $order->restaurant->address->zip_code ?>
                                                    </p>
                                                    <p>รายการอาหาร : <?= $order->product->name ?></p>
                                                    <p>จำนวน : <?= $order->product->amount ?></p>
                                                    <p><a href="https://www.google.com/maps/search/?api=1&query=<?= $order->restaurant->address->address ?>+<?= $order->restaurant->address->district ?>+<?= $order->restaurant->address->amphure ?>" class="btn btn-primary" target="_blank">ดู Map</a></p>

                                                </div>

                                                <div class="col-3 vertical-mid text-left">
                                                    <h5><?= $order->user->first_name ?> <?= $order->user->last_name ?></h5>
                                                    <p>โทร : <?= $order->user->tel ?></p>
                                                    <p><?= $order->user_address->address ?>
                                                        <?= $order->user_address->district ?>
                                                        <?= $order->user_address->amphure ?>
                                                        <?= $order->user_address->zip_code ?></p>
                                                    <p><a href="https://www.google.com/maps/search/?api=1&query=<?= $order->user_address->address ?>+<?= $order->user_address->district ?>+<?= $order->user_address->amphure ?>" class="btn btn-primary" target="_blank">ดู Map</a></p>
                                                </div>
                                                <div class="col-2 vertical-mid">
                                                    <p><a href="service/order.php?method=sent_success<?= $order->payment_chanel == 'CASH' ? '_cash' : '' ?>&order_product_list_id=<?= $order->order_product_list_id ?>" class="btn btn-success">ส่งเรียบร้อย</a></p>
                                                    <p>ค่าจัดส่ง <?= $order->shipping ?> บาท</p>
                                                    <?php
                                                    if ($order->payment_chanel != 'CASH') {
                                                    ?>
                                                        <h4>จ่ายเรียบร้อยแล้ว</h4>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <h4>เก็บเงินสด : <?= $order->product->price_total ?> บาท</h4>
                                                    <?php
                                                    }
                                                    ?>
                                                    <p><a href="service/order.php?method=cancel&order_product_list_id=<?= $order->order_product_list_id ?>" class="cancel-btn">ยกเลิกรายการ</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<h3 class="status_free">ยังไม่ได้รับงาน</h3>';
                            }
                            ?>

                        </div>
                    </div>
                    <div class="row mt-4">
                        <h3>ประวัติการส่ง</h3>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2">
                                                ข้อมูลร้านอาหาร
                                            </div>
                                            <div class="col-3 text-left">
                                            </div>
                                            <div class="col-3 text-right">
                                                ที่จัดส่ง
                                            </div>
                                            <div class="col-3 text-right">
                                                ราคา
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (COUNT($orders_history) > 0) {
                                    foreach ($orders_history as $order) {
                                ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <img style="width: 100%;" src="images/restaurant/<?= $order->restaurant->img ?>" alt="">
                                                    </div>
                                                    <div class="col-5 vertical-mid text-left">
                                                        <h5>ร้าน : <?= $order->restaurant->name ?></h5>
                                                        <p>
                                                            <?= $order->restaurant->address->address ?>
                                                            <?= $order->restaurant->address->district ?>
                                                            <?= $order->restaurant->address->amphure ?>
                                                            <?= $order->restaurant->address->zip_code ?>
                                                        </p>
                                                        <p>รายการอาหาร : <?= $order->product->name ?></p>
                                                        <p>จำนวน : <?= $order->product->amount ?></p>
                                                    </div>

                                                    <div class="col-3 vertical-mid text-left">
                                                        <h5><?= $order->user->first_name ?> <?= $order->user->last_name ?></h5>
                                                        <p>โทร : <?= $order->user->tel ?></p>
                                                        <p><?= $order->user_address->address ?>
                                                            <?= $order->user_address->district ?>
                                                            <?= $order->user_address->amphure ?>
                                                            <?= $order->user_address->zip_code ?></p>

                                                    </div>
                                                    <div class="col-2 vertical-mid">
                                                        <h4>ค่าจัดส่ง <?= $order->shipping ?> บาท</h4>
                                                        <p><?= $order->express_datetime ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<h3 class="status_free">ไม่มีประวัติการส่ง</h3>';
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>