<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="../js/sweet-alert.js"></script>
<?php
require '../condb.php';

if (isset($_POST['name_restaurant']) && isset($_POST['genre']) && isset($_POST['address_id'])) {
    $restaurant_id = $_SESSION['restaurant_id'];

    $name = $_POST['name_restaurant'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];

    $address_id = $_POST['address_id'];
    $address = $_POST['address'];
    $amphure = $_POST['amphure'];
    $district = $_POST['district'];
    $zip_code = $_POST['zip_code'];

    $sql = "UPDATE `restaurant` SET `name`='$name',`genre`='$genre',`description`='$description' WHERE id ='$restaurant_id'";
    $up_res = mysqli_query($conn, $sql);
    $sql = "UPDATE `address` SET `address`='$address',`amphure`='$amphure',`district`='$district',`zip_code`='$zip_code' WHERE id = '$address_id'";
    $up_address = mysqli_query($conn, $sql);
    if ($up_address && $up_res) {
        SweetAlert('แก้ไขสำเร็จ', 'success', '../restaurant-profile.php');
    } else {
        SweetAlert('ผิดพลาด', 'error', '../restaurant-profile.php');
    }
    header("Refresh:2; ../restaurant-profile.php");
}
