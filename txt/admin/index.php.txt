<?php
  session_start();
  // User must be logged in and an Admin to view this page
  if(!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../");
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      define("PAGE_TITLE", "Dashboard");
      require('../partials/head.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/admin.css'); ?>">
  </head>
  <body>
    <div class="f-pusher">
      <?php
        include('../partials/navbar.php');
        include('../partials/adminSidebar.php');
        include('../partials/database.php');
      ?>
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-4">Dashboard</h1>
        </div>
      </div>
      <div class="container">
        <!-- Shows a table of orders with the status "Pending" -->
        <div class="card">
          <div class="card-header text-white bg-success">
            <span class="fas fa-shipping-fast"></span> Pending Orders
          </div>
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Shipping</th>
                <th>Status</th>
                <th>Date Ordered</th>
              </tr>
              <?php
                $orderQuery = "SELECT orderID, name, shippingMethod, status, timestamp FROM FramedOrders WHERE status = 'Processing';";
                $results = mysqli_query($connection, $orderQuery);
                if($results) {
                  while($row = mysqli_fetch_assoc($results)) {
                    $dateString = date('M j, Y g:i A', strtotime($row['timestamp']));
                    echo <<<HERE
                      <tr>
                       <td>{$row['orderID']}</td>
                       <td>{$row['name']}</td>
                       <td>{$row['shippingMethod']}</td>
                       <td>{$row['status']}</td>
                       <td>{$dateString}</td>
                     </tr>
HERE;
                  }
                  mysqli_free_result($results);
                }
              ?>
            </table>
          </div>
        </div>
        <br>
        <!-- Shows a table of messages submitted to the contact form from the about page -->
        <div class="card">
          <div class="card-header text-white bg-info">
            <span class="fas fa-envelope"></span> Contact Form
          </div>
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
              </tr>
              <?php
                $contactQuery = "SELECT name, email, message, timestamp FROM FramedContactForm;";
                $messages = mysqli_query($connection, $contactQuery);

                if($messages) {
                  while($row = mysqli_fetch_assoc($messages)) {
                    $dateString = date('M j, Y g:i A', strtotime($row['timestamp']));
                    echo <<<HERE
                      <tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['message']}</td>
                        <td>{$dateString}</td>
                      </tr>
HERE;
                  }
                  mysqli_free_result($messages);
                }
              ?>
            </table>
          </div>
        </div>
        <br>
        <!-- Shows a table of the top 5 selling products -->
        <div class="card">
          <div class="card-header text-white bg-danger">
            <span class="fas fa-trophy"></span> Most Popular Items
          </div>
          <table class="table">
            <tr>
              <th>Item</th>
              <th>Number Sold</th>
            </tr>
            <?php
              $popularQuery = "SELECT FramedProducts.productID as id, name, count(name) as qty
                               FROM FramedOrderItems JOIN FramedProducts on FramedOrderItems.productID = FramedProducts.productID
                               GROUP BY name ORDER BY qty DESC LIMIT 5;";
              $popItems = mysqli_query($connection, $popularQuery);

              if($popItems) {
                while($row = mysqli_fetch_assoc($popItems)) {
                  echo <<<HERE
                    <tr>
                      <td><a href="{$_ENV['SERVER_ROOT']}/item/?id={$row['id']}">{$row['name']}</a></td>
                      <td>{$row['qty']}</td>
                    </tr>
HERE;
                }
                mysqli_free_result($popItems);
              }
              mysqli_close($connection);
            ?>
          </table>
        </div>
      </div>
    </div>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
