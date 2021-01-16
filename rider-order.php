<?php
require 'condb.php';

if (!isset($_SESSION['id']) || $_SESSION['type'] != 3) {
    header("Refresh:0; login.php");
    return;
}

$is_upsert = false;
$upsert_success = false;
$restaurant_id = 0;
$amphure_id = isset($_GET['amphure_id']) ? $_GET['amphure_id'] : "";
$district_id = isset($_GET['district_id']) ? $_GET['district_id'] : "";
$amphure_name = "";
$district_name = "";


if (isset($_GET['method']) && isset($_GET['order_product_list_id'])) {
    $order_product_list_id = $_GET['order_product_list_id'];
    $sql = "UPDATE `order_product_list` SET status = '" . $_GET['method'] . "' WHERE id = '$order_product_list_id'";
    mysqli_query($conn, $sql);
}

$query_address = "";
if ($amphure_id != "" && $district_id != "") {
    $sql = "SELECT amphures.name_th as amphure_name, districts.name_th as district_name FROM amphures INNER JOIN districts ON districts.amphure_id = amphures.id WHERE districts.id = '$district_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $amphure_name = $row['amphure_name'];
    $district_name = $row['district_name'];
    $amphure_name = str_replace("'", "", $amphure_name);
    $district_name = str_replace("'", "", $district_name);
    $query_address = "AND address.amphure = '$amphure_name' AND address.district = '$district_name'";
}


$orders = [];
$sql = "SELECT *,order_p.id as order_product_list_id, order_product.created_at as order_created_at,order_p.price as order_price  FROM `order_product_list` as order_p
    INNER JOIN order_product ON order_product.id = order_p.order_id
    INNER JOIN user ON order_product.user_id = user.id
    INNER JOIN address ON address.id = order_product.address_id
    INNER JOIN product ON product.id = order_p.product_id
    WHERE order_p.status = 'call_rider'
    $query_address ORDER BY order_p.id DESC";

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

        $address = new Address();
        $address->address = $row['address'];
        $address->amphure = $row['amphure'];
        $address->district = $row['district'];
        $address->zip_code = $row['zip_code'];
        $order->user_address = $address;

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

        .select-filter div {
            width: auto;
            margin-right: 20px;
        }
    </style>
    <div class="container layout-1">
        <div class="row mt-4">
            <form class="form-inline select-filter" method="get" action="rider-order.php">
                <?php require './components/select-address.php'; ?>
                <input type="submit" id="select_filter" value="ค้นหา" class="btn btn-primary">
                <div class="text-right" style="margin-left: 20px;">
                    <h4>ค้นหา : <?= $amphure_name != '' ? $amphure_name . " " . $district_name : 'ทั้งหมด' ?></h4>
                </div>
            </form>

            <table class="table table-striped product_table mt-2">
                <thead>
                    <tr>
                        <th scope="col" width="50">#</th>
                        <th scope="col" width="100"></th>
                        <th scope="col" width="350">ข้อมูล</th>
                        <th scope="col" width="500">ผู้สั่งซื้อ</th>
                        <th scope="col" width="250"></th>
                    </tr>
                </thead>
            </table>
            <div style="max-height: 60vh;overflow-y: scroll;">
                <table class="table table-striped product_table mt-2">
                    <tbody>
                        <?php
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
                                <td width="500">
                                    <h5><?= $order->user->first_name ?> <?= $order->user->last_name ?></h5>
                                    <p>โทร : <?= $order->user->tel ?></p>
                                    <p><?= $order->user_address->address ?>
                                        <?= $order->user_address->district ?>
                                        <?= $order->user_address->amphure ?>
                                        <?= $order->user_address->zip_code ?></p>
                                    <p><a href="https://www.google.com/maps/search/?api=1&query=<?= $order->user_address->address ?>+<?= $order->user_address->district ?>+<?= $order->user_address->amphure ?>" class="btn btn-primary" target="_blank">ดู Map</a></p>
                                </td>
                                <td width="300" class="text-right">
                                    <a href="./service/order.php?method=recieve_order&order_product_list_id=<?= $order->order_product_list_id ?>" style="margin-right: 20px;" class="btn btn-success r-2">รับส่งออร์เดอร์</a>

                                </td>
                            </tr>
                        <?php
                        }


                        ?>

                    </tbody>
                </table>
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


    function CancelOrder(id) {
        SweetAlertConfirm('ยืนยันการยกเลิกออร์เดอร์', 'warning', 'restaurant-order.php?method=cancel&order_product_list_id=' + id);
    }
</script>