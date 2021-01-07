<?php
require 'condb.php';
$user_id = 0;
$is_update = false;
$is_delete = false;
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    require 'service/cart.php';
    if (isset($_GET['amount']) && isset($_GET['product_id'])) {
        $is_update = UpdateCartProduct($conn, $_GET['amount'], $_GET['product_id']);
    } elseif (isset($_GET['product_id']) && isset($_GET['delete'])) {
        $is_delete = DeleteCarProduct($conn, $_GET['product_id']);
    } else if (isset($_GET['product_id'])) {
        AddCartProduct($conn);
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

<body>

    <?php
    require './components/header.php';

    ?>
    <!-- <link rel="stylesheet" type="text/css" href="js/cart.js"> -->
    <link rel="stylesheet" type="text/css" href="css/cart.css">
    <style>

    </style>
    <div class="container layout-1">
        <div class="wrap cf">
            <!-- <h1 class="projTitle">คำนวณราคาสินค้าทั้งหมด</h1> -->
            <div class="heading cf">
                <h1>ตระกร้าสินค้า</h1>
                <a href="index.php" class="continue">ช้อปปิ้งต่อ</a>
            </div>
            <div class="cart">
                <ul class="cartWrap">
                    <?php
                    $sum = 0;
                    $sql = "SELECT *,product.id as product_id FROM cart 
                    LEFT JOIN product ON product.id = cart.product_id
                    WHERE cart.user_id = '$user_id'";
                    $result = mysqli_query($conn, $sql);
                    $count_product = 0;
                    if ($result) {
                        $count_product = mysqli_num_rows($result);
                        if ($count_product > 0) {
                            if (mysqli_num_rows($result)) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $product = new Product();
                                    $product->id = $row['product_id'];
                                    $product->img = $row['img'];
                                    $product->description = $row['description'];
                                    $product->price = $row['price'];
                                    $product->name = $row['name'];
                                    $amount = $row['amount'];
                                    $product_price = $amount * $product->price;
                                    $sum += $product_price;
                    ?>
                                    <li class="items odd">

                                        <div class="infoWrap">
                                            <div class="cartSection">
                                                <img src="images/product/<?= $product->img ?>" alt="" class="itemImg" />
                                                <!-- <p class="itemNumber">#QUE-007544-002</p> -->
                                            </div>

                                            <div class="cartSection">
                                                <h3><?= $product->name ?></h3>

                                                <p class="stockStatus"> จำนวน</p>
                                                <p>
                                                    <input type="text" class="qty" value="<?= $amount ?>" min="1" max="10" onchange="UpdateAmount(this.value,<?= $product->id ?>)" /> x <?= $product->price ?>
                                                </p>


                                            </div>
                                            <div class="prodTotal cartSection">
                                                <p><?= $product_price ?> บาท</p>
                                            </div>
                                            <div class="cartSection removeWrap">

                                                <a href="cart.php?delete=true&product_id=<?= $product->id ?>" class="remove" id="delete"> x </a>

                                                <input type="hidden" name="" id="sum" value="<?php echo $sum ?>">
                                                <input type="hidden" name="" id="cart" value=" <?= $product->id ?>">

                                            </div>
                                        </div>
                                    </li>
                    <?php
                                }
                            }
                        } else {
                            echo '<h1 style="text-align: center;font-size: 40px;padding: 30px 0;color: #ccc;">ไม่มีสินค้าในตระกร้า</h1>';
                        }
                    }

                    ?>

                </ul>
            </div>

            <div class="subtotal cf">
                <ul>
                    <!-- <li class="totalRow"><span class="label">Subtotal</span><span class="value">
                            <div> <?php echo $sum ?> บาท</div>
                        </span></li> -->




                    <li class="totalRow final"><span class="label">ยอดสุทธิ</span><span class="value">
                            <div> <?php echo $sum ?> บาท</div>
                        </span></li>
                    <?php
                    if ($count_product > 0) {
                    ?>
                        <li class="totalRow"><a href="payment.php" class="btn continue" id="addorder">ชำระเงิน</a></li>
                    <?php
                    }
                    ?>
                </ul>

            </div>
        </div>


    </div>

</body>
<script>
    function UpdateAmount(amount, product_id) {
        setTimeout(() => {
            amount = amount > 10 ? 10 : amount;
            window.location.href = 'cart.php?amount=' + amount + '&product_id=' + product_id;
        }, 100);
    }
</script>

</html>