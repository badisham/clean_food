<?php
require '../condb.php';
if (!isset($_SESSION['id']) && $_SESSION['type'] != 4) {
    header("Refresh:0; login.php");
    return;
}
$riders = [];
$sql = "SELECT *,rider.id as rider_id FROM rider
INNER JOIN user ON rider.user_id = user.id ORDER BY rider.id DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $rider = new Rider();
    $rider->id = $row['rider_id'];
    $rider->rider_card_id = $row['rider_card_id'];
    $rider->card_num_id = $row['card_num_id'];
    $rider->card_id_img = $row['card_id_img'];
    $rider->working_order = $row['working_order'];

    $user = new User();
    $user->username = $row['username'];
    $user->first_name = $row['first_name'];
    $user->last_name = $row['last_name'];
    $user->email = $row['email'];
    $user->tel = $row['tel'];
    $user->type = $row['type'];
    $rider->user = $user;

    array_push($riders, $rider);
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
                                            <th>รายละเอียด</th>
                                            <th>สถานะการทำงาน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($riders as $rider) {
                                        ?>
                                            <tr>
                                                <td><?= $rider->id ?></td>
                                                <td><?= $rider->user->first_name . ' ' . $rider->user->last_name  ?></td>
                                                <td><?= $rider->user->tel ?></td>
                                                <td>
                                                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#rider-detail" onclick="RiderDetailModal(<?= $rider->id ?>)">
                                                        ข้อมูล
                                                    </a>
                                                </td>
                                                <td><?= $rider->working_order > 0 ? 'กำลังทำงาน' : 'ว่าง' ?></td>
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
    <div class="modal fade" id="rider-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="modal-content" class="modal-content">


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
</script>