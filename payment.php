<?php
require 'condb.php';


if (!isset($_SESSION['id'])) {
    header("Refresh:0; login.php");
    return;
}

$purchase_success = false;
$cash_not_enough = false;
require './service/order.php';

$user_id = $_SESSION['id'];
$total_sum = 0;
$products = [];
$cart_id = 0;
$sql = "SELECT *,cart.id as cart_id,product.id as product_id FROM cart 
    LEFT JOIN product ON product.id = cart.product_id
    WHERE cart.user_id = '$user_id' ORDER BY cart.id DESC";

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
        $product->price_total = $row['amount'] * $product->price;
        $product->cart_id = $row['cart_id'];

        $total_sum += $product->price_total;
        array_push($products, $product);
    }
}

$bank_cards = [];
$sql = "SELECT * FROM bank_user
INNER JOIN bank_card ON bank_user.num = bank_card.num
 WHERE bank_user.user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bank_card = new BankCard();
            $bank_card->id = $row['id'];
            $bank_card->name = $row['name'];
            $bank_card->num = $row['num'];
            $bank_card->type = $row['type'];
            $bank_card->cash = $row['cash'];
            $bank_cards[$bank_card->id] = $bank_card;
        }
    }
}

$address = [];
$sql = "SELECT * FROM address WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $add = new Address();
            $add->id = $row['id'];
            $add->address = $row['address'];
            $add->amphure = $row['amphure'];
            $add->district = $row['district'];
            $add->zip_code = $row['zip_code'];
            $address[$add->id] = $add;
        }
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
        <form action="payment.php" method="post">
            <div class="row mt-4">
                <h3>รายการสินค้า</h3>
                <hr>
                <div class="row">
                    <div class="col-12">
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
                                        </div>
                                        <div class="col-3 vertical-mid">
                                            <h6> จัดส่งวันที่ : <?= GetNextDay($product->day) ?></h6>
                                        </div>
                                        <div class="col-3 vertical-mid">
                                            <p>จำนวน : <?= $product->amount ?></p>
                                            <h4><?= $product->price_total ?> บาท</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="cart_id[]" value="<?= $product->cart_id ?>">
                            <input type="hidden" name="product_id[]" value="<?= $product->id ?>">
                            <input type="hidden" name="amount[]" value="<?= $product->amount ?>">
                            <input type="hidden" name="price[]" value="<?= $product->price ?>">
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <h3>เลือกสถานที่จัดส่ง</h3>
                <hr>
                <div class="row">
                    <?php
                    if (count($address) > 0) {
                        foreach ($address as $add) {
                    ?>
                            <div class="form-check col-md-4">
                                <label class="form-check-label" style="width: 100%;" for="address_<?= $add->id ?>">
                                    <div class="card col-12">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-2">
                                                    <input class="form-check-input" type="radio" name="select_address" id="address_<?= $add->id ?>" value="<?= $add->id ?>" checked>
                                                </div>
                                                <div class="col-10">
                                                    <?= $add->address ?>
                                                    <?= $add->district ?>
                                                    <?= $add->amphure ?>
                                                    <?= $add->zip_code ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                        <?php
                        }
                    } else {
                        ?>
                        <a href="profile.php#address">
                            <div class="card col-12">
                                <div class="card-body">
                                    <p class='text-center'>เพิ่มสถานที่จัดส่ง</p>
                                </div>
                            </div>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row mt-4">
                <h3>ช่องทางการชำระ</h3>
                <hr>
                <div class="row">
                    <?php
                    if (count($bank_cards) > 0) {
                        foreach ($bank_cards as $bank) {
                    ?>
                            <div class="form-check col-md-4">
                                <label class="form-check-label" style="width: 100%;" for="bank_<?= $bank->id ?>">
                                    <div class="card col-12">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-1">
                                                    <input class="form-check-input" type="radio" name="select_payment" id="bank_<?= $bank->id ?>" value="<?= $bank->num ?>" required>
                                                </div>
                                                <div class="col-3">
                                                    <?php
                                                    if ($bank->type == 1) {
                                                        echo "Debit";
                                                    } else {
                                                        echo "VISA";
                                                    } ?>
                                                </div>
                                                <div class="col-8">
                                                    <?= $bank->name ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                    <?php
                        }
                    }
                    ?>
                    <div class="form-check col-md-4">
                        <label class="form-check-label" style="width: 100%;" for="bank_cash">
                            <div class="card col-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-1">
                                            <input class="form-check-input" type="radio" name="select_payment" id="bank_cash" value="cash" checked>
                                        </div>
                                        <div class="col-3">

                                        </div>
                                        <div class="col-8">
                                            เงินสด
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="total_price" value="<?= $total_sum ?>">
            <input type="submit" style="display: none;" name="" id="submit">
        </form>
    </div>
    <div style="position: fixed;bottom: 0;width: 100%;height: 140px;background-color: #fff;padding: 20px;border-top: 1px solid #ccc;">
        <div class="row">
            <div class="col-6">
                <h2>ยอดรวม</h2>
            </div>
            <div class="col-6 text-right">
                <h2><?= $total_sum ?> บาท</h2>
            </div>
        </div>
        <button id="submit_btn" type="button" class="btn btn-primary" style="width: 100%;">
            ชำระเงิน
        </button>
    </div>

</body>

</html>
<script>
    $('#submit_btn').on('click', () => {
        if (<?= json_encode(COUNT($address)) ?> <= 0) {
            SweetAlert('กรุณาเลือกที่จัดส่ง', 'warning');
            return;
        }
        $('#submit').click();
    })

    setTimeout(() => {
        if (<?= json_encode(isset($_POST['total_price'])) ?>) {
            if (<?= json_encode($purchase_success) ?>) {
                SweetAlertOk('สั่งซื้อเรียบร้อย', 'success', 'order-list.php');
            } else if (<?= json_encode($cash_not_enough) ?>) {
                SweetAlert('จำนวนเงินไม่พอชำระ', 'warning');
            } else {
                SweetAlert('ไม่สามารถชำระได้ในขณะนี้', 'warning');
            }
        }
    }, 100);
</script>