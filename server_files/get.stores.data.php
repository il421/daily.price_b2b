<?php
  require_once('connection.php');

  $id = intval($_GET['ID']);
  $sqlCommandSelect = "SELECT * FROM `stores` WHERE store_id=$id";
  $query = mysqli_query($connection, $sqlCommandSelect);
  $matchData = mysqli_fetch_assoc($query);

  $data = [$matchData['store_id'], $matchData['store_name'], $matchData['store_address'], $matchData['store_suburb'], $matchData['store_city'], $matchData['store_zip'], $matchData['store_phone'], $matchData['store_lat'], $matchData['store_long']];

  echo json_encode($data);
  mysqli_close($connection);
?>
