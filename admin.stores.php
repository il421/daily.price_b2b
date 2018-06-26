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

  $readFromSql = "SELECT * FROM stores ORDER BY store_name LIMIT $start, $n";
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
              <li class="header__menu--active"><a href="">Stores</a></li>
              <li><a href="admin.subscription.php">Subscription</a></li>
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
              <th scope="col">Address</th>
              <th scope="col">Phone</th>
              <th scope="col">Lat</th>
              <th scope="col">Long</th>
              <th scope="col">
                <button class="btn-add btn btn-dark btn--modificator btn--add" data-toggle="modal" data-target="#exampleModal"></button>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
              while ($matchData = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
              <td><?php echo $matchData['store_id']; ?></td>
              <td><?php echo $matchData['store_name']; ?></td>
              <td><?php echo $matchData['store_address'].", ".$matchData['store_suburb'].", ".$matchData['store_city'].", ".$matchData['store_zip']; ?></td>
              <td><?php echo $matchData['store_phone']; ?></td>
              <td><?php echo $matchData['store_lat']; ?></td>
              <td><?php echo $matchData['store_long']; ?></td>
              <td class="operate-table__btns">
                <a class="btn-edit btn btn-dark btn--modificator btn--edit" data-toggle="modal" data-target="#exampleModal" role="button"></a>
                <a class="btn-delete btn btn-dark btn--modificator btn--delete" href="server_files/delete.store.php?ID=<?php echo $matchData['store_id']; ?>" role="button"></a>
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
          $a = mysqli_query($connection, "SELECT COUNT(1) FROM `stores`");
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
    <div class="edit-form-stores modal fade" data-backdrop="static" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="edit-form-stores__title"></h2>
          </div>
          <form class="edit-form-stores__form m-3" action="" method="post">
            <div class="form-row">
              <div class="form-group col md-6 hidden">
                <label for="id">ID</label>
                <input type="text" id="id" class="form-control edit-form-stores__id" name="id" readonly>
              </div>
              <div class="form-group col md-6">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control edit-form-stores__name" name="name" required>
              </div>
              <div class="form-group col md-6">
                <label for="add">Address</label>
                <input type="text" id="add" class="form-control edit-form-stores__address" name="address" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="sub">Suburb</label>
                <input type="text" id="sub" class="form-control edit-form-stores__suburb" name="suburb" required>
              </div>
              <div class="form-group col-md-4">
                <label for="city">City</label>
                <input type="text" id="city" class="form-control edit-form-stores__city" name="city" required>
              </div>
              <div class="form-group col-md-4">
                <label for="zip">Zip code</label>
                <input type="number" id="zip" class="form-control edit-form-stores__zip" name="zip" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="phone">Phone number</label>
                <input type="number" id="phone" class="form-control edit-form-stores__phone" name="phone" required>
              </div>
              <div class="form-group col-md-4">
                <label for="name">Lat</label>
                <input type="text" id="lat" class="form-control edit-form-stores__lat" name="lat" required>
              </div>
              <div class="form-group col-md-4">
                <label for="long">Long</label>
                <input type="text" id="long" class="form-control edit-form-stores__long" name="long" required>
              </div>
            </div>
            <div class="form-group">
              <div class="stores-map">
                <div id="map" class="google__map"></div>
              </div>
            </div>
            <button type="button" class="btn-close btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
            <button type="submit" class="btn-submit btn btn-dark btn--modificator"></button>
          </form>
        </div>
      </div>
    </div>
  </main>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/utilites.js"></script>
  <script src="js/paginator.js"></script>
  <script src="js/map.js"></script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLcjOjAORs7XzQWbtigPxMZadap59ikkE&callback=initMap"></script>
  <script src="js/load.stores.js"></script>
</body>
</html>
