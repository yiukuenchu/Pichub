<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      DEFINE("PAGE_TITLE", "Home");
      require('./partials/head.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/home.css'); ?>">
  </head>
  <body>
    <?php include('./partials/navbar.php'); ?>
    <div class="full-image">
      <div class="container">
        <h1 class="display-4">PicHub，为您提供高质量的摄影展览</h1>
      </div>
    </div>
    <?php include('./partials/footer.php'); ?>
  </body>
</html>