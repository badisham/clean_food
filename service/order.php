<?php


if (isset($_GET['order_product_list_id']) && isset($_GET['method']) && $_GET['method'] == "success") { // ส่งเรียบร้อย
    require '../condb.php';
    $order_product_list_id = $_GET['order_product_list_id'];
    $rider_id = $_SESSION['rider_id'];
    $sql = "UPDATE `order_product_list` SET `rider_id`='$rider_id',`status`='success' , `express_datetime` = NOW() WHERE id = '$order_product_list_id'";
    $result = mysqli_query($conn, $sql);
    $sql = "UPDATE `rider` SET `working_order`= working_order - 1 WHERE id = '$rider_id'";
    $result = mysqli_query($conn, $sql);

    header("Refresh:0; ../rider-profile.php");
} else if (isset($_GET['order_product_list_id']) && isset($_GET['method']) && $_GET['method'] == "cancel") { // ยกเลิกการส่ง
    require '../condb.php';
    $order_product_list_id = $_GET['order_product_list_id'];
    $rider_id = $_SESSION['rider_id'];
    $sql = "UPDATE `order_product_list` SET `rider_id`='$rider_id',`status`='call_rider'  WHERE id = '$order_product_list_id'";
    $result = mysqli_query($conn, $sql);
    $sql = "UPDATE `rider` SET `working_order`= working_order - 1 WHERE id = '$rider_id'";
    $result = mysqli_query($conn, $sql);

    header("Refresh:0; ../rider-profile.php");
} else if (isset($_GET['order_product_list_id']) && isset($_GET['method']) && $_GET['method'] == "recieve_order") {
    require '../condb.php';
    $order_product_list_id = $_GET['order_product_list_id'];
    $rider_id = $_SESSION['rider_id'];
    $sql = "UPDATE `order_product_list` SET `rider_id`='$rider_id',`status`='rider_recieve' WHERE id = '$order_product_list_id'";
    $result = mysqli_query($conn, $sql);
    $sql = "UPDATE `rider` SET `working_order`= working_order + 1 WHERE id = '$rider_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Refresh:0; ../rider-profile.php");
    } else {
        header("Refresh:0; ../rider-order.php");
    }
} else if (isset($_POST['product_id']) && isset($_POST['amount']) && isset($_POST['total_price']) && isset($_POST['price'])) {

    $user_id = $_SESSION['id'];
    $product_ids = $_POST['product_id'];
    $cart_ids =  implode("','", $_POST['cart_id']);
    $amounts = $_POST['amount'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $address_id = $_POST['select_address'];

    $pay_bill_success = false;
    if (isset($_POST['select_payment']) && $_POST['select_payment'] != 0) { // Pay with card credit
        $num_card = $_POST['select_payment'];
        $sql = "SELECT bank_card.cash as cash FROM `bank_user` INNER JOIN `bank_card` ON bank_card.num = bank_user.num WHERE bank_user.num = '$num_card'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $cash = mysqli_fetch_assoc($result)['cash'];
            if ($cash < $total_price) {
                $cash_not_enough = true;
                return "notenough";
            }

            $sql = "UPDATE `bank_card` SET `cash`= cash - $total_price WHERE num = '$num_card'";
            mysqli_query($conn, $sql);
            $pay_bill_success = true;
        } else {
            return "notcard";
        }
    }

    $sql = "INSERT INTO `order_product`(`total_price`, `address_id`,`user_id`) VALUES ('$total_price','$address_id','$user_id')";
    $result = mysqli_query($conn, $sql);
    $order_id = mysqli_insert_id($conn);
    if ($result) {
        if ($pay_bill_success) { // หักเงินในบัตร
            $sql = "INSERT INTO `payment`( `order_id`, `user_id`,`cash`) VALUES ('$order_id','$user_id','$total_price')";
            mysqli_query($conn, $sql);
        }

        $query_list = "('$order_id','$product_ids[0]','$amounts[0]','$price[0]','wait')";
        for ($i = 1; $i < COUNT($product_ids); $i++) {
            $query_list .= ",('$order_id','$product_ids[$i]','$amounts[$i]','$price[$i]','wait')";
        }
        $sql = "INSERT INTO `order_product_list`(`order_id`, `product_id`, `amount`, `price`, `status`) VALUES $query_list";
        mysqli_query($conn, $sql);

        $sql = "DELETE FROM `cart` WHERE id in ('$cart_ids')";
        mysqli_query($conn, $sql);

        $purchase_success = true;
    }
}
