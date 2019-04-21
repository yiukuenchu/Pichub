<?php
  session_start();
  if(!isset($_SESSION['userID'])) {
    header("Location: ../");
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
      define("PAGE_TITLE", "Account Settings");
      require('../partials/head.php');
    ?>
  </head>
  <body>
    <?php include('../partials/navbar.php'); ?>
    <div class="jumbotron">
      <div class="container">
        <h1 class="display-4">Account Settings</h1>
      </div>
    </div>
    <div class="container">
      <?php
        require('../partials/database.php');

        if(!empty($_POST)) {
          // update a user's favorites page visibility
          if(isset($_POST['visibility'])) {
            // Check for valid input before updating visibility
            if(($_POST['visibility'] == 0 || $_POST['visibility'] == 1)) {
              $updateVisibility = "UPDATE FramedUsers
                                   SET publicProfile = {$_POST['visibility']}
                                   WHERE userID = {$_SESSION['userID']}";
              $result = mysqli_query($connection, $updateVisibility);
              if(!$result) {
                echo '<h4 class="text-danger">Error updating favorites visibility</h4>';
              }
            }
          } else if(isset($_POST['currentPassword'])) {
            // get their current password
            $pwQuery = "SELECT password FROM FramedUsers WHERE userID = {$_SESSION['userID']};";
            $result = mysqli_query($connection, $pwQuery);
            $currentPW = mysqli_fetch_assoc($result)['password'];

            // See if they entered their current passwor correctly
            if(!password_verify($_POST['currentPassword'], $currentPW)) {
              $errors['currentPassword'] = "当前密码输入错误！";
            // Make sure their new password is the correct length
            } else if(strlen($_POST['newPassword1']) < 8 || strlen($_POST['newPassword1']) > 72) {
              $errors['newPassword1'] = "请确保密码为 8-72 字符长度";
            // Make sure the new password fields match
            } else if($_POST['newPassword1'] !== $_POST['newPassword2']) {
              $errors['newPassword2'] = "两次新密码不匹配！";
            } else {
              // If everything is ok, hash the new password and update it in the database
              $newPW = password_hash($_POST['newPassword1'], PASSWORD_DEFAULT);
              $updatePW = "UPDATE FramedUsers
                           SET password = '{$newPW}'
                           WHERE userID = {$_SESSION['userID']};";
              $successful = mysqli_query($connection, $updatePW);
              if($successful) {
                echo '<h4 class="text-success">密码成功修改！</h4>';
              } else {
                echo '<h4 class="text-danger">发生错误！</h4>';
              }
            }
          }
        }
        ?>
        <form action="" method="post">
          <legend>更新密码</legend>
          <fieldset>
            <div class="form-group">
              <label for="currentPassword">当前密码</label>
              <?php if(isset($errors['currentPassword'])) echo "<small class=\"text-danger\">{$errors['currentPassword']}</small>";?>
              <input class="form-control" name="currentPassword" type="password" required="true">
            </div>
            <div class="form-group">
              <label for="newPassword1">新密码</label>
              <?php if(isset($errors['newPassword1'])) echo "<small class=\"text-danger\">{$errors['newPassword1']}</small>";?>
              <input class="form-control" name="newPassword1" type="password" required="true" minlength="8" maxlength="72">
            </div>
            <div class="form-group">
              <label for="newPassword2">重复确认新密码</label>
              <?php if(isset($errors['newPassword2'])) echo "<small class=\"text-danger\">{$errors['newPassword2']}</small>";?>
              <input class="form-control" name="newPassword2" type="password" required="true" minlength="8" maxlength="72">
            </div>
          </fieldset>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">提交</button>
          </div>
        </form>
        <?php
          // Get current favorite page visibilty
          $visibilityQuery = "SELECT publicProfile FROM FramedUsers WHERE userID = {$_SESSION['userID']};";
          $result = mysqli_query($connection, $visibilityQuery);
          $data = mysqli_fetch_assoc($result);
        ?>
        <form action="" method="post">
          <legend>更新 Favorites 可见度</legend>
          <small class="text-muted">您是否愿意公开您的 Favorites？设置为 public 就能和朋友分享 Favorites！</small>
            <div class="form-group">
              <label for="visibility">Favorites 可见度</label>
              <select class="form-control" name="visibility">
                <option value="0" <?php echo ($data['publicProfile'] == 0) ? "selected" : "" ?>>Private</option>
                <option value="1" <?php echo ($data['publicProfile'] == 1) ? "selected" : "" ?>>Public</option>
              </select>
            </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">提交</button>
          </div>
        </form>
        <?php $favURL = "/favorites/?user={$_SESSION['username']}"; ?>
        <h6>如果您的 Favorites 设置为 public, 别人能在这里看到 <a href="<?php path($favURL); ?>"><?php echo $_SERVER['SERVER_NAME'];
         path($favURL); ?></a></h6>
         <?php
          mysqli_free_result($result);
          mysqli_close($connection);
        ?>
      <a class="btn btn-danger" href="<?php path('/logout/'); ?>">Log Out</a>
    </div>
    <?php include('../partials/footer.php'); ?>
  </body>
</html>
