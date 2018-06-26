<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $prodId = $_POST['id'];
    $offerDate = $_POST['date'];
    $newPrice = $_POST['new-price'];
    $oldPrise = $_POST['old-price'];
    $UpdatedPrise = $_POST['updated-price'];
    $offerQty = $_POST['qty'];

    $updateSql = "UPDATE `product_child` SET prod_child_price='$UpdatedPrise' WHERE prod_id=$prodId AND prod_child_bbd='$offerDate' AND prod_child_price='$newPrice'";

  	$query = mysqli_query($connection, $updateSql);
  	if ($query) {
  		header('Location: ../staff.offers.php');
  	} else {
  		echo "Failed to update data.";
  	}
      mysqli_close($connection);
  }
?>
