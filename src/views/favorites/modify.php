<?php
  // This page is the backend for the favorite buttons, it is called using AJAX and returns JSON to the browser
  session_start();
  header('Content-Type: application/json');
  $data = json_decode(file_get_contents('php://input'), true);

  // Makes sure the user is logged in
  if(!isset($_SESSION) || !isset($_SESSION['userID'])) {
    response(false, "Not logged in");
    die();
  }
  if(!isset($_ENV['SERVER_ROOT'])) {
    require('../partials/env.php');
  }
  require('../partials/database.php');

  // Function to send the JSON response back to the browser
  function response($success, $reason) {
    $response['success'] = ($success) ? true : false;
    if(!$success) {
      $response['reason'] = $reason;
    }
    echo json_encode($response);
  }

  // Inserts a new favorite into the database.
  function addFavorite($id) {
    global $connection;
    $query = "INSERT INTO FramedFavorites VALUES ({$_SESSION['userID']}, {$id})";
    $result = mysqli_query($connection, $query);
    response($result, "Cannot add favorite");
  }

  // Deletes a favorite from the database
  function deleteFavorite($id) {
    global $connection;
    $query = "DELETE FROM FramedFavorites WHERE userID = {$_SESSION['userID']} AND productID = {$id}";
    $result = mysqli_query($connection, $query);
    response($result, "Cannot delete favorite");
  }

  // Gets all favorites for a user and returns them as JSON
  function getFavorites() {
    global $connection;
    $query = "SELECT productID FROM FramedFavorites WHERE userID = {$_SESSION['userID']}";
    $result = mysqli_query($connection, $query);
    if($result) {
      $favorites['success'] = true;
      while($row = mysqli_fetch_row($result)) {
        $favorites['favorites'][] = $row[0];
      }
      if(!isset($favorites['favorites'][0])) {
        $favorites['favorites'] = array();
      }
      $json = json_encode($favorites);
      echo $json;
      mysqli_free_result($result);
    } else {
      response(false, "Error fetching favorites");
    }
  }

  // Calls the appropriate function for each action.
  switch($data['action']) {
    case 'Add':
      addFavorite($data['itemID']);
      break;
    case 'Delete':
      deleteFavorite($data['itemID']);
      break;
    default:
      getFavorites();
      break;
  }

  mysqli_close($connection);
?>
