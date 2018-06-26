<?php
  require_once('connection.php');

  $id = intval($_GET['ID']);
  $sqlCommandSelect = "SELECT * FROM `products` WHERE prod_id=$id";
  $query = mysqli_query($connection, $sqlCommandSelect);
  $matchData = mysqli_fetch_assoc($query);

  $data = [$matchData['prod_id'], $matchData['prod_cat'], $matchData['prod_subcat'], $matchData['prod_brand'], $matchData['prod_name'], $matchData['prod_unit'], $matchData['prod_price'],$matchData['prod_img1']];

  echo json_encode($data);
  mysqli_close($connection);
?>
