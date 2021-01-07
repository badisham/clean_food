<?php

function UpdateAccount($conn)
{
    $user_id = $_SESSION['id'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $tel = $_POST['tel'];

    $sql = "UPDATE `user` SET `first_name`='$first_name',`last_name`='$last_name',`email`='$email',`tel`='$tel' WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function ChangePassword($conn)
{
    $user_id = $_SESSION['id'];
    $password = $_POST['password'];
    $password_new = $_POST['password_new'];

    $sql = "SELECT id FROM `user` WHERE password = '$password' AND id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        return "notfound";
    }

    $sql = "UPDATE `user` SET `password`='$password_new' WHERE password = '$password' AND id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        return "true";
    } else {
        return "false";
    }
}

function AddAddress($conn)
{
    $user_id = $_SESSION['id'];
    $address = $_POST['address'];
    $district_id = $_POST['district_id'];
    $zip_code = $_POST['zip_code'];

    $sql = "SELECT districts.name_th as district_name, amphures.name_th as amphure_name FROM districts INNER JOIN amphures ON amphures.id = districts.amphure_id 
    WHERE districts.id = '$district_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $amphure_name = $row['amphure_name'];
    $district_name = $row['district_name'];

    $sql = "INSERT INTO `address`(`address`, `amphure`, `district`, `zip_code`, `user_id`) VALUES ('$address','$amphure_name','$district_name','$zip_code','$user_id')";
    $result = mysqli_query($conn, $sql);
}

function DeleteAddress($conn, $address_id)
{
    $sql = "DELETE FROM `address` WHERE id = '$address_id'";
    mysqli_query($conn, $sql);
}
