<?php
require 'condb.php';

if (!isset($_SESSION['restaurant_id']) || !isset($_SESSION['id'])) {
  header("Refresh:0; login.php");
  return;
}
$is_upsert = false;
$upsert_success = false;
$restaurant_id = 0;
$select_day = isset($_GET['select_day']) ? $_GET['select_day'] : "";
$select_genre = isset($_GET['select_genre']) ? $_GET['select_genre'] : "";


$restaurant_id = $_SESSION['restaurant_id'];
require 'service/product.php';

if (isset($_POST['product_name'])) {
  $is_upsert = true;
  $upsert_success = CreateProduct($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/head.php';
?>

<body>

  <?php
  require './components/header.php';

  ?>
  <style>
    .product_table img {
      width: 100px;
    }

    .product_table tbody td,
    .product_table thead th {
      text-align: center;
      vertical-align: middle !important;
    }
  </style>
  <div class="container layout-1">
    <div class="row mt-4">
      <div class="col-md-8" style="max-height: 620px;overflow-y: scroll;">
        <form class="form-inline" method="get" action="restaurant-profile.php">
          <div class="form-group mt-4">
            <select class="form-control" id="select_genre" name="select_genre" onchange="OnSelect()">
              <option value="">-- ทุกประเภท --</option>
              <option value="food" <?= $select_genre == "food" ? "selected" : "" ?>>อาหาร</option>
              <option value="sweet" <?= $select_genre == "sweet" ? "selected" : "" ?>>ของหวาน</option>
            </select>
          </div>
          <div class="form-group mt-4 ml-2">
            <select class="form-control" id="select_day" name="select_day" onchange="OnSelect()">
              <option value="">-- ทุกวัน --</option>
              <option value="sunday" <?= $select_day == "sunday" ? "selected" : "" ?>>วันอาทิตย์</option>
              <option value="monday" <?= $select_day == "monday" ? "selected" : "" ?>>วันจันทร์</option>
              <option value="tuesday" <?= $select_day == "tuesday" ? "selected" : "" ?>>วันอังคาร</option>
              <option value="wednesday" <?= $select_day == "wednesday" ? "selected" : "" ?>>วันพุธ</option>
              <option value="thursday" <?= $select_day == "thursday" ? "selected" : "" ?>>วันพฤหัสบดี</option>
              <option value="friday" <?= $select_day == "friday" ? "selected" : "" ?>>วันศุกร์</option>
              <option value="saturday" <?= $select_day == "saturday" ? "selected" : "" ?>>วันเสาร์</option>
            </select>
          </div>
          <input type="submit" id="select_filter" style="display: none;">
        </form>

        <table class="table table-striped product_table mt-2">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col"></th>
              <th scope="col">ชื่อ</th>
              <th scope="col">ประเภท</th>
              <th scope="col">ราคา</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $products = [];
            $query_type = $select_genre != "" ? "AND genre = '$select_genre'" : "";
            $query_day = $select_day != "" ? "AND day LIKE '%$select_day%'" : "";
            $sql = "SELECT * FROM `product` WHERE restaurant_id = '$restaurant_id' $query_type $query_day AND is_enable = '1' ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);

            if ($result) {
              if (mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_assoc($result)) {

                  $product = new Product;
                  $product->id = $row['id'];
                  $product->name = $row['name'];
                  $product->img = $row['img'];
                  $product->genre = $row['genre'];
                  $product->price = $row['price'];
                  $product->description = $row['description'];
                  $product->day = explode(",", $row['day']);
                  $products[$row['id']] = $product;
            ?>
                  <tr>
                    <th scope="row"><?= $row['id']; ?></th>
                    <td><img src="images/product/<?= $product->img ?>" alt=""></td>
                    <td><?= $product->name ?></td>
                    <td>
                      <?= $product->genre == "food" ? "อาหาร" : "" ?>
                      <?= $product->genre == "sweet" ? "ของหวาน" : "" ?>
                    </td>
                    <td><?= $product->price ?></td>
                    <td class="text-right">
                      <button class="btn btn-warning" onclick="SetUpdateProduct(<?= $product->id ?>)">แก้ไข</button>
                      <button class="btn btn-danger" onclick="DeleteProduct(<?= $product->id ?>)">X</button>
                    </td>
                  </tr>
            <?php
                }
              }
            }
            ?>

          </tbody>
        </table>
      </div>
      <div class="col-md-4">
        <h4 id="title_product">เพิ่มรายการสินค้า</h4>
        <form action="restaurant-profile.php" method="post" enctype="multipart/form-data">
          <input type="hidden" id="product_id" name="product_id" value="">
          <div class="form-group mt-4">
            <label for="product_name">ชื่อสินค้า</label>
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="ชื่อสินค้า" required>
          </div>
          <div class="form-group mt-4">
            <label for="genre">ประเภท</label>
            <select class="form-control" id="genre" name="genre" required>
              <option value="">เลือก</option>
              <option value="food">อาหาร</option>
              <option value="sweet">ของหวาน</option>
            </select>
          </div>

          <div style="border: 2px solid #ccc;border-radius: 5px;padding: 10px;">
            <h5>เลือกวันทำออเดอร์</h5>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="sunday" type="checkbox" id="sunday" checked>
              <label class="form-check-label" for="sunday">วันอาทิตย์</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="monday" type="checkbox" id="monday" checked>
              <label class="form-check-label" for="monday">วันจันทร์</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="tuesday" type="checkbox" id="tuesday" checked>
              <label class="form-check-label" for="tuesday">วันอังคาร</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="wednesday" type="checkbox" id="wednesday" checked>
              <label class="form-check-label" for="wednesday">วันพุธ</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="thursday" type="checkbox" id="thursday" checked>
              <label class="form-check-label" for="thursday">วันพฤหัสบดี</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="friday" type="checkbox" id="friday" checked>
              <label class="form-check-label" for="friday">วันศุกร์</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" name="day[]" value="saturday" type="checkbox" id="saturday" checked>
              <label class="form-check-label" for="saturday">วันเสาร์</label>
            </div>

          </div>

          <div class="form-group mt-4">
            <label for="description">รายละเอียด</label>
            <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="รายละเอียด" required></textarea>
          </div>
          <div class="form-group mt-4">
            <label for="fileToUpload">รูปสินค้า</label>
            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control bg-light" accept="image/*">
          </div>
          <div class="form-group mt-4">
            <label for="price">ราคา</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="ราคา" value="1" min="1" required>
          </div>
          <button id="btn_submit" type="submit" name="method" value="post" class="btn btn-primary">ยืนยัน</button>
          <button id="btn_cancel" style="display: none;" type="button" onclick="CancelUpdateProduct()" class="btn btn-secondary">ยกเลิก</button>
        </form>
      </div>
    </div>

    <!-- <div class="row mt-4">
      <div class="col-12">
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
          แก้ไขข้อมูลร้าน
        </button>
        <div class="collapse mt-4" id="collapseExample">
          <form>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputEmail4">ชื่อร้าน</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
              </div>
              <div class="form-group col-md-6">
                <label for="inputPassword4">Password</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail4">รายละเอียด</label>
              <textarea name="description" class="form-control" id="" cols="10" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">ยืนยัน</button>
          </form>
        </div>
      </div>
    </div> -->
  </div>


  <script>
    setTimeout(() => {
      if (<?= json_encode($is_upsert) ?>) {
        if (<?= json_encode($upsert_success) ?>) {
          SweetAlert('เรียบร้อย', 'success');
        } else {
          SweetAlert('ผิดพลาด', 'warning');
        }
      }
    }, 100);

    let products = <?= json_encode($products) ?>

    function SetUpdateProduct(id) {

      $('#title_product').html("แก้ไขสินค้า");
      $("#product_id").val(products[id].id);
      $("#product_name").val(products[id].name);
      $("#genre").val(products[id].genre);
      $("#description").val(products[id].description);
      $("#price").val(products[id].price);
      $("#btn_submit").val('put');
      $('#btn_cancel').show();

      $('#sunday').prop("checked", products[id].day.includes('sunday'));
      $('#monday').prop("checked", products[id].day.includes('monday'));
      $('#tuesday').prop("checked", products[id].day.includes('tuesday'));
      $('#wednesday').prop("checked", products[id].day.includes('wednesday'));
      $('#thursday').prop("checked", products[id].day.includes('thursday'));
      $('#friday').prop("checked", products[id].day.includes('friday'));
      $('#saturday').prop("checked", products[id].day.includes('saturday'));
    }

    function CancelUpdateProduct() {
      $('#title_product').html("เพิ่มสินค้า");
      $("#product_name").val(null);
      $("#genre").val("");
      $("#description").val(null);
      $("#price").val(1);
      $("#btn_submit").val('post');
      $('#btn_cancel').hide();

      $('#sunday').prop("checked", true);
      $('#monday').prop("checked", true);
      $('#tuesday').prop("checked", true);
      $('#wednesday').prop("checked", true);
      $('#thursday').prop("checked", true);
      $('#friday').prop("checked", true);
      $('#saturday').prop("checked", true);
    }

    function OnSelect() {
      $('#select_filter').click();
    }

    function DeleteProduct(id) {
      SweetAlertConfirm('ยืนยันการลบ', 'warning', 'restaurant-profile.php?method=delete&id=' + id);
    }
  </script>