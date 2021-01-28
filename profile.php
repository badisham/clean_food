<?php
require 'condb.php';

$add_bank = false;
$add_bank_success = "";

$update_account = false;
$update_account_success = false;

$change_password = false;
$change_password_success = "";

$user_id = 0;
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    require './service/user.php';
    if (isset($_POST['password_new'])) {
        $change_password = true;
        $change_password_success = ChangePassword($conn);
    } else if (isset($_POST['email'])) {
        $update_account = true;
        $update_account_success = UpdateAccount($conn);
    } else if (isset($_POST['district_id'])) {
        AddAddress($conn);
    } else if (isset($_GET['method']) && $_GET['method'] == "delete" && isset($_GET['address_id'])) {
        DeleteAddress($conn, $_GET['address_id']);
    }



    require './service/bank_card.php';
    if (isset($_POST['num'])) {
        $add_bank = true;
        $add_bank_success = AddBankCard($conn);
    } else if (isset($_GET['method']) && $_GET['method'] == "delete" && isset($_GET['num'])) {
        echo "<script>alert(" . $_GET['num'] . ")</script>";
        DeleteBankCard($conn, $_GET['num']);
    }

    if ($_SESSION['type'] == 3) {
        $rider_id = $_SESSION['rider_id'];

        $sql = "SELECT (SUM(price) - SUM(shipping)) as overdue FROM order_product_list WHERE rider_id = '$rider_id' AND status = 'sent_success_cash' AND express_datetime < now() - INTERVAL 1 DAY";
        $result = mysqli_query($conn, $sql);
        $overdue = mysqli_fetch_assoc($result)['overdue'];
        $overdue = number_format($overdue, 2, '.', '');


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
    }
} else {
    header("Refresh:0; login.php");
}

$user = new User();
$sql = "SELECT * FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user->id = $row['id'];
            $user->username = $row['username'];
            $user->first_name = $row['first_name'];
            $user->last_name = $row['last_name'];
            $user->email = $row['email'];
            $user->tel = $row['tel'];
            $user->type = $row['type'];
        }
    } else {
        header("Refresh:0; login.php");
    }
} else {
    header("Refresh:0; login.php");
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
    .wrap-box {
        padding: 20px;
    }

    .pointer-a {
        cursor: pointer;
    }
</style>

<body>

    <?php
    require './components/header.php';

    ?>

    <div class="container layout-1">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="profile.php">
                    <div class="row mt-4 shadow-lg col-md-6 wrap-box" style="margin: 0 auto;">

                        <div class="col-12 text-center">
                            <h4><strong>โปรไฟล</strong></h4>
                        </div>

                        <div class="col-12">
                            <div id="loginwithemail">
                                <div class="form-group mt-4">
                                    <label for="email">email</label>
                                    <input required type="email" id="email" class="form-control bg-light" name="email" placeholder="email" value="<?= $user->email ?>">
                                </div>

                                <div class="form-group">
                                    <label for="first_name">ชื่อ</label>
                                    <input required type="text" id="first_name" class="form-control bg-light" name="first_name" placeholder="ชื่อ" value="<?= $user->first_name ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">นามสกุล</label>
                                    <input required type="text" id="last_name" class="form-control bg-light" name="last_name" placeholder="นามสกุล" value="<?= $user->last_name ?>">
                                </div>
                                <div class="form-group">
                                    <label for="tel">เบอร์โทร</label>
                                    <input required type="text" id="tel" pattern="[0-9]+" class="form-control bg-light" name="tel" placeholder="เบอร์โทร" value="<?= $user->tel ?>">
                                </div>
                                <div class="text-center mt-4 mb-4 float-right">
                                    <button type="submit" name="reg_user" class="btn btn-warning rounded pl-4 pr-4">
                                        บันทึก
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form method="POST" action="profile.php">
                    <div class="row mt-4 shadow-lg col-md-6 wrap-box" style="margin: 0 auto;">

                        <div class="col-12 text-center">
                            <h3><strong>แก้รหัสผ่าน</strong></h3>
                        </div>

                        <div class="col-12">
                            <div id="change_password">
                                <div class="form-group mt-4">
                                    <!-- <label for="password">รหัสผ่านปัจจุบัน</label> -->
                                    <input required type="password" id="password" class="form-control bg-light" name="password" placeholder="รหัสผ่านปัจจุบัน">
                                </div>
                                <div class="form-group mt-4">
                                    <!-- <label for="password">รหัสผ่านใหม่</label> -->
                                    <input required type="password" id="password" class="form-control bg-light" name="password_new" placeholder="รหัสผ่านใหม่">
                                </div>
                                <input required type="hidden" id="password" name="id" value="<?= $user->id ?>">
                                <div class="text-center mt-4 mb-4 float-right">
                                    <button type="submit" name="reg_user" class="btn btn-warning rounded pl-4 pr-4">
                                        บันทึก
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" id="address">
            <div class="col-12">
                <div class="row mt-4 shadow-lg col-md-6 wrap-box" style="margin: 0 auto;">

                    <h3 class="text-center">
                        <strong>สถานที่จัดส่ง</strong>
                    </h3>
                    <?php
                    if (count($address) > 0) {
                        foreach ($address as $add) {
                    ?>
                            <div class="card col-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-10">
                                            <?= $add->address ?>
                                            <?= $add->district ?>
                                            <?= $add->amphure ?>
                                            <?= $add->zip_code ?>
                                        </div>
                                        <div class="col-2 text-right">
                                            <a onclick="DeleteAddress(<?= $add->id ?>)" style="color: red;" class="pointer-a">ลบ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="card col-12">
                            <div class="card-body">
                                <p class='text-center'>ไม่มีสถานที่จัดส่ง</p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mt-4 float-right" data-bs-toggle="modal" data-bs-target="#AddAddress">
                        เพิ่มสถานที่
                    </button>
                </div>
            </div>
        </div>
        <div class="row" id="bank_card">
            <div class="col-12">
                <div class="row mt-4 shadow-lg col-md-6 wrap-box" style="margin: 0 auto;">

                    <h3 class="text-center">
                        <strong>ช่องทางการชำระ <?= $_SESSION['type'] == 2 || $_SESSION['type'] == 3 ? 'หรือรับเงิน (Debit)' : '' ?></strong>
                    </h3>
                    <?php
                    if (count($bank_cards) > 0) {
                        foreach ($bank_cards as $bank) {
                    ?>
                            <div class="card col-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-2"><?= $bank->type ?></div>
                                        <div class="col-6">
                                            <?= $bank->name ?>
                                        </div>
                                        <div class="col-4 text-right">
                                            <a onclick="DeleteBank(<?= $bank->num ?>)" style="color: red;" class="pointer-a">ลบ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="card col-12">
                            <div class="card-body">
                                <p class='text-center'>ไม่มีช่องทาง</p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mt-4 float-right" data-bs-toggle="modal" data-bs-target="#AddCreditCard">
                        เพิ่มช่องทาง
                    </button>
                </div>
            </div>
        </div>



        <?php
        if ($_SESSION['type'] == 3) {
        ?>
            <div class="row" id="overdue">
                <div class="col-12">
                    <div class="row mt-4 shadow-lg col-md-6 wrap-box" style="margin: 0 auto;">

                        <h3 class="text-center">
                            <strong>ยอดค้าชำระ</strong>
                        </h3>
                        <div class="card col-12">
                            <div class="card-body">
                                <h3 class='text-center'>
                                    <?= $overdue > 0 ? $overdue . ' บาท' : 'ไม่มียอดชำระ' ?>
                                </h3>
                            </div>
                        </div>
                        <!-- Button trigger modal -->
                        <button type="button" <?= $overdue == 0 ? 'disabled' : '' ?> class="btn btn-primary mt-4 float-right" data-bs-toggle="modal" data-bs-target="<?= COUNT($bank_cards) > 0 ? '#PayOverdue' : '#AddCreditCard' ?>">
                            ชำระ
                        </button>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    </div>




    <!-- Modal -->
    <div class="modal fade" id="AddCreditCard" tabindex="1" aria-labelledby="AddCreditCardLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <form action="profile.php" method="post">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            เพิ่มช่องทางชำระ
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="cardNumber">
                                                เลขหน้าบัตร 16 หลัก</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="cardNumber" placeholder="เลขหน้าบัตร" required autofocus pattern="[0-9]{16}" maxlength="16" name="num" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-7 col-md-7">
                                                <div class="form-group">
                                                    <label for="expityMonth">
                                                        วันหมดอายุ</label>
                                                    <div class="row">
                                                        <div class="col-xs-5 col-lg-5 pl-ziro">
                                                            <input pattern="[0-9]{2}" maxlength="2" type="text" class="form-control" id="expityMonth" placeholder="MM" required name="expire_month" />
                                                        </div>
                                                        <div class="col-xs-5 col-lg-5 pl-ziro">
                                                            <input pattern="[0-9]{4}" maxlength="4" type="text" class="form-control" id="expityYear" placeholder="YYYY" required name="expire_year" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xs-5 col-md-5 pull-right">
                                                <div class="form-group">
                                                    <label for="cvCode">
                                                        รหัส CVV</label>
                                                    <input pattern="[0-9]{3}" maxlength="3" type="password" class="form-control" id="cvCode" placeholder="CV" required name="cvv" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <button type="submit" class="btn btn-primary btn-lg btn-block">เพิ่ม</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="AddAddress" tabindex="1" aria-labelledby="AddAddressLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <form action="profile.php" method="post">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            เพิ่มสถานที่จัดส่ง
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="address">
                                                ที่อยู่
                                            </label>
                                            <div class="input-group">
                                                <textarea class="form-control" name="address" id="address" cols="30" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <?php require './components/select-address.php'; ?>
                                            <div class="form-group col-xs-4 col-md-4">
                                                <label for="zip_code"></label>
                                                <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="รหัสไปรษณีย์" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br />
                                <button type="submit" class="btn btn-primary btn-lg btn-block">เพิ่ม</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="PayOverdue" tabindex="1" aria-labelledby="PayOverdueLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <form action="service/transaction.php" method="post">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            เลือกช่องทางชำระ
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row mt-4">
                                            <input type="hidden" name="overdue" value="<?= $overdue ?>">
                                            <?php
                                            if (count($bank_cards) > 0) {
                                                foreach ($bank_cards as $bank) {
                                            ?>
                                                    <div class="form-check col-12">
                                                        <label class="form-check-label" style="width: 100%;" for="bank_<?= $bank->id ?>">
                                                            <div class="card col-12">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-1">
                                                                            <input class="form-check-input" type="radio" name="select_payment" id="bank_<?= $bank->id ?>" value="<?= $bank->num . '-' . $bank->type ?>" required>
                                                                        </div>
                                                                        <div class="col-3"><?= $bank->type ?></div>
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
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <br />
                    <button type="submit" class="btn btn-primary btn-lg btn-block">เพิ่ม</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        setTimeout(() => {
            if (<?= json_encode($add_bank) ?>) {
                let msg = <?= json_encode($add_bank_success) ?>;
                if (msg == "duplicate") {
                    SweetAlert('มีบัตรนี้ในระบบแล้ว', 'warning');
                } else if (msg == "false") {
                    SweetAlert('ผิดพลาด', 'warning');
                } else if (msg == 'notfound') {
                    SweetAlert('ไม่พบบัตรในระบบ', 'warning');
                } else {
                    SweetAlert('เรียบร้อย', 'success');
                }
            } else if (<?= json_encode($update_account) ?>) {
                if (<?= json_encode($update_account_success) ?>) {
                    SweetAlert('เรียบร้อย', 'success');
                } else {
                    SweetAlert('ผิดพลาด', 'warning');
                }
            } else if (<?= json_encode($change_password) ?>) {
                let msg = <?= json_encode($change_password_success) ?>;
                if (msg === "notfound") {
                    SweetAlert('รหัสผ่านไม่ถูกต้อง', 'warning');
                } else if (msg === "true") {
                    SweetAlert('เรียบร้อย', 'success');
                } else {
                    SweetAlert('ผิดพลาด', 'warning');
                }
            }

        }, 100);

        function DeleteBank(num) {
            SweetAlertConfirm('ยืนยันการลบ', 'warning', 'profile.php?method=delete&num=' + num);
        }

        function DeleteAddress(id) {
            SweetAlertConfirm('ยืนยันการลบ', 'warning', 'profile.php?method=delete&address_id=' + id);
        }
    </script>

</body>

</html>