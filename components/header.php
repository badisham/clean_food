<!-- Header -->

<header class="header trans_300">

    <div class="top_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">

                    <!--ใส่ได้-->


                </div>
                <div class="col-md-6 text-right">
                    <div class="top_nav_right">
                        <ul class="top_nav_menu">
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