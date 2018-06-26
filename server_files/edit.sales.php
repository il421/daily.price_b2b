<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $prodId = $_POST['id'];
    $offerDate = $_POST['date'];
    $sellQty = $_POST['sell-qty'];

    for ($i=0; $i < $sellQty ; $i++) {
      $deleteSql = "DELETE FROM `product_child` WHERE prod_id=$prodId AND prod_child_bbd='$offerDate' LIMIT 1";
      $query = mysqli_query($connection, $deleteSql);
    }

  	if ($query) {
  		header('Location: ../staff.stock.php');
  	} else {
  		echo "Failed to update data.";
  	}
      mysqli_close($connection);
  }
?>
