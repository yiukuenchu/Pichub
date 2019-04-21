<?php
  // This page is the backend for the orders page. It returns all the info for a specific order as JSON 
  session_start();
  header('Content-Type: application/json');
  // User must be logged in and an Admin to view this page
  if(!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    echo '{ "successful": false, "message": "Access Denied!" }';
    die();
  }

  if(!isset($_ENV['SERVER_ROOT'])) {
    require('../../partials/env.php');
  }
  require('../../partials/database.php');

  // Order is specified by a GET variable
  if(isset($_GET) && isset($_GET['orderID'])) {
    if(!is_numeric($_GET['orderID'])) {
      echo '{ "successful": false, "message": "Invalid order id!" }';
      die();
    }
    $orderQuery = "SELECT * FROM FramedOrders WHERE orderID = {$_GET['orderID']};"; // Query for general order info
    $itemQuery = "SELECT FramedOrderItems.productID, name, photographer, frame, description
                  FROM FramedOrderItems JOIN FramedProducts on FramedOrderItems.productID = FramedProducts.productID
                  WHERE orderID = {$_GET['orderID']};"; // Query for order items
    $order = mysqli_query($connection, $orderQuery);
    $items = mysqli_query($connection, $itemQuery);

    if($order && $items) {
      $orderInfo = mysqli_fetch_assoc($order);
      $orderInfo['timestamp'] = date('M j, Y g:i A', strtotime($orderInfo['timestamp']));
      while($row = mysqli_fetch_assoc($items)) {
        $orderedItems[] = $row;
      }
      // Add items for the info array, or an empty arry if there are no items associated with the order somehow
      $orderInfo['items'] = (isset($orderedItems)) ? $orderedItems : array();

      $json = json_encode($orderInfo);
      echo '{ "successful": true, "orderInfo": '.$json.' }';

      mysqli_free_result($order);
      mysqli_free_result($items);
    } else {
      echo '{ "successful": false, "message": "Error fetching order info" }';
    }
  } else {
    echo '{ "successful": false, "message": "Invalid request" }';
  }
  mysqli_close($connection);
?>
