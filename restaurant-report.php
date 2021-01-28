<?php
require 'condb.php';


if (!isset($_SESSION['id']) || $_SESSION['type'] != 2) {
    header("Refresh:0; login.php");
    return;
}

$purchase_success = false;
require './service/order.php';

$user_id = $_SESSION['id'];
$restaurant_id = $_SESSION['restaurant_id'];
$total_sum = 0;
$products = [];
$sql = "SELECT *,product.id as product_id
    ,order_product_list.price as order_price 
    ,order_product.created_at as order_created_at
    FROM order_product_list
    INNER JOIN order_product ON order_product_list.order_id = order_product.id
    INNER JOIN product ON order_product_list.product_id = product.id
    WHERE product.restaurant_id = '$restaurant_id' AND order_product_list.status IN ('success','sent_success','sent_success_cash')
    ORDER BY order_created_at DESC";

$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product = new Product();
        $product->id = $row['product_id'];
        $product->img = $row['img'];
        $product->description = $row['description'];
        $product->price = $row['order_price'];
        $product->name = $row['name'];

        $product->day = ThaiDate($row['order_created_at']);

        $product->amount = $row['amount'];
        $product->status = $row['status'];
        $product->price_total = $row['amount'] * $product->price;

        $product->express_datetime = ThaiDate($row['express_datetime'], 2);
        $total_sum += $product->price_total;
        array_push($products, $product);
    }
}

$incomes = [];
$sql = "SELECT * FROM transaction WHERE recieve_user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $income = new Transaction();
    $income->id = $row['id'];
    $income->cash = $row['cash'];
    $income->created_at = $row['created_at'];
    array_push($incomes, $income);
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>
<style>
    .card img {
        height: 50px;
    }

    .vertical-mid {
        /* padding: 20px 20px 20px 0; */
        vertical-align: middle;
        text-align: right;
    }

    .layout-1 {
        padding-bottom: 160px;
    }

    .nav-item {
        width: 50%;
        text-align: center;
        font-size: 24px;
        padding: 5px 0;
    }
</style>

<body>

    <?php
    require './components/header.php';
    ?>
    <div class="container layout-1">
        <div class="row mt-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="true">รายการสั่งซื้อ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="revenue-tab" data-toggle="tab" href="#revenue" role="tab" aria-controls="revenue" aria-selected="false">รายได้</a>
                </li>
            </ul>
            <hr>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="order" role="tabpanel" aria-labelledby="order-tab">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-1">

                                        </div>
                                        <div class="col-4 text-left">
                                            ข้อมูล
                                        </div>
                                        <div class="col-2 text-right">
                                            สถานะ
                                        </div>
                                        <div class="col-2 text-right">
                                            จำนวน
                                        </div>
                                        <div class="col-2 text-right">
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
                                            <div class="col-1">
                                                <img src="images/product/<?= $product->img ?>" alt="">
                                            </div>
                                            <div class="col-4 vertical-mid text-left">
                                                <h4><?= $product->name ?></h4>
                                                <h6> จัดส่งวันที่ : <?= $product->express_datetime ?></h6>
                                            </div>
                                            <div class="col-2 vertical-mid">
                                                <?php
                                                if ($product->status == "success") {
                                                    echo "<h4>รับเงินเรียบร้อย</h4>";
                                                } else if ($product->status == 'sent_success' || $product->status == 'sent_success_cash') {
                                                    echo "<h4>รอรับยอดรวม</h4>";
                                                }
                                                ?>
                                            </div>
                                            <div class="col-2 vertical-mid">
                                                <h5>จำนวน : <?= $product->amount ?></h5>
                                            </div>
                                            <div class="col-2 vertical-mid">
                                                <h5><?= $product->price_total ?> บาท</h5>
                                                <?php
                                                if ($product->status == "wait") {
                                                ?>
                                                    <p><a href="" class="cancel-btn">ยกเลิกรายการ</a></p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            if (COUNT($products) <= 0) {
                                echo '<h1 style="text-align: center;font-size: 40px;padding: 30px 0;color: #ccc;">ไม่มีรายการสั่งซื้อ</h1>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="revenue" role="tabpanel" aria-labelledby="revenue-tab">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-1">
                                            #
                                        </div>
                                        <div class="col-6 text-right">
                                            ยอดเงิน
                                        </div>
                                        <div class="col-5 text-right">
                                            วันที่
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            foreach ($incomes as $income) {
                            ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-1">
                                                <?= $income->id ?>
                                            </div>
                                            <div class="col-6 text-right">
                                                <?= $income->cash ?>
                                            </div>
                                            <div class="col-5 ">
                                                <?= $income->created_at ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            if (COUNT($incomes) <= 0) {
                                echo '<h1 style="text-align: center;font-size: 40px;padding: 30px 0;color: #ccc;">ยังไม่มีรายรับ</h1>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</body>

</html>