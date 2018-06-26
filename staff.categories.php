<?php
  session_start();

  if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'staff') {
    header('Location: server_files/logout.php');
  };

  $name = $_SESSION['user_first'];

  require_once('server_files/connection.php');

  if (!isset($_GET['start']) or !is_numeric($_GET['start'])) {
    $start = 0;
  } else {
    $start = (int)$_GET['start'];
  };

  $n = 3; // number of rows for paginator
  $storeNumber = 1; // must be a SESSION VAR $_SESSION['store_id']
  $readFromSql = "SELECT * FROM products WHERE prod_store_id='$storeNumber' ORDER BY prod_cat LIMIT $start, $n";
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
                <li class="header__menu--active"><a href="">Categories</a></li>
                <li><a href="staff.stock.php">In stock</a></li>
                <li><a href="staff.offers.php">Offers</a></li>
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
                <th scope="col" class="hidden">ID</th>
                <th scope="col">Category</th>
                <th scope="col">Subcat</th>
                <th scope="col">Brand</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">
                  <button class="btn-add btn btn-dark btn--modificator btn--add" data-toggle="modal" data-target="#exampleModal-1"></button>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
                while ($matchData = mysqli_fetch_assoc($query)) {
              ?>
              <tr>
                <td class="hidden"><?php echo $matchData['prod_id']; ?></td>
                <td><?php echo $matchData['prod_cat']; ?></td>
                <td><?php echo $matchData['prod_subcat']; ?></td>
                <td><?php echo $matchData['prod_brand']; ?></td>
                <td><?php echo $matchData['prod_name'] . ', ' . $matchData['prod_unit']; ?></td>
                <td><?php echo '$' . $matchData['prod_price']; ?></td>
                <td class="operate-table__btns">
                  <a class="btn-edit btn btn-dark btn--modificator btn--edit" data-toggle="modal" data-target="#exampleModal-1" role="button"></a>
                  <a class="btn-delete btn btn-dark btn--modificator btn--delete" href="server_files/delete.cat.php?ID=<?php echo $matchData['prod_id']; ?>" role="button"></a>
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
            $a = mysqli_query($connection, "SELECT COUNT(1) FROM `products` WHERE prod_store_id='$storeNumber'");
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
  <!-- Modal dialog of an product form -->
      <div class="edit-form-products modal fade" id="exampleModal-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="edit-form-products__title"></h2>
            </div>
            <form class="edit-form-products__form m-3" action="" method="post" enctype="multipart/form-data">
              <div class="form-row">
                <div class="form-group hidden">
                  <input type="text" class="form-control edit-form-products__id" name="id" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="categ">Category</label>
                  <input type="text" id="categ" class="form-control edit-form-products__cat" name="cat" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="subcateg">Subcategory</label>
                  <input type="text" id="subcateg" class="form-control edit-form-products__subcat" name="subcat" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="brand">Brand</label>
                  <input type="text" id="brand" class="form-control edit-form-products__brand" name="brand" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="name">Name</label>
                  <input type="text" id="name" class="form-control edit-form-products__name" name="name" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="unit">Unit</label>
                  <input type="text" id="unit" class="form-control edit-form-products__unit" name="unit" required>
                </div>
                <div class="form-group col-md-4">
                  <label for="price">Price ($)</label>
                  <input type="number" for="id" class="form-control edit-form-products__price" step="0.01" name="price" required>
                </div>
              </div>
              <div class="form-group">
                <label for="photo">Upload one photo</label>
                <input type="file" id="photo" class="form-control-file edit-form-products__photo" name="photo" multiple required>
              </div>
              <div class="form-group">
                <div class="edit-form-products__preview"></div>
              </div>
              <button type="button" class="btn-close btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
              <button type="submit" class="btn-submit btn btn-dark btn--modificator"></button>
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
  <script src="js/load.cat.js"></script>
</body>
</html>
