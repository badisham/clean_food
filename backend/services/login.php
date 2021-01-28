<?php

if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM user WHERE type = '4' AND username = '$username' AND password = '$password'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['type'] = $row['type'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Refresh:0; index.php");
            return;
        }
    }
}
