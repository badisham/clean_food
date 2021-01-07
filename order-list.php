<?php
require 'condb.php';

$purchase_success = false;
require './service/order.php';

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $total_sum = 0;
    $products = [];
    $sql = "SELECT *,product.id as product_id FROM order_product_list
    INNER JOIN product ON order_product_list.product_id = product.id
    WHERE order_product_list.order_id in 
    (SELECT id as order_id FROM order_product WHERE user_id = '$user_id') ORDER BY order_product_list.id DESC";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product = new Product();
            $product->id = $row['product_id'];
            $product->img = $row['img'];
            $product->description = $row['description'];
            $product->price = $row['price'];
            $product->name = $row['name'];
            $product->day = $row['day'];
            $product->amount = $row['amount'];
            $product->status = $row['status'];
            $product->price_total = $row['amount'] * $product->price;

            $total_sum += $product->price_total;
            array_push($products, $product);
        }
    }
} else {
    header("Refresh:0; login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>
<style>
    .card img {
        height: 80px;
    }

    .vertical-mid {
        padding: 20px 20px 20px 0;
        vertical-align: middle;
        text-align: right;
    }

    .layout-1 {
        padding-bottom: 160px;
    }
</style>

<body>

    <?php
    require './components/header.php';
    ?>
    <div class="container layout-1">
        <div class="row mt-4">
            <h3>รายการสั่งซื้อ</h3>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">

                                </div>
                                <div class="col-3 text-left">
                                    ข้อมูล
                                </div>
                                <div class="col-3 text-right">
                                    สถานะ
                                </div>
                                <div class="col-3 text-right">
                                    ราคา
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    foreach ($products as $product) {
                    ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <img src="images/product/<?= $product->img ?>" alt="">
                                    </div>
                                    <div class="col-3 vertical-mid text-left">
                                        <h4><?= $product->name ?></h4>
                                        <h6> จัดส่งวันที่ : <?= GetNextDay($product->day) ?></h6>
                                    </div>
                                    <div class="col-3 vertical-mid">
                                        <?php
                                        if ($product->status == "wait") {
                                            echo "<h4>รอวันจัดส่ง</h4>";
                                        } else if ($product->status == "cancel") {
                                            echo "<h4>ยกเลิกรายการ</h4>";
                                        } else if ($product->status == "delivery") {
                                            echo '<img src="images/delivery.gif" style="width: 100px;height: 50px" alt="">';
                                            echo "<h4>กำลังจัดส่ง</h4>";
                                        }
                                        ?>
                                    </div>
                                    <div class="col-3 vertical-mid">
                                        <p>จำนวน : <?= $product->amount ?></p>
                                        <h4><?= $product->price_total ?> บาท</h4>
                                        <p><a href="" class="cancel-btn">ยกเลิกรายการ</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>