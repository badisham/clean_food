<?php

function AddCartProduct($conn)
{
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['id'];
    $check = mysqli_query($conn, "SELECT amount FROM `cart` WHERE product_id = '$product_id' AND user_id = '$user_id'");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_row($check);
        $amount = $row[0];
        if ($amount >= 10) {
            return;
        }
        UpdateCartProduct($conn, $amount + 1, $product_id);
    } else {
        $sql = "INSERT INTO `cart`(`product_id`, `amount`, `user_id`) VALUES ('$product_id','1','$user_id')";
        mysqli_query($conn, $sql);
    }
}

function UpdateCartProduct($conn, $amount, $product_id)
{
    $user_id = $_SESSION['id'];

    $sql = "UPDATE `cart` SET `amount`='$amount' WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function DeleteCarProduct($conn, $product_id)
{
    $user_id = $_SESSION['id'];

    $sql = "DELETE FROM `cart` WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}
