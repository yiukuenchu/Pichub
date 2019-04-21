<?php
  // Backend for the items page. Receives JSON and updates database with changes
  session_start();
  header('Content-Type: application/json');
  // User must be logged in and an Admin to view this page
  if(!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    echo '{ "status": "error", "message": "Access denied! You must be signed in as an administrator." }';
    die();
  }

  // Function that checks if input is valid
  function checkForErrors($data) {
    $errors = array();
    if(empty($data['name']) || !preg_match("/^[a-zA-Z'\s-]*$/", $data['name']) || strlen($data['name']) > 50) {
      $errors['name'] = "The item name doesn't look quite right. Please only use letters dashes, spaces, and apostrophes";
    }
    if(empty($data['photographer']) || !preg_match("/^[a-zA-Z'\s-]*$/", $data['photographer']) || strlen($data['name']) > 50) {
      $errors['photographer'] = "The photographer name doesn't look quite right. Please only use letters dashes, spaces, and apostrophes";
    }
    if(empty($data['imageURL']) || !filter_var($data['imageURL'], FILTER_VALIDATE_URL) || strlen($data['imageURL']) > 100) {
      $errors['imageURL'] = "That URL doesn't look quite. Make sure it's in the right format";
    }
    if(empty($data['category']) || !preg_match("/^[a-zA-Z-]*$/", $data['category']) || strlen($data['category']) > 50) {
      $errors['category'] = "The category doesn't look quite right. Please only use letters and dashes with no spaces";
    }
    if(empty($data['color']) || !preg_match("/^[a-zA-Z]*$/", $data['color']) || strlen($data['color']) > 50) {
      $errors['color'] = "The color doesn't look quite right. Please only use letters and no spaces";
    }
    if(empty($data['description']) || strlen($data['description']) > 500) {
      $errors['description'] = "The description doesn't look quite right.";
    }
    return $errors;
  }

  function sanitizeInput($connection, $data) {
    $clean = array();

    foreach ($data as $key => $value) {
      $clean[$key] = htmlspecialchars(mysqli_escape_string($connection, $value));
    }
    return $clean;
  }

  $data = json_decode(file_get_contents('php://input'), true); // Reads JSON

  if(isset($data) && isset($data['action'])) {
    if(!isset($_ENV['SERVER_ROOT'])) {
      require('../../partials/env.php');
    }
    require('../../partials/database.php');

    if($data['action'] === 'update' || $data['action'] === 'add') {
      $errors = checkForErrors($data);
      if(!empty($errors)) {
        echo '{ "successful": false, "errors": '.json_encode($errors).' }';
        die();
      }
    }

    $clean = sanitizeInput($connection, $data);

    // Builds the correct query for the action being performed
    switch($data['action']) {
      case 'add':
        $updateQuery = "INSERT INTO FramedProducts
                        (name, photographer, category, color, imageURL, description)
                        VALUES ('{$clean['name']}', '{$clean['photographer']}', '{$clean['category']}', '{$clean['color']}', '{$clean['imageURL']}', '{$clean['description']}');";
        break;
      case 'update':
        $updateQuery = "UPDATE FramedProducts
                        SET name = '{$clean['name']}', photographer = '{$clean['photographer']}', imageURL = '{$clean['imageURL']}', category = '{$clean['category']}', color = '{$clean['color']}', description = '{$clean['description']}'
                        WHERE ProductID = {$clean['productID']};";
        break;
      case 'delete':
        $updateQuery = "DELETE FROM FramedProducts
                        WHERE productID = {$clean['productID']};";
        break;
      default:
        echo '{ "successful": false, "message", "Invalid data" }';
        die();
    }

    $result = mysqli_query($connection, $updateQuery);
    if($result) {
      echo '{ "successful": true, "message": "Successfully updated item" }';
    } else {
      echo '{ "successful": false, "message": "Error updating item" }';
    }
    mysqli_close($connection);
  } else {
    echo '{ "successful": false, "message", "Invalid data" }';
    die();
  }
?>
