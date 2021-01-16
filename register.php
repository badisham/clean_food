<?php
require 'condb.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once './components/head.php';
$is_regis = false;
$regis_msg = "";
if (isset($_POST['username'])) {
    require 'service/register.php';
    $is_regis = true;
    $regis_msg = Register($conn);
}
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

                <form method="POST" action="register.php" autocomplete="off">
                    <div class="row mt-4 shadow-lg pt-4 pb-4">

                        <div class="col-12 text-center">
                            <h4><strong>สมัครสมาชิก</strong></h4>
                        </div>

                        <div class="col-12">
                            <div id="loginwithemail">
                                <div class="form-group mt-4">
                                    <label for="inputFullName">ชื่อผู้ใช้งาน</label>
                                    <input onblur="CheckUsername(this.value)" required type="text" class="form-control bg-light" name="username" value="" placeholder="ชื่อผู้ใช้งาน" minlength="8">
                                    <p style="color: red !important;display: none;" id="username_false">มีชื่อผู้ใช้นี้แล้วในระบบ</p>
                                    <p style="color: green !important;display: none;" id="username_true">สามารถใช้ชื่อผู้ใช้นี้ได้</p>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">รหัสผ่าน</label>
                                    <input required type="password" class="form-control bg-light" name="password" placeholder="รหัสผ่าน" minlength="8">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="inputCfPassword">ยืนยันรหัสผ่าน</label>
                                    <input required type="password" class="form-control bg-light" name="password_2" placeholder="••••••••">

                                </div> -->
                                <div class="form-group mt-4">
                                    <label for="first_name">ชื่อ</label>
                                    <input required type="text" class="form-control bg-light" id="first_name" name="first_name" value="" placeholder="ชื่อ">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="last_name">นามสกุล</label>
                                    <input required type="text" class="form-control bg-light" id="last_name" name="last_name" value="" placeholder="นามสกุล">
                                </div>

                                <div class="form-group mt-4">
                                    <label for="inputFullName">เบอร์โทรศัพท์</label>
                                    <input required pattern="[0-9]+" class="form-control bg-light" name="telephone" value="" placeholder="เบอร์โทรศัพท์">
                                </div>

                                <div class="form-group mt-4">
                                    <label for="inputEmail">อีเมล์</label>
                                    <input required type="email" class="form-control bg-light" name="email" value="" placeholder="email@example.com">
                                </div>


                                <div class="text-center mt-4 mb-4">
                                    <button type="submit" name="reg_user" class="btn btn-danger rounded pl-4 pr-4" style="background-color: #1fd100 !important; border-color: #76b6c2">
                                        สมัครสมาชิก
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <div class="row text-center mt-4 mb-65">
                    <div class="col-12">
                        มีบัญชีอยู่แล้ว ? <a href="login.php" class="text-danger" style="color: #00c200 !important;">เข้าสู่ระบบ</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
<script>
    setTimeout(() => {
        if (<?= json_encode($is_regis) ?>) {
            if (<?= json_encode($regis_msg) ?> == 'success') {
                SweetAlertOk('สมัครสมาชิกเรียบร้อย', 'success', 'index.php');
            } else if (<?= json_encode($regis_msg) ?> == 'duplicate') {
                SweetAlert('มีชื่อนี้ในระบบแล้ว', 'warning');
            } else {
                SweetAlert('ผิดพลาด', 'warning');
            }
        }

    }, 100);
</script>