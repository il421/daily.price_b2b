<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $targetDir = '../img/';
    $fileTempName = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $filePath = $targetDir . basename($fileName);

    move_uploaded_file($fileTempName, $filePath);

    $cat = $_POST['cat'];
    $subcat = $_POST['subcat'];
    $brand = $_POST['brand'];
    $name = $_POST['name'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];


    $storeNumber = 1; // temporary

    $insertSql = "INSERT INTO products (prod_store_id, prod_cat, prod_subcat, prod_brand, prod_name, prod_unit, prod_price, prod_img1)
    VALUES ('$storeNumber', '$cat','$subcat','$brand','$name','$unit', '$price', 'img/$fileName')";

    $query = mysqli_query($connection, $insertSql);

    if ($query) {
      header("Location: ../staff.categories.php");
    } else {
      echo "Something has come up, please try again!";
    }
      mysqli_close($connection);
  }
?>
