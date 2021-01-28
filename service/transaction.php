<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="../js/sweet-alert.js"></script>
<?php
require '../condb.php';

if (isset($_POST['overdue']) && isset($_POST['select_payment'])) { // rider โอนให้บริษัท
    $user_id = $_SESSION['id'];
    $rider_id = $_SESSION['rider_id'];
    $cash = $_POST['overdue'];
    $payment = explode("-", $_POST['select_payment']);
    $num = $payment[0];
    $type = $payment[1];

    if (Transfer($conn, $user_id, $num, $admin_id, $admin_num_card, $cash, $type, '../profile.php')) {
        $sql = "UPDATE `order_product_list` SET `status`='sent_success' WHERE rider_id = '$rider_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            SweetAlert('ชำระเรียบร้อย', 'success', '../profile.php');
        } else {
            SweetAlert('ผิดพลาด', 'error', '../profile.php');
        }
    }
    header("Refresh:2; ../profile.php");
}
