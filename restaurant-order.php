<?php
require 'condb.php';


if (!isset($_SESSION['restaurant_id']) || !isset($_SESSION['id'])) {
    header("Refresh:0; login.php");
    return;
}

$is_upsert = false;
$upsert_success = false;
$restaurant_id = 0;
$select_day = isset($_GET['select_day']) ? $_GET['select_day'] : "";
$select_genre = isset($_GET['select_genre']) ? $_GET['select_genre'] : "";
$select_status =  isset($_GET['select_status']) ? $_GET['select_status'] : "";


if (isset($_GET['method']) && isset($_GET['order_product_list_id'])) {
    $order_product_list_id = $_GET['order_product_list_id'];
    $sql = "UPDATE `order_product_list` SET status = '" . $_GET['method'] . "' WHERE id = '$order_product_list_id'";
    mysqli_query($conn, $sql);
}

$restaurant_id = $_SESSION['restaurant_id'];

$query_type = $select_genre != "" ? "AND product.genre = '$select_genre'" : "";
$query_day = $select_day != "" ? "AND product.day LIKE '%$select_day%'" : "";
$query_status = $select_status != "" ? "AND order_p.status = '$select_status'" : "";

$orders = [];
$sql = "SELECT *,order_p.id as order_product_list_id, order_product.created_at as order_created_at FROM `order_product_list` as order_p
    INNER JOIN order_product ON order_product.id = order_p.order_id
    INNER JOIN user ON order_product.user_id = user.id
    INNER JOIN product ON product.id = order_p.product_id
    WHERE order_p.status != 'success' AND order_p.product_id in (SELECT id FROM `product` WHERE `restaurant_id` = '$restaurant_id') 
    $query_type $query_day $query_status ORDER BY order_p.id DESC";

$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $order = new Order();
        $order->id = $row['order_id'];
        $order->created_at = $row['order_created_at'];
        $order->order_product_list_id = $row['order_product_list_id'];
        $order->status = $row['status'];

        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['name'];
        $product->genre = $row['genre'];
        $product->img = $row['img'];
        $product->day = $row['day'];
        $product->amount = $row['amount'];
        $order->product = $product;

        $user = new User();
        $user->id = $row['user_id'];
        $user->first_name = $row['first_name'];
        $user->last_name = $row['last_name'];
        $user->tel = $row['tel'];
        $order->user = $user;

        array_push($orders, $order);
    }
}

if (isset($_POST['product_name'])) {
    $is_upsert = true;
    $upsert_success = CreateProduct($conn);
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

    ?>
    <style>
        .product_table img {
            width: 100px;
        }

        .product_table tbody td,
        .product_table thead th {
            text-align: left;
            vertical-align: middle !important;
        }

        p {
            margin-bottom: 5px;
        }
    </style>
    <div class="container layout-1">
        <div class="row mt-4">
            <form class="form-inline" method="get" action="restaurant-order.php">
                <div class="form-group mt-4">
                    <select class="form-control" id="select_genre" name="select_genre" onchange="OnSelect()">
                        <option value="">-- ทุกประเภท --</option>
                        <option value="food" <?= $select_genre == "food" ? "selected" : "" ?>>อาหาร</option>
                        <option value="sweet" <?= $select_genre == "sweet" ? "selected" : "" ?>>ของหวาน</option>
                    </select>
                </div>
                <div class="form-group mt-4 ml-2">
                    <select class="form-control" id="select_day" name="select_day" onchange="OnSelect()">
                        <option value="">-- ทุกวัน --</option>
                        <option value="sunday" <?= $select_day == "sunday" ? "selected" : "" ?>>วันอาทิตย์</option>
                        <option value="monday" <?= $select_day == "monday" ? "selected" : "" ?>>วันจันทร์</option>
                        <option value="tuesday" <?= $select_day == "tuesday" ? "selected" : "" ?>>วันอังคาร</option>
                        <option value="wednesday" <?= $select_day == "wednesday" ? "selected" : "" ?>>วันพุธ</option>
                        <option value="thursday" <?= $select_day == "thursday" ? "selected" : "" ?>>วันพฤหัสบดี</option>
                        <option value="friday" <?= $select_day == "friday" ? "selected" : "" ?>>วันศุกร์</option>
                        <option value="saturday" <?= $select_day == "saturday" ? "selected" : "" ?>>วันเสาร์</option>
                    </select>
                </div>

                <div class="form-group mt-4 ml-2">
                    <select class="form-control" id="select_status" name="select_status" onchange="OnSelect()">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="confirm" <?= $select_status == "confirm" ? "selected" : "" ?>>รับออร์เดอร์แล้ว</option>
                        <option value="wait" <?= $select_status == "wait" ? "selected" : "" ?>>รอยืนยัน</option>
                        <option value="cancel" <?= $select_status == "cancel" ? "selected" : "" ?>>ยกเลิก</option>
                    </select>
                </div>
                <input type="submit" id="select_filter" style="display: none;">
            </form>

            <table class="table table-striped product_table mt-2">
                <thead>
                    <tr>
                        <th scope="col" width="50">#</th>
                        <th scope="col" width="100"></th>
                        <th scope="col" width="350">ข้อมูล</th>
                        <th scope="col" width="100">สถานะ</th>
                        <th scope="col" width="400">ผู้สั่งซื้อ</th>
                        <th scope="col" width="250"></th>
                    </tr>
                </thead>
            </table>
            <div style="max-height: 60vh;overflow-y: scroll;">
                <table class="table table-striped product_table mt-2">
                    <tbody>
                        <?php
                        if (COUNT($orders) > 0) {
                            foreach ($orders as $order) {

                        ?>
                                <tr>
                                    <td width="50" scope="row"><?= $order->id; ?></td>
                                    <td width="50"><img src="images/product/<?= $order->product->img ?>" alt=""></td>
                                    <td width="400">
                                        <h4><?= $order->product->name ?></h4>
                                        <p>จำนวน : <?= $order->product->amount ?></p>
                                        <p>
                                            <?= $order->product->genre == "food" ? "อาหาร" : "" ?>
                                            <?= $order->product->genre == "sweet" ? "ของหวาน" : "" ?></p>
                                    </td>
                                    <td width="150">
                                        <h5>
                                            <?= $order->status == "wait" ? "อาหาร" : "" ?>
                                            <?= $order->status == "confirm" ? "ยืนยันรับออร์เดอร์" : "" ?>
                                            <?= $order->status == "call_rider" ? "เรียกไรเดอร์" : "" ?>
                                            <?= $order->status == "rider_recieve" ? "ไรเดอร์กำลังมา..." : "" ?>
                                            <?= $order->status == "cancel" ? "ยกเลิก" : "" ?>
                                        </h5>
                                    </td>
                                    <td width="400">
                                        <h5><?= $order->user->first_name ?> <?= $order->user->last_name ?></h5>
                                        <p>โทร : <?= $order->user->tel ?></p>
                                        <p>วันที่ส่ง : <?= GetNextDay($order->product->day) ?></p>
                                    </td>
                                    <td width="300" class="text-right">
                                        <?php
                                        if ($order->status == "wait") {
                                        ?>
                                            <a href="restaurant-order.php?method=confirm&order_product_list_id=<?= $order->order_product_list_id ?>" style="margin-right: 20px;" class="btn btn-success r-2">รับออร์เดอร์</a>
                                        <?php
                                        } else if ($order->status == "confirm") {
                                        ?>
                                            <a href="restaurant-order.php?method=call_rider&order_product_list_id=<?= $order->order_product_list_id ?>" style="margin-right: 20px;" class="btn btn-warning r-2">เรียกไรเดอร์มารับ</a>
                                        <?php
                                        } else if ($order->status == "call_rider") {
                                        ?>
                                            <a href="restaurant-order.php?method=confirm&order_product_list_id=<?= $order->order_product_list_id ?>" style="margin-right: 20px;" class="btn btn-warning r-2">ยกเลิกเรียกไรเดอร์</a>
                                        <?php
                                        }
                                        if ($order->status == "cancel") {
                                            echo "<h6>ยกเลิกแล้ว</h6>";
                                        } else {
                                        ?>
                                            <a href="#" onclick="CancelOrder(<?= $order->order_product_list_id ?>)" style="margin-right: 20px;" class="cancel-btn r-2">ยกเลิก</a>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
                <?php
                if (COUNT($orders) <= 0) {
                    echo '<h1 style="text-align: center;font-size: 40px;padding: 30px 0;color: #ccc;">ไม่มีรายการสั่งซื้อเข้ามา</h1>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    setTimeout(() => {
        if (<?= json_encode($is_upsert) ?>) {
            if (<?= json_encode($upsert_success) ?>) {
                SweetAlert('เรียบร้อย', 'success');
            } else {
                SweetAlert('ผิดพลาด', 'warning');
            }
        }
    }, 100);

    function OnSelect() {
        $('#select_filter').click();
    }

    function CancelOrder(id) {
        SweetAlertConfirm('ยืนยันการยกเลิกออร์เดอร์', 'warning', 'restaurant-order.php?method=cancel&order_product_list_id=' + id);
    }
</script>