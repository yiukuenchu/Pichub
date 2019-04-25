<?php
  $connection =  mysqli_connect($_ENV['DB_HOSTNAME'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);
  
  if(mysqli_connect_errno()) {
    die("Error connecting to the database: ". mysqli_connect_error());
  }
?>
