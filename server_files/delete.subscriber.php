<?php
  require_once('connection.php');
  $subscribeId = $_GET['ID'];

  $sqlCommandDelete = "DELETE FROM `subscribers` WHERE subscribe_id=$subscribeId";
  $query = mysqli_query($connection, $sqlCommandDelete);
  header('location: ../admin.subscription.php');
  mysqli_close($connection);
?>
