<?php session_start();
  if(!isset($_SESSION['userID']) || count($_SESSION['cart']) == 0) {
    header('Location: ../store/');
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      define("PAGE_TITLE", "Checkout");
      require('../partials/head.php');
    ?>
  </head>
  <body>
    <div class="f-pusher">
    <?php
      include('../partials/navbar.php');
      if(!empty($_POST)) {
        $errors = checkForErrors();

        if(empty($errors)) {
          //save to database
          require('../partials/database.php');

          // Sanitize data to make sure there are no MySQL or XSS issues
          $sanitized = sanitizeData($connection);

          // Insert customer info into Customer table
          $insertOrder = "INSERT INTO FramedOrders (userID, shippingMethod, name, stAddress, stAddress2, city, state, zip, phone)
                          VALUES ('{$_SESSION['userID']}', '{$sanitized['method']}', '{$sanitized['name']}',
                          '{$sanitized['stAddress']}', '{$sanitized['stAddress2']}', '{$sanitized['city']}', '{$sanitized['state']}', '{$sanitized['zip']}', '{$sanitized['phNum']}');";
          $result = mysqli_query($connection, $insertOrder);
          if(!$result) {
            echo '<h4 class="text-danger">Error submitting order information. Please try again.</h4>';
            die();
          }
          $orderID = mysqli_insert_id($connection); // gets the id of the newly added order

          $cartItems = array();
          $insertItems = "INSERT INTO FramedOrderItems (orderID, productID, frame) VALUES ";
          foreach ($_SESSION['cart'] as $itemID => $itemArray) {
            foreach ($itemArray as $item) {
              array_push($cartItems, "($orderID, $itemID, '{$item['frame']}')");
            }
          }
          // Builds VALUES sets - (orderID, itemID, frame) to be added to the Order Items table
          $insertItems .= implode(", ", $cartItems).";";

          $result2 = mysqli_query($connection, $insertItems);
          if(!$result2) {
            echo '<h4 class="text-danger">Error submitting order information. Please try again.</h4>';
            die();
          }
          mysqli_close($connection);
        }
      }

      // Function that checks if input is valid
      function checkForErrors() {
        $errors = array();
        if(empty($_POST['name']) || !preg_match("/^[a-zA-Z'\s-]*$/", $_POST['name'])) {
          $errors['name'] = "Hmm, is that your name? Please only use letters dashes, spaces, and apostrophes";
        }
        if(!preg_match("/^\d{1,10} [\w\.\s,#-]*$/", $_POST['stAddress'])) {
          $errors['stAddress'] = "Your address doesn't look quite right. Make sure it starts with number and doesn't have any symbols besides .,#-";
        }
        if(!empty($_POST['stAddress2']) && !preg_match("/^[\w\.\s,#-]*$/", $_POST['stAddress2'])) {
          $errors['stAddress2'] = "Your address doesn't look quite right. Make sure it doesn't have any symbols besides .,#-";
        }
        if(empty($_POST['city']) || !preg_match("/^[A-Za-z\.\s-]*$/", $_POST['city'])) {
          $errors['city'] = "Invalid city! Enter only letters, spaces, or these symbols .-";
        }
        if(!preg_match("/^[A-Z]{2}$/", $_POST['state'])) {
          $errors['state'] = "Invalid state! Enter only the two letter abbreviation in all caps";
        }
        if(!preg_match("/^\d{5}$/", $_POST['zip'])) {
          $errors['zip'] = "Invalid zip code! Enter only 5 numbers.";
        }
        if(!preg_match("/^\d{3}-\d{3}-\d{4}$/", $_POST['phNum'])) {
          $errors['phNum'] = "Invalid phone number! Please enter in 123-456-7890 format";
        }
        $shippingOptions = array("Standard", "2 Day", "1 Day");
        if(empty($_POST['method']) || !in_array($_POST['method'], $shippingOptions)) {
          $errors['method'] = "Please select a valid shipping method";
        }
        return $errors;
      }

      // Function that sanitizes the input data to make sure there are no MySQL or XSS issues
      function sanitizeData() {
        global $connection;
        foreach ($_POST as $key => $value) {
          $sanitized[$key] = htmlspecialchars(mysqli_escape_string($connection, $value));
        }
        return $sanitized;
      }
    ?>
    <?php if(empty($_POST) || !empty($errors)): ?>
    <!-- If the form hasn't been filled out yet or there are errors -->
      <div class="container" id="order-form">
        <h3>Checkout</h3>
        <form action="" method="post">
          <div class="form-group">
            <label>Name</label>
            <?php if(!empty($errors['name'])) echo "<small class=\"text-danger\">{$errors['name']}</small>"; ?>
            <input class="form-control" type="text" name="name" placeholder="John Smith" value="<?php if(!empty($_POST['name'])) echo $_POST['name']; ?>">
          </div>
          <div class="form-group">
            <label>Street Address</label>
            <?php if(!empty($errors['stAddress'])) echo "<small class=\"text-danger\">{$errors['stAddress']}</small>"; ?>
            <input class="form-control" type="text" name="stAddress" placeholder="4500 5th Ave" value="<?php if(!empty($_POST['stAddress'])) echo $_POST['stAddress']; ?>">
          </div>
          <div class="form-group">
            <label>Street Address 2</label>
            <?php if(!empty($errors['stAddress2'])) echo "<small class=\"text-danger\">{$errors['stAddress2']}</small>"; ?>
            <input class="form-control" type="text" name="stAddress2" placeholder="Unit 204" value="<?php if(!empty($_POST['stAddress2'])) echo $_POST['stAddress2']; ?>">
          </div>
          <div class="form-group">
            <label>City</label>
            <?php if(!empty($errors['city'])) echo "<small class=\"text-danger\">{$errors['city']}</small>"; ?>
            <input class="form-control" type="text" name="city" placeholder="Pittsburgh" value="<?php if(!empty($_POST['city'])) echo $_POST['city']; ?>">
          </div>
          <div class="form-group">
            <label>State</label>
            <?php if(!empty($errors['state'])) echo "<small class=\"text-danger\">{$errors['state']}</small>"; ?>
            <input class="form-control" type="text" name="state" placeholder="PA" value="<?php if(!empty($_POST['state'])) echo $_POST['state']; ?>">
            <small class="text-muted">Please enter the two letter abbreviation (PA)</small>
          </div>
          <div class="form-group">
            <label>Zip</label>
            <?php if(!empty($errors['zip'])) echo "<small class=\"text-danger\">{$errors['zip']}</small>"; ?>
            <input class="form-control" type="text" name="zip" placeholder="15213" value="<?php if(!empty($_POST['zip'])) echo $_POST['zip']; ?>">
          </div>
          <div class="form-group">
            <label>Phone Number</label>
            <?php if(!empty($errors['phNum'])) echo "<small class=\"text-danger\">{$errors['phNum']}</small>"; ?>
            <input class="form-control" type="tel" name="phNum" placeholder="412-555-0122" value="<?php if(!empty($_POST['phNum'])) echo $_POST['phNum']; ?>">
            <small class="text-muted">Please enter in 123-456-7890 format</small>
          </div>
          <div class="form-group">
            <label>Shipping Method</label>
            <?php if(!empty($errors['method'])) echo "<small class=\"text-danger\">{$errors['method']}</small>"; ?>
            <select class="form-control" name="method">
              <option value="">Select a shipping method...</option>
              <option <?php if(!empty($_POST) && $_POST['method'] == 'Standard') echo "selected"; ?>>Standard</option>
              <option <?php if(!empty($_POST) && $_POST['method'] == '2 Day') echo "selected"; ?>>2 Day</option>
              <option <?php if(!empty($_POST) && $_POST['method'] == '1 Day') echo "selected"; ?>>1 Day</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">Submit Order!</button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <!-- Success page that will show after order is successfully submitted -->
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-4 text-success">Successfully Ordered!</h1>
          <p class="lead">Thanks for you order. We hope you will enjoy it.</p>
        </div>
      </div>
      <?php $_SESSION['cart'] = array(); ?>
    </div>
    <?php endif;
      include('../partials/footer.php');
    ?>
  </body>
</html>
