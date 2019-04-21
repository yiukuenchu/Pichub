<?php
  session_start();
  // If user is already logged in, redirect to home
  if(isset($_SESSION['userID'])) {
    header("Location: ../");
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      define('PAGE_TITLE', 'Register');
      include('../partials/head.php');
    ?>
  </head>
  <body>
    <?php include('../partials/navbar.php'); ?>
    <div class="jumbotron">
      <div class="container">
        <h1 class="display-4">Register</h1>
      </div>
    </div>
    <div class="container">
      <?php
        function sanitizeInput($connection) {
          $clean = array();

          foreach ($_POST as $key => $value) {
            $clean[$key] = htmlspecialchars(mysqli_escape_string($connection, $value));
          }
          $clean['hPassword'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

          return $clean;
        }

        // Check for any invalid input
        function checkForErrors() {
          $errors = array();

          if(empty($_POST['fname']) || strlen($_POST['fname']) > 20 || !preg_match("/^[a-zA-Z'\s-]*$/", $_POST['fname'])) {
            $errors['fname'] = "Hmm, is that your name? Please only use letters dashes, spaces, and apostrophes";
          }
          if(empty($_POST['lname']) || strlen($_POST['lname']) > 20 || !preg_match("/^[a-zA-Z'\s-]*$/", $_POST['lname'])) {
            $errors['lname'] = "Hmm, is that your name? Please only use letters dashes, spaces, and apostrophes";
          }
          if(strlen($_POST['email']) > 50 || !preg_match("/^[\w\.+_-]*@[\w\.+_-]*\.[A-Za-z\.]*$/", $_POST['email'])) {
            $errors['email'] = "Invalid email address. Only numbers, letters, and .-+_ are allowed";
          }
          if(empty($_POST['username']) || strlen($_POST['username']) > 20 || !preg_match("/^[a-zA-Z0-9_\.-]*$/", $_POST['username'])) {
            $errors['username'] = "Hmm, is that your name? Please only use letters, numbers, dashes, underscores, and periods";
          }
          if(empty($_POST['password']) || strlen($_POST['password']) < 8) {
            $errors['password'] = "Make sure your password is at least 8 characters long";
          }

          return $errors;
        }

        if(!empty($_POST)) {
          require('../partials/database.php');

          $clean = sanitizeInput($connection);
          $errors = checkForErrors();

          if(!isset($errors) || empty($errors)) {
            $query = "INSERT INTO FramedUsers (firstName, lastName, username, password, email) VALUES('{$clean['fname']}', '{$clean['lname']}', '{$clean['username']}', '{$clean['hPassword']}', '{$clean['email']}')";
            $result = mysqli_query($connection, $query);

            if(!$result) {
              // Display error if error submitting
              echo "<h5 class=\"text-danger\">Error creating account!</h5>";
              mysqli_close($connection);
            } else {
              // Save userID and username to session and redirect
              $_SESSION['loggedIn'] = true;
              $_SESSION['username'] = $clean['username'];
              $_SESSION['userID'] = mysqli_insert_id($connection);
              mysqli_close($connection);
              echo '<meta http-equiv="refresh" content="0;URL=../">'; // redirect to home page
              die();
            }
          }
        }
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label>First Name</label>
          <?php if(isset($errors['fname'])) echo "<small class=\"text-danger\">{$errors['fname']}</small>";?>
          <input class="form-control" type="text" name="fname" maxlength="20" value="<?php if(isset($_POST['fname'])) echo $_POST['fname']; ?>" required>
        </div>
        <div class="form-group">
          <label>Last Name</label>
          <?php if(isset($errors['lname'])) echo "<small class=\"text-danger\">{$errors['lname']}</small>";?>
          <input class="form-control" type="text" name="lname" maxlength="20" value="<?php if(isset($_POST['lname'])) echo $_POST['lname']; ?>" required>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <?php if(isset($errors['email'])) echo "<small class=\"text-danger\">{$errors['email']}</small>";?>
          <input class="form-control" type="email" name="email" maxlength="50" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
        </div>
        <div class="form-group">
          <label>Username</label>
          <?php if(isset($errors['username'])) echo "<small class=\"text-danger\">{$errors['username']}</small>";?>
          <input class="form-control" type="text" name="username" maxlength="20" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <?php if(isset($errors['password'])) echo "<small class=\"text-danger\">{$errors['password']}</small>";?>
          <input class="form-control" type="password" name="password" minlength="8" required>
        </div>
        <div class="form-group">
          <button class="btn btn-primary" type="submit">Register</button>
        </div>
      </form>
    </div>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
