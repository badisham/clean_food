<?php

if (isset($_GET['order_product_list_id']) && isset($_GET['method'])) {
    require '../condb.php';
    $val = 0;
    $status = "";
    $express_datetime = "";
    $method = $_GET['method'];
    if ($method == "sent_success" || $method == 'sent_success_cash') { // ส่งเรียบร้อย
        $val = -1;
        $status = $method;
        $express_datetime = ", express_datetime = NOW()";
    } else if ($method == "recieve_order") { // รับงานส่ง
        $val = 1;
        $status = 'rider_recieve';
    } else if ($method == "cancel") { // ยกเลิกการส่ง
        $val = -1;
        $status = 'call_rider';
    }
    $order_product_list_id = $_GET['order_product_list_id'];
    $rider_id = $_SESSION['rider_id'];
    $sql = "UPDATE `order_product_list` SET `rider_id`='$rider_id',`status`='$status' $express_datetime  WHERE id = '$order_product_list_id'";
    $result = mysqli_query($conn, $sql);
    $sql = "UPDATE `rider` SET `working_order`= working_order + $val WHERE id = '$rider_id'";
    $result = mysqli_query($conn, $sql);

    header("Refresh:0; ../rider-profile.php");
} else if (isset($_POST['product_id']) && isset($_POST['amount']) && isset($_POST['total_price']) && isset($_POST['price'])) { // ยืนยันสั่งออเดอร์

    $user_id = $_SESSION['id'];
    $product_ids = $_POST['product_id'];
    $cart_ids =  implode("','", $_POST['cart_id']);
    $amounts = $_POST['amount'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $address_id = $_POST['select_address'];

    $pay_bill_success = "";
    $success = false;
    if (isset($_POST['select_payment']) && $_POST['select_payment'] != 0) { // จ่ายผ่านบัตร
        $num_card = $_POST['select_payment'];
        $sql = "SELECT bank_card.cash as cash,type FROM `bank_user` INNER JOIN `bank_card` ON bank_card.num = bank_user.num WHERE bank_user.num = '$num_card'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $cash  = $row['cash'];
            $payment_chanel = $row['type'];
            if ($cash < $total_price) {
                SweetAlert('จำนวนเงินไม่พอชำระ', 'warning');
            } else {
                require 'bank_card.php';
                // หักเงินในบัตร
                $pay_bill_success = PayCard($conn, $num_card, $total_price);
                $sql = "INSERT INTO `transaction`( `transfer_user_id`, `recieve_user_id`, `cash`, `type`) VALUES ('$user_id','1','$total_price','$payment_chanel')";
                $result = mysqli_query($conn, $sql);
                $success = true;
            }
        } else {
            SweetAlert('ไม่มีบัตรนี้', 'warning');
        }
    } else { // เงินสด
        $success = true;
        $payment_chanel = "CASH";
    }
    if ($success) {
        $sql = "INSERT INTO `order_product`(`total_price`, `address_id`,`payment_chanel`,`user_id`) VALUES ('$total_price','$address_id','$payment_chanel','$user_id')";
        $result = mysqli_query($conn, $sql);
        $order_id = mysqli_insert_id($conn);
        if ($result) {
            if ($pay_bill_success == 'success') {
                $sql = "INSERT INTO `transaction`(`transfer_user_id`, `recieve_user_id`, `cash`, `type`, `order_id`) VALUES ('$user_id','1','$total_price','$payment_chanel','$order_id')";
                mysqli_query($conn, $sql);
            }

            $shipping = CalShipping($price[0], $amouunt[0]);
            $query_list = "('$order_id','$product_ids[0]','$amounts[0]','$price[0]','wait','$shipping')";
            for ($i = 1; $i < COUNT($product_ids); $i++) {
                $shipping = CalShipping($price[$i], $amouunt[$i]);
                $query_list .= ",('$order_id','$product_ids[$i]','$amounts[$i]','$price[$i]','wait','$shipping')";
            }
            $sql = "INSERT INTO `order_product_list`(`order_id`, `product_id`, `amount`, `price`, `status`, `shipping`) VALUES $query_list";
            mysqli_query($conn, $sql);

            $sql = "DELETE FROM `cart` WHERE id in ('$cart_ids')";
            mysqli_query($conn, $sql);

            SweetAlert('สั่งซื้อเรียบร้อย', 'success', 'order-list.php');
            return;
        }
    }
}
