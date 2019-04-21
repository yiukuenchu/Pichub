<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      define("PAGE_TITLE", "Cart");
      require('../partials/head.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/cart.css'); ?>">
  </head>
  <body>
    <div class="f-pusher">
      <?php include('../partials/navbar.php'); ?>
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-4">Cart</h1>
        </div>
      </div>
      <div class="container">
        <?php
          // Remove an item from the cart
          if(isset($_POST) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            // If the item being deleted is the only one in the array, just delete the whole array
            if(count($_SESSION['cart'][$id]) == 1) {
              unset($_SESSION['cart'][$id]);
            // Otherwise remove the item and clean up the array
            } else {
              unset($_SESSION['cart'][$id][$_POST['index']]);
              $_SESSION['cart'][$id] = array_values($_SESSION['cart'][$id]);
            }
          }

          // Displays all items in the cart
          if(!empty($_SESSION['cart'])) {
            require('../partials/database.php');
            $totalPrice = 0;
            $ids = join(", ", array_keys($_SESSION['cart'])); // lookup the product info for only the items in the cart
            $itemsQuery = "SELECT productID, name, photographer, imageURL FROM FramedProducts WHERE productID IN ($ids);";
            $items = mysqli_query($connection, $itemsQuery);

            while($row = mysqli_fetch_assoc($items)) {
              $cartItemArr = $_SESSION['cart'][$row['productID']];

              for($index = 0; $index < count($cartItemArr); $index++) {
                $cartItem = $cartItemArr[$index];
                $totalPrice += $cartItem['price'];
                echo <<<HERE
                <div class="cart-item row align-items-center">
                  <div class="col-auto">
                    <img class="cart-image" src="{$row['imageURL']}/350x250/">
                  </div>
                  <div class="col d-flex justify-content-between">
                    <div>
                      <a href="{$_ENV['SERVER_ROOT']}/item/?id={$row['productID']}"><h5>{$row['name']}</h5></a>
                      <h6>{$row['photographer']}</h6>
                      <small class="text-muted">Frame: {$cartItem['frame']}</small>
                    </div>
                    <div>
                      <p><strong>\${$cartItem['price']}</strong></p>
                      <form action="" method="post">
                        <input type="hidden" name="id" value="{$row['productID']}">
                        <input type="hidden" name="index" value="{$index}">
                        <button class="btn btn-sm btn-danger" type="submit"><span class="fas fa-times"></span></button>
                      </form>
                    </div>
                  </div>
                </div>
HERE;
              }
            }
            echo "<h4 class=\"text-right\">Total: \$$totalPrice</h4>";
            echo '<a class="btn btn-success" href="'.$_ENV["SERVER_ROOT"].'/checkout/">Checkout</a>';
            mysqli_free_result($items);
            mysqli_close($connection);
          } else {
            echo '<h4 class="text-info">You don\'t seem to have anything in your cart.</h4>';
          }
        ?>
      </div>
    </div>
    <?php include('../partials/footer.php') ?>
  </body>
</html>
