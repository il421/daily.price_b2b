<?php
  session_start();

  if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'staff') {
    header('Location: server_files/logout.php');
  };

  require_once('server_files/connection.php');

  $readFromSql = "SELECT COUNT(*) AS total, product_child.prod_child_bbd, product_child.prod_id, SUM(product_child.prod_child_special) AS offers, products.prod_cat , products.prod_subcat , products.prod_brand , products.prod_name , products.prod_unit, products.prod_qty, products.prod_price FROM product_child LEFT JOIN products ON products.prod_id = product_child.prod_id GROUP BY prod_child_bbd, prod_id HAVING total > 0";

  $query = mysqli_query($connection, $readFromSql);
  $storeNumber = 1; // CURENTLY
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
                <li class="header__menu--active"><a href="">In stock</a></li>
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
        <table class="table table--modificator">
          <thead class="table">
            <tr>
              <th class="hidden" scope="col">Id</th>
              <th scope="col">Category</th>
              <th scope="col">Name</th>
              <th scope="col">Price</th>
              <th scope="col">BBD</th>
              <th scope="col">Qty</th>
              <th scope="col">Offers</th>
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
              <td><?php echo $matchData['prod_cat'] . ', ' . $matchData['prod_subcat']; ?></td>
              <td><?php echo $matchData['prod_brand'] . ' ' . $matchData['prod_name'] . ', ' . $matchData['prod_unit']; ?></td>
              <td><?php echo '$' . $matchData['prod_price'] ; ?></td>
              <td class="operate-table__date"><?php echo $matchData['prod_child_bbd']; ?></td>
              <td><?php echo $matchData['total']; ?></td>
              <td><?php echo $matchData['offers']; ?></td>
              <td class="operate-table__btns">
                <a class="btn-sell btn btn-dark btn--modificator btn--sell" data-toggle="modal" data-target="#exampleModal-3" role="button"></a>
                <a class="btn-offer btn btn-dark btn--modificator btn--offer" data-toggle="modal" data-target="#exampleModal-2" role="button"></a>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
      </div>
  <!-- Modal dialog of an adding products -->
      <div class="add-form-products modal fade" data-backdrop="static" id="exampleModal-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="add-form-products__title"></h2>
            </div>
            <form class="add-form-products__form m-3" action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <select class="form-control add-form-products__select select" name="product">
                  <option class="select__def" data-id="" data-price="">Choose a poduct</option>
                <?php
                  $readFromSqlProducts = "SELECT * FROM products WHERE prod_store_id='$storeNumber' ORDER BY prod_cat";
                  $queryProducts = mysqli_query($connection, $readFromSqlProducts);
                  while ($matchData = mysqli_fetch_assoc($queryProducts)) {
                ?>
                  <option data-id="<?php echo $matchData['prod_id']; ?>" data-price="<?php echo $matchData['prod_price']; ?>"><?php echo $matchData['prod_subcat'] . ' ' . $matchData['prod_brand'] . ' ' . $matchData['prod_name'] . ', ' . $matchData['prod_unit']; ?></option>
                <?php
                  }
                ?>
                </select>
              </div>
              <div class="form-group">
                <input type="text" class="form-control add-form-products__id hidden" name="id">
              </div>
              <div class="form-row">
                <div class="form-group col md-4">
                  <input type="text" class="form-control add-form-products__price" name="price" placeholder="Price" readonly>
                </div>
                <div class="form-group col md-4">
                  <input type="number" class="form-control add-form-products__qty" min="1" name="qty" placeholder="Quantity" required>
                </div>
                <div class="form-group col md-4">
                  <input type="date" class="form-control add-form-products__date" name="date" required>
                </div>
              </div>
              <button type="button" class="btn-close--products btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
              <button type="submit" class="btn-submit--products btn btn-dark btn--modificator"></button>
            </form>
          </div>
        </div>
      </div>
  <!-- Modal dialog of an offer form -->
      <div class="add-form-offers modal fade" data-backdrop="static" id="exampleModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="add-form-offers__title">Make an offer</h2>
            </div>
            <form class="add-form-offers__form m-3" action="server_files/add.new.offer.php" method="post" enctype="multipart/form-data">
              <div class="add-form-offers__product">
                <p></p>
              </div>
              <input type="text" class="form-control add-form-offers__id hidden" name="id">
              <div class="form-row">
                <div class="form-group col md-6">
                  <label for="bbd">Best before date</label>
                  <input type="text" id="bbd" class="form-control add-form-offers__date" name="date" readonly>
                </div>
                <div class="form-group col md-6">
                  <label for="offer-price">Offer price ($)</label>
                  <input type="number" id="offer-price" class="form-control add-form-offers__price" name="offer-price" placeholder="0" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col md-6">
                  <label for="total-qty">Total quantity</label>
                  <input type="number" id="total-qty" class="form-control add-form-offers__total-qty" name="total-qty" readonly>
                </div>
                <div class="form-group col md-6">
                  <label for="offer-qty">Offer quantity</label>
                  <input type="number" id="offer-qty" class="form-control add-form-offers__offer-qty" min="1" name="offer-qty" placeholder="0" required>
                </div>
              </div>
              <div class="form-group">
                <p class="add-form-offers__massege">Your offer will be placed in 5 min!</p>
              </div>
              <div class="form-check__wrapper">
                <div class="form-check system-checkbox system-checkbox--modal">
                  <input type="hidden" name="active" value="0">
                  <input type="checkbox" class="form-check-input" id="offer-active" name="active" value="1" required>
                  <label class="form-check-label" for="offer-active"></label>
                </div>
                <span>I agree to place the offer in Daily Price System</span>
              </div>
              <button type="button" class="btn-close--offer btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
              <button type="submit" class="btn-submit--offer btn--save btn btn-dark btn--modificator"></button>
            </form>
          </div>
        </div>
      </div>
  <!-- Modal dialog of a selling form -->
      <div class="sell-product-form modal fade" data-backdrop="static" id="exampleModal-3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="sell-product-form__title"></h2>
            </div>
            <form class="sell-product-form__form m-3" action="" method="post" enctype="multipart/form-data">
              <div class="form-group" style="display: none">
                <label for="name3">ID</label>
                <input type="text" class="form-control sell-product-form__id" name="id" readonly>
              </div>
              <div class="form-group">
                <label for="name3">Name</label>
                <input type="text" class="form-control sell-product-form__name" name="name" readonly>
              </div>
              <div class="form-row">
                <div class="form-group col md-4">
                  <label for="date3">Best before date</label>
                  <input type="text" id="date3" class="form-control sell-product-form__date" name="date" readonly>
                </div>
                <div class="form-group col md-4">
                  <label for="qty3">Quantity</label>
                  <input type="number" id="qty3" class="form-control sell-product-form__qty" name="qty" readonly>
                </div>
                <div class="form-group col md-4">
                  <label for="qtysell3">Sell quantity</label>
                  <input type="number" id="qtysell3" class="form-control sell-product-form__sell-qty" name="sell-qty" placeholder="0" required>
                </div>
              </div>
              <button type="button" class="btn-close--sell btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
              <button type="submit" class="btn-submit--sell btn btn-dark btn--modificator"></button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/utilites.js"></script>
  <script src="js/load.products.js"></script>
  <script src="js/load.offers.js"></script>
  <script src="js/edit.sales.js"></script>
</body>
</html>
