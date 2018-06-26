<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $prodId = $_POST['id'];
    $prodCat = $_POST['cat'];
    $prodSubcat = $_POST['subcat'];
    $prodBrand = $_POST['brand'];
    $prodName = $_POST['name'];
    $prodUnit = $_POST['unit'];
    $prodPrice = $_POST['price'];
    $fileName = $_FILES['photo']['name'];
    $fileNameImg = 'img/' . $_FILES['photo']['name'];

    if ($fileName == '') {
      $updateSql = "UPDATE `products` SET prod_cat='$prodCat', prod_subcat='$prodSubcat', prod_brand='$prodBrand', prod_name='$prodName', prod_unit='$prodUnit', prod_price='$prodPrice' WHERE prod_id=$prodId";
    } else {
      $targetDir = '../img/';
      $fileTempName = $_FILES['photo']['tmp_name'];
      $filePath = $targetDir . basename($fileName);
      move_uploaded_file($fileTempName, $filePath);

      $updateSql = "UPDATE `products` SET prod_cat='$prodCat', prod_subcat='$prodSubcat', prod_brand='$prodBrand', prod_name='$prodName', prod_unit='$prodUnit', prod_price='$prodPrice', prod_img1='$fileNameImg' WHERE prod_id=$prodId";
    }

  	$query = mysqli_query($connection, $updateSql);
  	if ($query) {
  		header('Location: ../staff.categories.php');
  	} else {
  		echo "Failed to update data.";
  	}
      mysqli_close($connection);
  }
?>
