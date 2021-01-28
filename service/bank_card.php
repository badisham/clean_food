<?php

function AddBankCard($conn)
{
    $user_id = $_SESSION['id'];
    $num = $_POST['num'];
    $cvv = $_POST['cvv'];
    $expire_month = $_POST['expire_month'];
    $expire_year = $_POST['expire_year'];

    $check = mysqli_query($conn, "SELECT `id`, `num`, `user_id` FROM `bank_user` WHERE num = '$num' AND user_id = '$user_id'");
    if (mysqli_num_rows($check) > 0) {
        return "duplicate";
    } else {
        $sql = "SELECT num FROM `bank_card` WHERE num = '$num' AND cvv = '$cvv' AND expire_month = '$expire_month' AND expire_year ='$expire_year'";
        $result = mysqli_query($conn, $sql);


        if (mysqli_num_rows($result) > 0) {
            $bank = new BankCard();
            while ($row = mysqli_fetch_assoc($result)) {
                $bank->num = $row['num'];
            }

            $sql = "INSERT INTO `bank_user`(`num`, `user_id`) VALUES ('$bank->num','$user_id')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                return "true";
            } else {
                return "false";
            }
        } else {
            return "notfound";
        }
    }
}

function DeleteBankCard($conn, $num)
{
    $user_id = $_SESSION['id'];

    $sql = "DELETE FROM `bank_user` WHERE num = '$num' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
}

function PayCard($conn, $num_card, $total_price)
{
    $sql = "UPDATE `bank_card` SET `cash`= cash - $total_price WHERE num = '$num_card'";
    $result = mysqli_query($conn, $sql);
    return $result ? true : false;
}
