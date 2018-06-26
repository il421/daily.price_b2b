<?php
  require_once('connection.php');

  if (isset($_POST) & !empty($_POST)) {

    $storeId = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $suburb = $_POST['suburb'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $phone = $_POST['phone'];
    $lat = $_POST['lat'];
    $long = $_POST['long'];

    $updateSql = "UPDATE `stores` SET store_name='$name', store_address='$address', store_suburb='$suburb', store_city='$city', store_zip='$zip', store_phone='$phone', store_lat='$lat', store_long='$long' WHERE store_id=$storeId";

  	$query = mysqli_query($connection, $updateSql);
  	if ($query) {
  		header('Location: ../admin.stores.php');
  	} else {
  		echo "Failed to update data.";
  	}
      mysqli_close($connection);
  }
?>
