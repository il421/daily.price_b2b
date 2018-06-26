<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $prodId = $_POST['id'];
    $offerPrice = $_POST['offer-price'];
    $offerQty = $_POST['offer-qty'];
    $offerActive = $_POST['active'];
    $BestBeforeDate = $_POST['date'];

    $updateSql = "UPDATE product_child SET prod_child_price='$offerPrice', prod_child_special='$offerActive' WHERE prod_id='$prodId' AND prod_child_bbd='$BestBeforeDate' AND prod_child_special='0' LIMIT $offerQty";

  	$query = mysqli_query($connection, $updateSql);
  	if ($query) {
  		header('Location: ../staff.stock.php');
  	} else {
  		echo "Failed to make an offer.";
  	}
      mysqli_close($connection);
  }
?>
