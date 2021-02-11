<?php

if (isset($_GET['amphure_id']) && !isset($amphure_id)) {
    require '../condb.php';
    $amphure_id = $_GET['amphure_id'];
    $json = array();
    $sql = "SELECT * FROM `districts` WHERE amphure_id = '$amphure_id'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($json, $row);
    }

    echo json_encode($json);
    return;
}

$amphures = [];
$amps_name = [];
$sql = "SELECT * FROM `amphures`";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $amphure = new Amphures();
        $amphure->id = $row['id'];
        $amphure->code = $row['code'];
        $amphure->name_th = $row['name_th'];
        $amphure->name_en = $row['name_en'];
        $amphures[$amphure->id] = $amphure;
        $amps_name[$amphure->id] = $row['name_th'];
    }
}


if (isset($district_edit) && isset($amphure_edit)) {
    $amphure_id = array_search($amphure_edit, $amps_name);
    $districts = [];

    $sql = "SELECT * FROM `districts` WHERE amphure_id = '$amphure_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $district = new District();
            $district->id = $row['id'];
            $district->name_th = $row['name_th'];
            $district->name_en = $row['name_en'];
            $district->amphure_id = $row['amphure_id'];
            $districts[$row['id']] = $district;
        }
    }

?>

    <div class="form-group col-xs-4 col-md-4">
        <label for="amphure"></label>
        <select name="amphure" id="amphure" class="form-control" required>
            <option value="">เลือกอำเภอ</option>
            <?php
            foreach ($amphures as $amphure) {
                echo $amphure_edit == $amphure->name_th ? $amphure->name_th : '';
            ?>
                <option <?= $amphure_edit == $amphure->name_th ? 'selected' : '' ?>><?= $amphure->name_th ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class=" form-group col-xs-4 col-md-4">
        <label for="district"></label>
        <select name="district" id="district" class="form-control" required>
            <option value="">เลือกตำบล</option>
            <?php
            foreach ($districts as $district) {
            ?>
                <option <?= $district_edit == $district->name_th ? 'selected' : '' ?>><?= $district->name_th ?></option>
            <?php
            }
            ?>
        </select>
    </div>
<?php
} else {


?>

    <div class="form-group col-xs-4 col-md-4">
        <label for="amphure"></label>
        <select name="amphure_id" id="amphure" class="form-control">
            <option value="">เลือกอำเภอ</option>
            <?php
            foreach ($amphures as $amphure) {
            ?>
                <option value="<?= $amphure->id ?>"><?= $amphure->name_th ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class=" form-group col-xs-4 col-md-4">
        <label for="district"></label>
        <select name="district_id" id="district" class="form-control">
            <option value="">เลือกตำบล</option>
        </select>
    </div>
<?php
}
?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
</script>
<script script>
    let districts = {};
    $(function() {
        var amphureObject = $('#amphure');
        var districtObject = $('#district');

        // on change amphure
        amphureObject.on('change', function() {
            var amphureId = $(this).val();
            districtObject.html('<option value="">เลือกตำบล</option>');

            $.get('./components/select-address.php?amphure_id=' + amphureId, function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    districts[item.id] = item;

                    districtObject.append(
                        $('<option> < /option>').val(item.id).html(item.name_th));
                });
            });
        });
        districtObject.on('change', function() {
            var districtId = $(this).val();
            $('#zip_code').val(districts[districtId].zip_code);
        });
    });
</script>