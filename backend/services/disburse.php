<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="../../js/sweet-alert.js"></script>
<?php
require '../../condb.php';

$company_bank_num = 1345975465842145;
$cvv = 123;

if (isset($_GET['restaurant_id']) && isset($_GET['disburse_price']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $restaurant_id = $_GET['restaurant_id'];
    $disburse_price = $_GET['disburse_price'];

    $where = "bank_card.num = '$company_bank_num' AND cvv = '$cvv'";
    $sql = "SELECT type,cash FROM bank_card WHERE $where";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);

    if ($data['cash'] < $disburse_price) { // เช็คเงินในบัญชีบริษัท
        SweetAlert('เงินในบัญชีไม่เพียงพอ', 'warning', '../restaurant.php');
        header("Refresh:4; ../restaurant.php");
        return;
    }
    $type = $data['type'];
    $sql = "UPDATE bank_card SET cash = cash - $disburse_price 
    WHERE $where";
    mysqli_query($conn, $sql);

    $sql = "SELECT bank_card.num FROM restaurant as res
    INNER JOIN user ON user.id = res.user_id
    INNER JOIN bank_user ON user.id = bank_user.user_id
    INNER JOIN bank_card ON bank_card.num = bank_user.num
    WHERE bank_card.type = 'DEBIT' AND res.id = '$restaurant_id'";
    $result = mysqli_query($conn, $sql); // หาบัญชีร้านค้า debit
    if (mysqli_num_rows($result) <= 0) {
        SweetAlert('ร้านค้าไม่มีบัญชีรับ', 'warning', '../restaurant.php');
        header("Refresh:4; ../restaurant.php");
        return;
    }
    $data = mysqli_fetch_assoc($result);
    $num = $data['num'];
    $sql = "UPDATE bank_card SET cash = cash + $disburse_price WHERE num = '$num'";
    mysqli_query($conn, $sql);

    $admin_user_id = $_SESSION['id'];
    $sql = "INSERT INTO `transaction`( `transfer_user_id`, `recieve_user_id`, `cash`, `type`) VALUES ('$admin_user_id','$user_id','$disburse_price','$type')";
    $result = mysqli_query($conn, $sql);

    $sql = "UPDATE order_product_list SET status = 'success' WHERE status = 'sent_success' product_id IN (SELECT id FROM product WHERE restaurant_id = '$restaurant_id')";
    $update = mysqli_query($conn, $sql);

    if ($result && $update) {
        SweetAlert('ชำระเรียบร้อย', 'success', '../restaurant.php');
    } else {
        SweetAlert('ผิดพลาด', 'error', '../restaurant.php');
    }
    // header("Refresh:4; ../restaurant.php");
}
