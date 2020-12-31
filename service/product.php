<?php
require 'service/upload-image.php';

if (isset($_GET['id']) && isset($_GET['method']) && $_GET['method'] == "delete") {
    $id = $_GET['id'];

    $sql = "DELETE FROM `product` WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $is_success = true;
    }
}


function CreateProduct($conn)
{
    $product_name = $_POST['product_name'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $is_enable = true;
    $img = UploadImage('product');

    if ($_POST['method'] == "post") {

        $restaurant_id = $_SESSION['restaurant_id'];

        $sql = "INSERT INTO `product`(`name`, `genre`, `description`, `img`, `price`, `is_enable`, `restaurant_id`) VALUES ('$product_name','$genre','$description','$img','$price','$is_enable','$restaurant_id')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $is_success = true;
            return $is_success;
        }
    } elseif ($_POST['method'] == "put") {
        $product_id = $_POST['product_id'];
        $updateImage = "";
        if ($img != "") {
            $updateImage = ",`img`='$img'";
        }
        $sql = "UPDATE `product` SET `name`='$product_name',`genre`='$genre',`description`='$description'" . $updateImage . ",`price`='$price' WHERE id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $is_success = true;
            return $is_success;
        }
    }
    return false;
}
