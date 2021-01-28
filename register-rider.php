<?php
require 'condb.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
$is_regis = false;
$regis_msg = "";
if (isset($_POST['username'])) {
    require 'service/register.php';
    $is_regis = true;
    $regis_msg = Register($conn);
}

?>
<style>
    #restaurant-register>*,
    h1,
    h4,
    h2,
    p {
        color: #fff !important;
    }
</style>
<script>
    function CheckUsername(username) {
        $.ajax({
            type: "GET",
            url: `./service/register.php?check_user=0&username=` + username,
            success: function(response) {
                if (response === 'false') {
                    $('#username_true').hide();
                    $('#username_false').show();
                } else if (response === 'true') {
                    $('#username_true').show();
                    $('#username_false').hide();
                }
            }
        });
    }
</script>

<body>
    <div class="super_container">
        <?php
        require_once 'components/header.php';
        ?>
        <div class="background_image_fixed background_filter" style="background-image: url(https://www.appmanthailand.com/assets/img/rider_regis.jpg);"></div>
        <div class="container">
            <div id="restaurant-register" class="form-wrap" style="margin-top: 200px;">

                <form action="register-rider.php" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="row mt-4 shadow-lg pt-4 pb-4">

                        <div class="col-md-6 content_register">
                            <div class="col-4" style="position: fixed;">
                                <h2>มาร่วมเป็นส่วนนึงในครอบครัวพนักงานขนส่งของเรา</h2>
                                <p>
                                    สมัครง่ายๆ สมัครออนไลน์ได้ ไม่ต้องเข้ามาสมัครด้วยตัวเอง
                                </p>
                                <p>
                                    รับอุปกรณ์ เตรียมตัวรอวิ่งงานวันถัดไปได้เลย</p>
                                <p>
                                    รายได้ดี! เลือกเวลาทำงานได้</p>
                                <p>
                                    โบนัส3ต่อ วิ่งครบรอบ ตามเงื่อนไข มีโบนัสพิเศษ!!</p>
                            </div>
                        </div>
                        <div class="col-md-6 backgrund_b_content">
                            <div id="loginwithemail">
                                <div class="form-group mt-4">
                                    <label for="inputFullName">ชื่อผู้ใช้งาน</label>
                                    <input required type="text" onblur="CheckUsername(this.value)" class="form-control bg-light" name="username" placeholder="ชื่อผู้ใช้งาน" minlength="8">
                                    <p style="color: red !important;display: none;" id="username_false">มีชื่อผู้ใช้นี้แล้วในระบบ</p>
                                    <p style="color: green !important;display: none;" id="username_true">สามารถใช้ชื่อผู้ใช้นี้ได้</p>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">รหัสผ่าน</label>
                                    <input required type="password" class="form-control bg-light" name="password" placeholder="รหัสผ่าน" minlength="8">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="first_name">ชื่อ</label>
                                    <input required type="text" class="form-control bg-light" id="first_name" name="first_name" placeholder="ชื่อ">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="last_name">นามสกุล</label>
                                    <input required type="text" class="form-control bg-light" id="last_name" name="last_name" placeholder="นามสกุล">
                                </div>

                                <div class="form-group mt-4">
                                    <label for="inputFullName">เบอร์โทรศัพท์</label>
                                    <input required type="text" pattern="[0-9]+" class="form-control bg-light" maxlength="20" name="tel" placeholder="เบอร์โทรศัพท์">
                                </div>

                                <div class="form-group mt-4">
                                    <label for="inputEmail">อีเมล์</label>
                                    <input required type="email" class="form-control bg-light" name="email" placeholder="email@example.com">
                                </div>
                                <hr />
                                <div class="form-group mt-4">
                                    <label for="rider_card_id">เลขที่ใบขับขี่</label>
                                    <input required type="text" id="rider_card_id" pattern="[0-9]+" maxlength="20" class="form-control bg-light" name="rider_card_id" placeholder="เลขที่ใบขับขี่">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="card_num_id">เลขบัตรประชาชน</label>
                                    <input required type="text" id="card_num_id" pattern="[0-9]{13}" maxlength="13" class="form-control bg-light" name="card_num_id" placeholder="เลขบัตรประชาชน">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="fileToUpload">รูปถ่ายบัตรประชาชน</label>
                                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control bg-light" accept="image/*" required>
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