<?php
  require_once('connection.php');
  $store_id = $_GET['ID'];

  $sqlCommandDelete = "DELETE FROM `stores` WHERE store_id=$store_id";
  $query = mysqli_query($connection, $sqlCommandDelete);
  header('location: ../admin.stores.php');
  mysqli_close($connection);
?>
