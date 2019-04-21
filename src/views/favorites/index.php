<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      DEFINE("PAGE_TITLE", "Favorites");
      require('../partials/head.php');
      require('../partials/itemCard.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/store.css'); ?>">
  </head>
  <body>
    <div class="f-pusher">
    <?php include('../partials/navbar.php'); ?>
    <div class="jumbotron">
      <div class="container">
        <h1 class="display-4"><?php if(isset($_GET) && isset($_GET['user'])) echo "{$_GET['user']}'s"; ?> Favorites</h1>
      </div>
    </div>
    <div class="container">
        <?php
          require('../partials/database.php');
          if (isset($_GET) && !empty($_GET['user'])) {
            // Only show favorites if the user is viewing their own favorites, or the profile is public
            if((isset($_SESSION['userID']) && $_SESSION['username'] == $_GET['user']) || profileIsPublic()) {
              displayResults();
            } else {
              echo '<h4 class="text-danger">Access denied! This profile does not exist or is not public.</h4>';
            }
          } elseif(isset($_SESSION) && isset($_SESSION['username'])) {
            // show use their own favorites if no user is specified
            $_GET['user'] = $_SESSION['username'];
            displayResults();
          } else {
            // if user is not logged in and they don't specifiy a username, show an error
            echo '<h4 class="text-info">Please login or specify a username to view favorites</h4>';
            die();
          }

          // Checks whether the user's profile is set to public (or exists)
          function profileIsPublic() {
            global $connection;
            $publicQuery = "SELECT publicProfile FROM FramedUsers WHERE username = '{$_GET['user']}'";
            $result = mysqli_query($connection, $publicQuery);
            if($result && mysqli_num_rows($result) == 1) {
              return mysqli_fetch_row($result)[0];
            } else {
              return false;
            }
          }

          // Shows favorited items
          function displayResults() {
            global $connection;
            $query = "SELECT FramedProducts.productID, name, photographer, category, color, imageURL, description
                      FROM FramedProducts JOIN FramedFavorites JOIN FramedUsers
                      ON FramedProducts.productID = FramedFavorites.productID AND FramedUsers.userID = FramedFavorites.userID
                      WHERE username = '{$_GET['user']}'";

            $results = mysqli_query($connection, $query);

            if($results && mysqli_num_rows($results) != 0) {
              printItemCards($results);
            } else {
              echo '<h4 class="text-primary">No products to show</h4>';
            }
            mysqli_close($connection);
          }
        ?>
      </div> <!-- /.row -->
    </div>
  </div>
    <script src="<?php path('/js/favorite.js'); ?>"></script>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
