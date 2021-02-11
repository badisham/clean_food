<?php
require '../condb.php';
if (!isset($_SESSION['id']) && $_SESSION['type'] != 4) {
    header("Refresh:0; login.php");
    return;
}
$products = [];
$sql = "SELECT 
product.id as product_id, user.first_name, user.last_name,product.price,product.name,product.img,product.genre,product.day, restaurant.name as restaurant_name
FROM product
INNER JOIN restaurant ON restaurant.id = product.restaurant_id
INNER JOIN user ON restaurant.user_id = user.id";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['name'];
        $product->img = $row['img'];
        $product->genre = $row['genre'];
        $product->price = $row['price'];
        $product->day = $row['day'];
        $product->restaurant_name = $row['restaurant_name'];
        $product->owner_name = $row['first_name'] . ' ' . $row['last_name'];

        array_push($products, $product);
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
                                            <th></th>
                                            <th>ชื่อ</th>
                                            <th>ประเภท</th>
                                            <th>ราคา</th>
                                            <th>วันที่ส่ง</th>
                                            <th>ร้านค้า</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($products as $product) {
                                        ?>
                                            <tr>
                                                <td><?= $product->id ?></td>
                                                <td><img src="../images/product/<?= $product->img ?>" style="width: 150px;" alt=""></td>
                                                <td><?= $product->name ?></td>
                                                <td><?= $product->genre ?></td>
                                                <td><?= $product->price ?></td>
                                                <td><?= $product->day ?></td>
                                                <td><?= $product->restaurant_name  ?></td>
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

            <?php
            require './components/footer.php';
            ?>

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