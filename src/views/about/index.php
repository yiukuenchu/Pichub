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
            <p>Hi，我是开发者 <strong>朱曜锟</strong>。 PicHub 是一个与您分享高质量摄影图片的平台。这里有许多摄影师的优秀作品，从自然风光，到人文环境，再到城市建筑，你可以在这里欣赏到各种风格类型的图片。更重要的是，你可以把它们买下来！我们会根据您的要求对画框进行定制，并在最短时间内邮寄到您的家里，让您足不出户便能享受画展的熏陶。</p>
            <p>无论您是搬到新的公寓、宿舍或是房子，如果您觉得家里的墙壁看上去太单调了，我们便建议您浏览 PicHub，在这个平台上发现您喜欢的、适合您家装修风格的摄影作品，定制好画框后放置在墙上。</p>
            <p>请放心，我们承诺只采用高级的画纸和颜料，不会有异味，也不会对人体有任何危害。我们采用专业的打印技术，您得到的会和您看到的一致。</p>
            <p>如果您对我们的产品、材料、订单有任何的疑问或建议，欢迎您通过右侧联系我们，我们会尽快回复。</p>
          </div>
          <form class="col-md" action="" method="POST">
            <h3>我们致力于为每一位用户解决问题</h3>
            <p>请在下方填写您的信息和疑问，我们会尽快与您取得联系。</p>
            <div class="form-group">
              <label>姓名</label>
              <?php if(isset($errors['name'])) echo "<small class=\"text-danger\">{$errors['name']}</small>";?>
              <input class="form-control" type="text" name="name" placeholder="您的称呼" maxlength="50" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" required>
            </div>
            <div class="form-group">
              <label>邮箱</label>
              <?php if(isset($errors['email'])) echo "<small class=\"text-danger\">{$errors['email']}</small>";?>
              <input class="form-control" type="email" name="email" placeholder="请正确填写您的邮箱" maxlength="50" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
            </div>
            <div class="form-group">
              <label>您遇到的问题</label>
              <?php if(isset($errors['message'])) echo "<small class=\"text-danger\">{$errors['message']}</small>";?>
              <textarea class="form-control" rows="5" name="message" maxlength="500" value="<?php if(isset($_POST['message'])) echo $_POST['message']; ?>" required></textarea>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">提交</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
