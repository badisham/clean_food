<?php
require 'service/upload-image.php';

if (isset($_GET['id']) && isset($_GET['method']) && $_GET['method'] == "delete") {
    $id = $_GET['id'];

    $sql = "UPDATE `product` SET is_enable='0' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $is_success = true;
    }
}

function CreateProduct($conn)
{
    $is_success = false;
    $product_name = $_POST['product_name'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $day = implode(",", $_POST['day']);
    $img = "";
    if ($_FILES['fileToUpload']['name'] != "") {
        $img = UploadImage('product');
    }

    if ($_POST['method'] == "post") {

        $restaurant_id = $_SESSION['restaurant_id'];

        $sql = "INSERT INTO `product`(`name`, `genre`, `description`, `img`, `price`, `restaurant_id`, `day`) VALUES ('$product_name','$genre','$description','$img','$price','$restaurant_id','$day')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $is_success = true;
        }
    } elseif ($_POST['method'] == "put") {
        $product_id = $_POST['product_id'];
        $updateImage = $img != "" ? ",`img`='$img'" : "";

        $sql = "UPDATE `product` SET created_at = created_at, `name`='$product_name',`genre`='$genre',`description`='$description'" . $updateImage . ",`price`='$price' ,`day`='$day' WHERE id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $is_success = true;
        }
    }
    return $is_success;
}
