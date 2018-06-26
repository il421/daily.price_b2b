<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $suburb = $_POST['suburb'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $phone = $_POST['phone'];
    $lat = $_POST['lat'];
    $long = $_POST['long'];

    $insertSql = "INSERT INTO stores (store_name, store_address, store_suburb, store_city, store_zip, store_phone, store_lat, store_long)
    VALUES ('$name','$address','$suburb','$city','$zip','$phone','$lat','$long')";

    $query = mysqli_query($connection, $insertSql);

    if ($query) {
      header("Location: ../admin.stores.php");
    } else {
      echo "Something has come up, please try again!";
    }
      mysqli_close($connection);
  }
?>
