<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $idUser = $_POST['id'];
    $firstUser = $_POST['firstName'];
    $lastUser = $_POST['lastName'];
    $passwordUser = $_POST['password'];
    $roleUser = $_POST['role'];

    $phoneUser = $_POST['phone'];
    $emailUser = $_POST['email'];
    $activationUser = $_POST['active'];

    if ($passwordUser == '') {
      $updateSql = "UPDATE `users` SET user_first='$firstUser', user_last='$lastUser', user_phone='$phoneUser', user_email='$emailUser', user_role='$roleUser', user_active='$activationUser' WHERE user_id=$idUser";
    } else {
      $passwordUserHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $updateSql = "UPDATE `users` SET user_first='$firstUser', user_last='$lastUser', user_pwd='$passwordUserHashed', user_phone='$phoneUser', user_email='$emailUser', user_role='$roleUser', user_active='$activationUser' WHERE user_id=$idUser";
    }

  	$query = mysqli_query($connection, $updateSql);
  	if ($query) {
  		header('Location: ../admin.users.php');
  	} else {
  		echo "Failed to update data.";
  	}
      mysqli_close($connection);
  }
?>
