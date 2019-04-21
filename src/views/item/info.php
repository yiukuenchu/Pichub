<?php
  // Page that returns information about the item in JSON format. Used on the Admin panel to load item info into the edit modal
  header('Content-Type: application/json');
  if(!isset($_ENV['SERVER_ROOT'])) {
    require('../partials/env.php');
  }
  require('../partials/database.php');
  // Returns in for for a specific item
  if(isset($_GET) && isset($_GET['id'])) {

    $itemQuery = "SELECT * FROM FramedProducts WHERE productID = {$_GET['id']}";
    $itemInfo = mysqli_query($connection, $itemQuery);

    if($itemInfo && mysqli_num_rows($itemInfo) == 1) {
      echo '{ "successful": true, "itemInfo": '.json_encode(mysqli_fetch_assoc($itemInfo), true).' }';
      mysqli_free_result($itemInfo);
    } else {
      echo '{ "successful": false, "message": "Invalid product id." }';
    }
  // Returns info for all products if none are specifically requested
  } else {
    $allItems = "SELECT productID, name, photographer, description FROM FramedProducts;";
    $results = mysqli_query($connection, $allItems);

    if($results) {
      while($row = mysqli_fetch_assoc($results)) {
        $items[] = $row;
      }
      $jsonItems = json_encode($items, true);
      echo '{ "successful": true, "items": '.$jsonItems.'}';
      mysqli_free_result($results);
    } else {
      echo '{ "successful": false, "message": "Error fetching items" }';
    }

  }
  mysqli_close($connection);
?>
