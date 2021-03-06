<?php

if (isset($_GET['username']) && isset($_GET['check_user'])) {
    require '../condb.php';
    $sql = "SELECT id FROM user WHERE username = '" . $_GET['username'] . "'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo 'false';
    } else {
        echo 'true';
    }
}

function Register($conn)
{
    require 'service/upload-image.php';
    $is_success = false;
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $type = 1;
        $username = $_POST['username'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];

        $sql = "SELECT id FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            return 'duplicate';
        }

        $user_id = 0;
        $sql = "INSERT INTO `user`(`username`, `password`, `first_name`, `last_name`, `email`, `tel`, `type`) VALUES ('$username','$password','$first_name','$last_name','$email','$tel','$type')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $is_success = true;
            $user_id = mysqli_insert_id($conn);
        }

        $restaurant_id = 0;
        $rider_id = 0;
        if (isset($_POST['name_restaurant']) && $user_id != 0) {
            require 'service/user.php';

            $name_restaurant = $_POST['name_restaurant'];
            $genre = $_POST['genre'];
            $description = $_POST['description'];
            $img = UploadImage('restaurant');
            AddAddress($conn);
            $address_id = mysqli_insert_id($conn);

            $sql = "INSERT INTO `restaurant`( `name`, `genre`, `description`, `img`, `user_id`,`address_id`) VALUES ('$name_restaurant','$genre','$description','$img','$user_id','$address_id')";

            $result = $conn->query($sql);
            $restaurant_id = mysqli_insert_id($conn);
            if (!$result) {
                $is_success = false;
            }
            $type = 2;
        } else if (isset($_POST['rider_card_id']) && $user_id != 0) {
            $rider_card_id = $_POST['rider_card_id'];
            $card_num_id = $_POST['card_num_id'];
            $card_id_img = UploadImage('rider');

            $sql = "INSERT INTO `rider`(`rider_card_id`, `card_num_id`, `card_id_img`, `user_id`, `working_order`) VALUES ('$rider_card_id','$card_num_id','$card_id_img','$user_id','0')";
            $result = $conn->query($sql);
            if (!$result) {
                $is_success = false;
            }
            $type = 3;
        }

        if ($is_success) {
            $sql = "UPDATE `user` SET `type`= '$type'  WHERE id = '$user_id'";
            $result = $conn->query($sql);
            if (!$result) {
                $is_success = false;
            }
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['type'] = $type;
            if ($restaurant_id != 0) {
                $_SESSION['restaurant_id'] = $restaurant_id;
            } elseif ($rider_id != 0) {
                $_SESSION['rider_id'] = $rider_id;
            }
        }
    }
    return 'success';
}
