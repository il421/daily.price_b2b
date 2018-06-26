<?php
  session_start();
  // You need to change the user_role to get a particular session. It can be admin or staff.
  $_SESSION['user_role'] = 'admin';
  $_SESSION['user_first'] = 'Ilya';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Index</title>
</head>
<body>
  <main>
    <div class="links container row">
      <a class="btn" href="admin.users.php">ADMIN</a>
      <a class="btn" href="staff.categories.php">STAFF</a>
    </div>
  </main>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
