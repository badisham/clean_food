<nav>
    <?php
    if (!isset($_SESSION['id'])) {
    ?>
        <ul class="account_selection">
            <li><a href="login.php"><i class="fa fa-sign-in" aria-hidden="true"></i>เข้าสู่ระบบ</a></li>
            <li><a href="register.php"><i class="fa fa-user-plus" aria-hidden="true"></i>สมัครสมาชิก</a></li>
        </ul>

    <?php
    } else {
    ?>
        <div class="container p-0 justify-content-end">

            <div class="btn-group">
                <a href="profile.php">
                    <div class="form-control mt-2" style="color: #33cc00">
                        โปรไฟล
                    </div>
                </a>
            </div>
            <a class="dropdown-item mt-2" href="logout.php" style="color: #0066ff">
                <img src="images/155.png" size="50 pixel" class="img-fluid icon-2 mr-2 mt-0-5">
                ออกจากระบบ
            </a>

        </div><!-- container -->
    <?php
    }
    ?>

</nav>