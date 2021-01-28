<?php
require '../condb.php';
if (!isset($_SESSION['id']) && $_SESSION['type'] != 4) {
    header("Refresh:0; login.php");
    return;
}
$users = [];
$sql = "SELECT * FROM user WHERE type != '4' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $user = new User();
    $user->id = $row['id'];
    $user->username = $row['username'];
    $user->first_name = $row['first_name'];
    $user->last_name = $row['last_name'];
    $user->email = $row['email'];
    $user->tel = $row['tel'];
    $user->type = $row['type'];
    array_push($users, $user);
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
                                            <th>Username</th>
                                            <th>ชื่อ - นามสกุล</th>
                                            <th>Email</th>
                                            <th>เบอร์โทร</th>
                                            <th>ประเภท</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($users as $user) {
                                        ?>
                                            <tr>
                                                <td><?= $user->id ?></td>
                                                <td><?= $user->username ?></td>
                                                <td><?= $user->first_name . ' ' . $user->last_name  ?></td>
                                                <td><?= $user->email ?></td>
                                                <td><?= $user->tel ?></td>
                                                <td>
                                                    <?php
                                                    if ($user->type == 1) {
                                                        echo 'ลูกค้าทั่วไป';
                                                    } else if ($user->type == 2) {
                                                        echo 'ร้านค้า';
                                                    } else if ($user->type == 3) {
                                                        echo 'พนักงานขนส่ง';
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