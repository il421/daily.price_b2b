<?php
  require_once('connection.php');

  $sqlCommandSelect = "SELECT * FROM `users`";
  $query = mysqli_query($connection, $sqlCommandSelect);

  while ($matchData = mysqli_fetch_assoc($query)) {
  $data[] = [
    $matchData['user_id'],
    $matchData['user_email'],
    $matchData['user_first'],
    $matchData['user_last'],
    $matchData['user_phone'],
    $matchData['user_role'],
    $matchData['user_active']
  ];
}
  echo json_encode($data);
  mysqli_close($connection);
?>
