<?php
require 'condb.php';

$is_upsert = false;
$upsert_success = false;
$restaurant_id = 0;
if ($_SESSION['restaurant_id'] && isset($_SESSION['id'])) {
  $restaurant_id = $_SESSION['restaurant_id'];
  require 'service/product.php';

  if (isset($_POST['product_name'])) {
    $is_upsert = true;
    $upsert_success = CreateProduct($conn);
  }
} else {
  header("Refresh:0; login.php");
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

    .product_table table tbody td {
      text-align: center;
    }
  </style>
  <div class="container layout-1">
    <div class="row mt-4">
      <div class="col-md-8" style="max-height: 620px;overflow-y: scroll;">
        <table class="table table-striped product_table">
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
            $sql = "SELECT * FROM `product` WHERE restaurant_id = '$restaurant_id' ORDER BY id DESC";
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
                  $products[$row['id']] = $product;
            ?>
                  <tr>
                    <th scope="row"><?= $row['id']; ?></th>
                    <td><img src="images/product/<?= $product->img ?>" alt=""></td>
                    <td><?= $product->name ?></td>
                    <td><?= $product->genre ?></td>
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
              <option>เลือก</option>
              <option value="food">อาหาร</option>
              <option value="sweet">ของหวาน</option>
            </select>
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

    <div class="row mt-4">
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
    </div>
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
    }

    function CancelUpdateProduct() {
      $('#title_product').html("เพิ่มสินค้า");
      $("#product_name").val(null);
      $("#genre").val();
      $("#description").val(null);
      $("#price").val(1);
      $("#btn_submit").val('post');
      $('#btn_cancel').hide();
    }

    function DeleteProduct(id) {
      SweetAlertConfirm('ยืนยันการลบ', 'warning', 'restaurant-profile.php?method=delete&id=' + id);
    }
  </script>