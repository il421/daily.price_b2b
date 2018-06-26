<?php
  require_once('connection.php');
  $user_id = $_GET['ID'];

  $sqlCommandDelete = "DELETE FROM `users` WHERE user_id=$user_id";
  $query = mysqli_query($connection, $sqlCommandDelete);
  header('location: ../admin.users.php');
  mysqli_close($connection);
?>
