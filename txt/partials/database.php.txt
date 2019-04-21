<?php
  // DB constants are defined in env.php which is only on the SIS server so it is not public anywhere
  $connection = mysqli_connect($_ENV['DB_HOSTNAME'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_USERNAME']);
  if(mysqli_connect_errno()) {
    die("Error connecting to the database: ". mysqli_connect_error());
  }
?>
