<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      DEFINE("PAGE_TITLE", "About");
      require('../partials/head.php');
    ?>
    <link rel="stylesheet" href="<?php path('/css/about.css'); ?>">
  </head>
  <body>
    <div class="f-pusher">
      <?php include('../partials/navbar.php'); ?>
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-4">About Us</h1>
        </div>
      </div>
      <div class="container">
        <?php
          require('../partials/database.php');

          // Function that checks if input is valid
          function checkForErrors() {
            $errors = array();
            if(empty($_POST['name']) || !preg_match("/^[a-zA-Z'\s-]*$/", $_POST['name']) || strlen($_POST['name']) > 50) {
              $errors['name'] = "The item name doesn't look quite right. Please only use letters dashes, spaces, and apostrophes";
            }
            if(strlen($_POST['email']) > 50 || !preg_match("/^[\w\.+_-]*@[\w\.+_-]*\.[A-Za-z\.]*$/", $_POST['email'])) {
              $errors['email'] = "Invalid email address. Only numbers, letters, and .-+_ are allowed";
            }
            if(empty($_POST['message']) || strlen($_POST['message']) > 500) {
              $errors['message'] = "The message doesn't look quite right.";
            }
            return $errors;
          }

          function sanitizeInput($connection) {
            $clean = array();

            foreach ($_POST as $key => $value) {
              $clean[$key] = htmlspecialchars(mysqli_escape_string($connection, $value));
            }
            return $clean;
          }

          // Recieves POST data and saves it to database after verifying and sanitizing it
          if(isset($_POST) && isset($_POST['name'])) {
            $errors = checkForErrors();

            if(empty($errors)) {
              $clean = sanitizeInput($connection);

              $contactQuery = "INSERT INTO FramedContactForm (name, email, message)
                               VALUES ('{$clean['name']}', '{$clean['email']}', '{$clean['message']}');";
              $successful = mysqli_query($connection, $contactQuery);

              if($successful) {
                echo '<h4 class="text-success">Form Submitted!</h4>';
                $_POST = array(); // clear form
              } else {
                echo '<h4 class="text-danger">Error submitting form!</h4>';
              }
            }
            mysqli_close($connection);
          }
        ?>
        <div class="row">
          <div class="col-md">
            <p>Framed sells prints of a wide variety of different photos from space images, to city scapes, to nature. You can get just a plain print or have it professionally framed in one of several high quality frames. Several different size options are available so you can get just the right size to fill that empty space on your wall.</p>
            <p>Whether you are moving into a new apartment, dorm, house, or just have an empty space on your wall we are here to provide you with the perfect high quality print. We have all sorts of images with your choice of frame so you can get something that fits the look you are going for.</p>
            <p>All of out prints are printed on high quality paper with premium inks to make sure they look great for years.</p>
            <p>If you have any questions about our products, materials, or your order, please reach out to us! We will be happy to assist you!</p>
          </div>
          <form class="col-md" action="" method="POST">
            <h3>What can we help you with?</h3>
            <p>Fill out this form and we will get back to you as soon as possible</p>
            <div class="form-group">
              <label>Name</label>
              <?php if(isset($errors['name'])) echo "<small class=\"text-danger\">{$errors['name']}</small>";?>
              <input class="form-control" type="text" name="name" placeholder="Noah" maxlength="50" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" required>
            </div>
            <div class="form-group">
              <label>Email Address</label>
              <?php if(isset($errors['email'])) echo "<small class=\"text-danger\">{$errors['email']}</small>";?>
              <input class="form-control" type="email" name="email" placeholder="noah@framed.com" maxlength="50" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
            </div>
            <div class="form-group">
              <label>Message</label>
              <?php if(isset($errors['message'])) echo "<small class=\"text-danger\">{$errors['message']}</small>";?>
              <textarea class="form-control" rows="5" name="message" maxlength="500" value="<?php if(isset($_POST['message'])) echo $_POST['message']; ?>" required></textarea>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
