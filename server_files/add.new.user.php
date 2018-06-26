<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $active = $_POST['active'];

    $passwordUserHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insertSql = "INSERT INTO users (user_email, user_pwd, user_first, user_last, user_phone, user_role, user_active)
    VALUES ('$email','$passwordUserHashed','$firstName','$lastName','$phone','$role','$active')";

    $query = mysqli_query($connection, $insertSql);

    if ($query) {
      header("Location: ../admin.users.php");
    } else {
      echo "Something has come up, please try again!";
    }
      mysqli_close($connection);
  }
?>
