<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $prodId = $_POST['id'];
    $prodQty = $_POST['qty'];
    $prodBBDate = $_POST['date'];

    for ($i=0; $i < $prodQty ; $i++) {
      $insertSql = "INSERT INTO product_child (prod_id, prod_child_bbd, prod_child_special)
      VALUES ('$prodId', '$prodBBDate','0')";
      $query = mysqli_query($connection, $insertSql);
    }

    if ($query) {
      header("Location: ../staff.stock.php");
    } else {
      echo "Something has come up, please try again!";
    }
      mysqli_close($connection);
  }
?>
