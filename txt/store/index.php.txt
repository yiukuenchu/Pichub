<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      DEFINE("PAGE_TITLE", "Store");
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
          <h1 class="display-4">Store</h1>
        </div>
      </div>
      <div class="container">
          <?php
            require('../partials/database.php');
            $query = "SELECT * FROM FramedProducts";

            // queries to show options in filter box
            $colorsQ = "SELECT DISTINCT color FROM FramedProducts";
            $categoriesQ = "SELECT DISTINCT category FROM FramedProducts";

            // Filter results if set in url
            if(isset($_GET['category'])) {
              $query .= " WHERE category='{$_GET['category']}'";
            } else if(isset($_GET['color'])) {
              $query .= " WHERE color='{$_GET['color']}'";
            }
            $results = mysqli_query($connection, $query);

            if($results) {
              $colors = mysqli_query($connection, $colorsQ);
              $categories = mysqli_query($connection, $categoriesQ);

              // filter options
              print_filter_box($colors, $categories);

              printItemCards($results);
              mysqli_free_result($results);
              mysqli_free_result($colors);
              mysqli_free_result($categories);
            } else {
              echo '<h4 class="text-primary">No products to show</h4>';
            }
            mysqli_close($connection);

            function print_filter_box($colors, $categories) {
              print <<<HERE
              <button class="btn btn-link" id="btn-filter">Filter <span class="fas fa-chevron-down"></span></button>
              <div id="filter-options" class="d-none"><ul><li>Category<ul><li>
HERE;
                while($catName = mysqli_fetch_array($categories)) {
                  echo "<span class=\"badge badge-pill badge-light\"><a href=\"{$_ENV['SERVER_ROOT']}/store/?category={$catName[0]}\">{$catName[0]}</a></span>";
                }
                echo "</li></ul></li><li>Color<ul><li>";
                while($colorName = mysqli_fetch_array($colors)) {
                  echo "<span class=\"badge badge-pill badge-light\"><a href=\"{$_ENV['SERVER_ROOT']}/store/?color={$colorName[0]}\">{$colorName[0]}</a></span>";
                }
                echo "</li></ul></li></ul><a class=\"btn btn-sm btn-dark\" href=\"{$_ENV['SERVER_ROOT']}/store/\">&times; clear</a></div>";
            }
          ?>
        </div> <!-- /.row -->
      </div>
    </div>
    <script src="<?php path('/js/favorite.js'); ?>"></script>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
