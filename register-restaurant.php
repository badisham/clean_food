<?php
require 'condb.php';
require 'service/register.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
$is_regis = false;
$regis_success = false;
if(isset($_POST['username'])){
    $is_regis = true;
    $regis_success = Register($conn);
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

<body>
    <div class="super_container">
        <?php
        require_once 'components/header.php';
        ?>
        <div class="background_image_fixed background_filter" style="background-image: url(https://static.estopolis.com/article/5bac767b15f02021d98a48aa_5bacb00b15f020493a0ace98.jpg);"></div>
        <div class="container">
            <div id="restaurant-register" class="form-wrap" style="margin-top: 200px;">

                <form action="register-restaurant.php" method="post" enctype="multipart/form-data">
                    <div class="row mt-4 shadow-lg pt-4 pb-4">

                        <div class="col-md-6 content_register">
                            <div class="col-4" style="position: fixed;">
                                <h2>เข้าร่วมเป็นพาร์ทเนอร์ร้านอาหารกับเรา</h2>
                                <p>
                                    เราทุกคนต่างต้องการสิ่งที่ดีที่สุดในชีวิตซึ่งก็คืออาหารอร่อยๆ เราออกสำรวจไปทั่วทั้งเมืองเพื่อนำส่งอาหารสดใหม่ให้ลูกค้าของเรา
                                </p>
                                <p>
                                    ฟู้ดแพนด้าเป็นธุรกิจนานาชาติที่เติบโตเร็วและพยายามดึงดูดความสนใจด้วยบริการที่ปรับให้เข้ากับความต้องการของชุมชน</p>
                            </div>
                        </div>
                        <div class="col-md-6 backgrund_b_content">
                            <div id="loginwithemail">
                                <div class="form-group mt-4">
                                    <label for="inputFullName">ชื่อผู้ใช้งาน</label>
                                    <input required type="text" class="form-control bg-light" name="username" placeholder="ชื่อผู้ใช้งาน">
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">รหัสผ่าน</label>
                                    <input required type="password" class="form-control bg-light" name="password" placeholder="••••••••">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="first_name">ชื่อ</label>
                                    <input required type="text" class="form-control bg-light" id="first_name" name="first_name" value="" placeholder="ชื่อ">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="last_name">นามสกุล</label>
                                    <input required type="text" class="form-control bg-light" id="last_name" name="last_name" value="" placeholder="นามสกุล">
                                </div>

                                <div class="form-group mt-4">
                                    <label for="tel">เบอร์โทรศัพท์</label>
                                    <input required type="number" class="form-control bg-light" id="tel" name="tel" placeholder="เบอร์โทรศัพท์">
                                </div>

                                <div class="form-group mt-4">
                                    <label for="email">อีเมล์</label>
                                    <input required type="email" class="form-control bg-light" id="email" name="email" placeholder="email@example.com">
                                </div>
                                <hr />
                                <div class="form-group mt-4">
                                    <label for="name_restaurant">ชื่อร้าน</label>
                                    <input required type="text" id="name_restaurant" class="form-control bg-light" name="name_restaurant" placeholder="ชื่อร้าน">
                                </div>
                                <div class="form-group mt-4">
                                    <label for="description">รายละเอียดร้าน</label>
                                    <textarea required type="text" id="description" class="form-control bg-light" name="description" placeholder="รายละเอียดร้าน" ></textarea>
                                    </div>
                                <div class="form-group mt-4">
                                    <label for="genre">ประเภทร้าน</label>
                                    <select class="form-control" id="genre" name="genre">
                                        <option>เลือก</option>
                                        <option value="food">อาหาร</option>
                                        <option value="sweet">ของหวาน</option>
                                    </select>
                                </div>
                                <div class="form-group mt-4">
                                    <label for="fileToUpload">รูปหน้าร้าน</label>
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
        if(<?=json_encode($is_regis)?>){
            if(<?=json_encode($regis_success)?>){
                SweetAlertConfirm('สมัครสมาชิกเรียบร้อย', 'success', 'index.php');
            }else{
                SweetAlert('ผิดพลาด', 'warning');
            }
        }
    }, 100);
</script>