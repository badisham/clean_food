<style>
    .alert {
        margin-bottom: 0;
    }

    .alert-fixed {
        position: fixed;
        bottom: 0px;
        left: 0px;
        width: 100%;
        z-index: 9999;
        border-radius: 0px;
    }
</style>
<?php
if (isset($_SESSION['id'])) {
    if ($_SESSION['type'] == 2 || $_SESSION['type'] == 3) {
        $sql = "SELECT COUNT(bank_user.id) as count FROM bank_user 
                            INNER JOIN bank_card ON bank_user.num = bank_card.num
                            WHERE bank_card.type = 'DEBIT' AND bank_user.user_id = '" . $_SESSION['id'] . "'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_fetch_assoc($result)['count'];
        if ($count <= 0) {
?>
            <div class="alert alert-warning alert-dismissible fade show alert-fixed" role="alert">
                คุณไม่มีบัญชีรับเงิน. <a href="profile.php#bank_card"><strong>เพิ่มช่องทาง</strong></a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
<?php
        }
    }
}
?>
<!-- Header -->

<header class="header trans_300">

    <div class="top_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">

                    <!-- ด้านหน้า -->

                </div>
                <div class="col-md-6 text-right">
                    <div class="top_nav_right">
                        <ul class="top_nav_menu">
                            <!-- <?php
                                    if (isset($_SESSION['id'])) {
                                    ?>
                                <li class="language">
                                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bell" aria-hidden="true"></i>
                                        
                                        <span class="badge badge-danger badge-counter">1</span>
                                    </a>
                                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                        <h6 class="dropdown-header">
                                            แจ้งเตือน
                                        </h6>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-success">
                                                    <i class=" fas fa-donate text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">asdasdasd</div>
                                                asdsdsdsd
                                            </div>
                                        </a>
                                    </div>



                                </li>
                            <?php
                                    }
                            ?> -->
                            <li class="account">
                                <a href="#" style="width: 150px;">

                                    <?php
                                    if (!isset($_SESSION['id'])) {
                                        echo 'บัญชี';
                                    } else {
                                        echo $_SESSION['username'];
                                    }
                                    ?>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="account_selection">
                                    <?php
                                    require_once 'components/nav.php';
                                    ?>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->

    <?php
    require_once 'components/subheader.php';
    ?>

</header>
<div class="fs_menu_overlay"></div>