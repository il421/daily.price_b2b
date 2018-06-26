<?php
  require_once('connection.php');
  $prodId = $_GET['ID'];
  $offerDate = $_GET['date'];
  $newPrice = $_GET['new-price'];

  $updateSql = "UPDATE `product_child` SET prod_child_special=0 WHERE prod_id=$prodId AND prod_child_bbd='$offerDate' AND prod_child_price='$newPrice'";

  $query = mysqli_query($connection, $updateSql);

  header('location: ../staff.offers.php');
  mysqli_close($connection);
?>
