<?php
require '../condb.php';
if (!isset($_SESSION['id']) && $_SESSION['type'] != 4) {
    header("Refresh:0; login.php");
    return;
}
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
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
                                            <th>ชื่อ - นามสกุล</th>
                                            <th>เบอร์โทร</th>
                                            <th>ค้างชำระ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($riders_overdue as $rider) {
                                        ?>
                                            <tr>
                                                <td><?= $rider->id ?></td>
                                                <td><?= $rider->user->first_name . ' ' . $rider->user->last_name  ?></td>
                                                <td><?= $rider->user->tel ?></td>
                                                <td><?= number_format($rider->overdue, 2, '.', '') ?> บาท</td>
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

<script>
    function RiderDetailModal(id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("modal-content").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "components/rider-detail.php?id=" + id, true);
        xhttp.send();
    }


    function SetSearch(val) {
        $('#dataTable_filter input').val(val);
    }
    setTimeout(
        () => {
            SetSearch(<?= json_encode($search) ?>)
        }, 100);
</script>