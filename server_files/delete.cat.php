<?php
  require_once('connection.php');
  $prod_id = $_GET['ID'];

  $sqlCommandDelete = "DELETE FROM `products` WHERE prod_id=$prod_id";
  $query = mysqli_query($connection, $sqlCommandDelete);
  header('location: ../staff.categories.php');
  mysqli_close($connection);
?>
