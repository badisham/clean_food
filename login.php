<?php
require 'condb.php';

if (isset($_SESSION['id'])) {
    header("Refresh:0; index.php");
}

$is_login = false;
$login_succes = false;
if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT *,user.id as id ,restaurant.id as restaurant_id, rider.id as rider_id  FROM user 
    LEFT JOIN restaurant ON user.id = restaurant.user_id
    LEFT JOIN rider ON user.id = rider.user_id
    WHERE user.username = '$username' AND user.password = '$password'";

    $result = mysqli_query($conn, $sql);
    $is_login = true;
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $_SESSION['type'] = $row['type'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                if (isset($row['rider_id'])) {
                    $_SESSION['rider_id'] = $row['rider_id'];
                } elseif (isset($row['restaurant_id'])) {
                    $_SESSION['restaurant_id'] = $row['restaurant_id'];
                }
                $login_succes = true;
            }
        }
    }
}
https: //www.google.co.th/maps/place/
?>
<script>
    setTimeout(() => {
        let isLogin = <?= json_encode($is_login); ?>;
        let login = <?= json_encode($login_succes); ?>;
        if (isLogin) {
            if (login) {
                SweetAlertOk('เข้าสู่ระบบเรียบร้อย', 'success', 'index.php');
            } else {
                SweetAlert('ชื่อผู้ใช้หรือรหัสผ่านผิดพลาด', 'warning');
            }
        }

    }, 100);
</script>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>
<style>
    #login {
        max-width: 400px;
        margin: 200px auto 50px;
    }
</style>

<body>
    <div class="super_container">
        <?php
        require_once 'components/header.php';
        ?>
        <div class="container">
            <div id="login" class="form-wrap">

                <form method="POST" action="login.php">
                    <div class="row mt-4 shadow-lg pt-4 pb-4">

                        <div class="col-12 text-center">
                            <h4><strong>เข้าสู่ระบบ</strong></h4>
                        </div>

                        <div class="col-12">
                            <div id="loginwithemail">
                                <div class="form-group mt-4">
                                    <label for="inputFullName">ชื่อผู้ใช้งาน</label>
                                    <input required type="text" class="form-control bg-light" name="username" value="" placeholder="ชื่อผู้ใช้งาน">
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">รหัสผ่าน</label>
                                    <input required type="password" class="form-control bg-light" name="password" placeholder="••••••••">
                                </div>
                                <div class="text-center mt-4 mb-4">
                                    <button type="submit" name="reg_user" class="btn btn-danger rounded pl-4 pr-4" style="background-color: #1fd100 !important; border-color: #76b6c2">
                                        เข้าสู่ระบบ
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <div class="row text-center mt-4 mb-65">
                    <div class="col-12">
                        มีบัญชีแล้วหรือยัง ? <a href="register.php" class="text-danger" style="color: #00c200 !important;">สมัครสมาชิก</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>