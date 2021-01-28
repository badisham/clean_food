<?php
require '../../condb.php';
$rider_id = $_GET['id'];
$sql = "SELECT *,rider.id as rider_id FROM rider
INNER JOIN user ON rider.user_id = user.id WHERE rider.id = '$rider_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
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
?>

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">รายละเอียด</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <h3>ชื่อ - นามสกุล : <?= $rider->user->first_name . ' ' . $rider->user->last_name ?></h3>
    <img src="../images/rider/<?= $rider->card_id_img ?>" style="width: 100%;">
    <p>เลขที่ใบขับขี่ : <?= $rider->rider_card_id ?></p>
    <p>เบอร์โทรศัพท์ : <?= $rider->user->tel ?></p>
    <p>Email : <?= $rider->user->email ?></p>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
</div>