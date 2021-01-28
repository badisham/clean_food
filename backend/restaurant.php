<?php
require '../condb.php';
if (!isset($_SESSION['id']) && $_SESSION['type'] != 4) {
    header("Refresh:0; login.php");
    return;
}
$restaurants = [];

$percent = 20;
$disburse = 1 - ($percent / 100);
$sql = "SELECT *,restaurant.id as restaurant_id,user.id as user_id ,
(SELECT ((SUM(order_product_list.price)* $disburse) - SUM(order_product_list.shipping)) FROM order_product_list WHERE order_product_list.status ='sent_success' AND order_product_list.product_id in (
            SELECT product.id FROM product WHERE product.restaurant_id = restaurant.id
        )) as disburse_price
FROM restaurant 
INNER JOIN user ON user.id = restaurant.user_id 
INNER JOIN address ON address.id = restaurant.address_id
ORDER BY restaurant.id DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $restaurant = new Restaurant();
        $restaurant->id = $row['restaurant_id'];
        $restaurant->name = $row['name'];
        $restaurant->genre = $row['genre'];
        $restaurant->created_at = $row['created_at'];
        $restaurant->disburse_price = $row['disburse_price'];

        $user = new User();
        $user->id = $row['id'];
        $user->username = $row['username'];
        $user->first_name = $row['first_name'];
        $user->last_name = $row['last_name'];
        $user->email = $row['email'];
        $user->tel = $row['tel'];
        $user->type = $row['type'];
        $restaurant->owner = $user;

        $address = new Address();
        $address->address = $row['address'];
        $address->amphure = $row['amphure'];
        $address->district = $row['district'];
        $address->zip_code = $row['zip_code'];
        $restaurant->address = $address;

        array_push($restaurants, $restaurant);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>User</title>
    <?php
    require 'components/head.html';
    ?>
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<style>
    tbody {
        text-align: center;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
        require 'components/nav.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                require 'components/header.php';
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">ผู้ใช้งานทั้งหมด</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ชื่อร้าน</th>
                                            <th>เจ้าของ ชื่อ - นามสกุล</th>
                                            <th>ประเภท</th>
                                            <th>เบอร์โทร</th>
                                            <th>ที่อยู่</th>
                                            <th>จ่ายค่าอาหาร</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($restaurants as $res) {
                                        ?>
                                            <tr>
                                                <td><?= $res->id ?></td>
                                                <td><?= $res->name ?></td>
                                                <td><?= $res->owner->first_name . ' ' . $res->owner->last_name  ?></td>
                                                <td><?= $res->genre ?></td>
                                                <td><?= $res->owner->tel ?></td>
                                                <td>
                                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= $res->address->address ?>+<?= $res->address->district ?>+<?= $res->address->amphure ?>" class="btn btn-primary" target="_blank">ที่อยู่</a>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($res->disburse_price > 0) {
                                                    ?>
                                                        <a href="services/disburse.php?user_id=<?$res->user->id?>&restaurant_id=<?= $res->id ?>&disburse_price=<?= $res->disburse_price ?>" class="btn btn-primary">จ่าย <?= $res->disburse_price ?> บาท</a>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>