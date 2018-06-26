<?php
  session_start();

  if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'staff') {
    header('Location: server_files/logout.php');
  };

  require_once('server_files/connection.php');

  if (!isset($_GET['start']) or !is_numeric($_GET['start'])) {
    $start = 0;
  } else {
    $start = (int)$_GET['start'];
  };

  $n = 3; // number of rows for paginator

  $readFromSql = "SELECT * FROM offers WHERE best_before_date > CURDATE() ORDER BY best_before_date, offer_discount DESC LIMIT $start, $n";
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
  <div class="wrapper">
    <header>
      <nav>
        <div class="container">
          <div class="header row align-items-center">
            <div class="col-1 header__logo"><a href="index.php">Daily<br>Prices</a></div>
            <div class="col-11 header__menu">
              <ul>
                <li><a href="staff.categories.php">Categories</a></li>
                <li><a href="staff.stock.php">In stock</a></li>
                <li class="header__menu--active"><a href="">Offers</a></li>
                <li><a href="server_files/logout.php">Log out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <main>
      <div class="operate-table mt-5 container">
        <div class="operate-table__name">
          <?php echo 'Hi, ' . $_SESSION['user_first']; ?>
        </div>
        <div class="table--modificator">
          <table class="table">
            <thead class="table">
              <tr>
                <th scope="col" class="hidden">ID</th>
                <th scope="col">Category</th>
                <th scope="col">Brand</th>
                <th scope="col">Name</th>
                <th scope="col">BBD</th>
                <th scope="col" class="hidden">Old Price</th>
                <th scope="col" class="hidden">New Price</th>
                <th scope="col">Discount</th>
                <th scope="col">Offer qty</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php
                while ($matchData = mysqli_fetch_assoc($query)) {
              ?>
              <tr>
                <td class="hidden"><?php echo $matchData['product_id']; ?></td>
                <td><?php echo $matchData['category']?></td>
                <td><?php echo $matchData['brand']; ?></td>
                <td><?php echo $matchData['product_name'].", ".$matchData['unit']; ?></td>
                <td class="operate-table__date"><?php echo $matchData['best_before_date']; ?></td>
                <td class="hidden"><?php echo $matchData['old_price']; ?></td>
                <td class="hidden"><?php echo $matchData['new_price']; ?></td>
                <td><?php echo ($matchData['offer_discount'] * 100) . "%"; ?></td>
                <td><?php echo $matchData['offer_quantity']; ?></td>
                <td class="operate-table__btns">
                  <a class="btn-edit btn btn-dark btn--modificator btn--edit" data-toggle="modal" data-target="#exampleModal" role="button"></a>
                  <a class="btn-delete btn btn-dark btn--modificator btn--delete" href="server_files/delete.offer.php?ID=<?php echo $matchData['product_id']; ?>&date=<?php echo $matchData['best_before_date']; ?>&new-price=<?php echo $matchData['new_price']; ?>" role="button"></a>
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
            $a = mysqli_query($connection, "SELECT COUNT(1) FROM `offers`");
            $b = mysqli_fetch_array( $a ); // number of rows in the users table

            for ($i = 0; $i < ($b[0] / $n); $i++) {
              echo '<li class="page-item">
                      <a href="' . $_SERVER['PHP_SELF'] . '?start=' . $i * $n . '" class="page-link page-link--modificator">' . ($i + 1) . '</a>
                    </li>';
            };
          ?>
          </ul>
        </nav>
      </div>
      <div class="edit-form-offers modal fade" data-backdrop="static" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="edit-form-offers__title">Edit the current offer</h2>
            </div>
            <form class="edit-form-offers__form m-3" action="server_files/edit.offer.php" method="post">
              <div class="form-row">
                <div class="form-group col-md-2 hidden">
                  <input type="text" class="form-control edit-form-offers__id" name="id" readonly>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control edit-form-offers__new hidden" name="new-price" readonly>
                </div>
                <div class="form-group col-md-12">
                  <label for="name">Name</label>
                  <input type="text" id="name" class="form-control edit-form-offers__name" name="name" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="date">Best before date</label>
                  <input type="text" id="date"class="form-control edit-form-offers__date" name="date" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label for="price">Price ($)</label>
                  <input type="number" id="price" class="form-control edit-form-offers__old" name="old-price" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label for="updated-price">Offer price ($)</label>
                  <input type="number" id="updated-price" class="form-control edit-form-offers__updated" name="updated-price" step="0.01" required>
                </div>
                <div class="form-group col-md-2">
                  <label for="qty">Quantity</label>
                  <input type="number" id="qty" class="form-control edit-form-offers__qty" name="qty" readonly>
                </div>
              </div>
              <div class="form-group">
                <p class="add-form-offers__massege">Your changes will take effect in 5 min!</p>
              </div>
              <button type="button" class="btn-close btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
              <button type="submit" class="btn-submit btn--save btn btn-dark btn--modificator"></button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/utilites.js"></script>
  <script src="js/paginator.js"></script>
  <script src="js/edit.offers.js"></script>
</body>
</html>
