<?php
  session_start();

  if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: server_files/logout.php');
  };
  require_once('server_files/connection.php');

  if (!isset($_GET['start']) or !is_numeric($_GET['start'])) {
    $start = 0;
  } else {
    $start = (int)$_GET['start'];
  };

  $n = 3; // number of rows for paginator

  $readFromSql = "SELECT subscribers.subscribe_id, users.user_first, users.user_last, users.user_email, users.user_phone, offers.product_name, offers.brand, offers.unit, offers.store_name FROM subscribers JOIN users ON subscribers.user_id = users.user_id JOIN offers ON subscribers.sub_offer_id=offers.offer_id LIMIT $start, $n";
  $query = mysqli_query($connection, $readFromSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Admin panel</title>
</head>
<body>
  <header>
    <nav>
      <div class="container">
        <div class="header row align-items-center">
          <div class="col-1 header__logo"><a href="index.php">Daily<br>Prices</a></div>
          <div class="col-11 header__menu">
            <ul>
              <li><a href="admin.users.php">Users</a></li>
              <li><a href="admin.stores.php">Stores</a></li>
              <li class="header__menu--active"><a href="">Subscription</a></li>
              <li><a href="server_files/logout.php">Log out</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <div class="operate-table container mt-5">
      <div class="operate-table__name">
        <?php echo 'Hi, ' . $_SESSION['user_first']; ?>
      </div>
      <div class="table--modificator">
        <table class="table">
          <thead class="table">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone</th>
              <th scope="col">Product</th>
              <th scope="col">Store</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php
              while ($matchData = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
              <td><?php echo $matchData['subscribe_id']; ?></td>
              <td><?php echo $matchData['user_first'] . " " . $matchData['user_last']; ?></td>
              <td><?php echo $matchData['user_email']; ?></td>
              <td><?php echo $matchData['user_phone']; ?></td>
              <td><?php echo $matchData['product_name'] . " " . $matchData['brand'] . " ," . $matchData['unit']; ?></td>
              <td><?php echo $matchData['store_name']; ?></td>
              <td class="operate-table__btns">
                <a class="btn-delete btn btn-dark btn--modificator btn--delete" href="server_files/delete.subscriber.php?ID=<?php echo $matchData['subscribe_id']; ?>" role="button"></a>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
      </div>
      <nav>
        <ul class="pagination">
        <?php
          $a = mysqli_query($connection, "SELECT COUNT(1) FROM `subscribers`");
          $b = mysqli_fetch_array( $a ); // number of rows in the users table

          for ($i = 0; $i < ($b[0] / $n); $i++) {
            echo '<li class="page-item page-item">
                    <a href="' . $_SERVER['PHP_SELF'] . '?start=' . $i * $n . '" class="page-link page-link--modificator">' . ($i + 1) . '</a>
                  </li>';
          };
        ?>
        </ul>
      </nav>
    </div>
  </main>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/utilites.js"></script>
  <script src="js/load.subscribers.js"></script>
  <script src="js/paginator.js"></script>
</body>
</html>
