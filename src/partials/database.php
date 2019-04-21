<?php
  // DB constants are defined in env.php which is only on the SIS server so it is not public anywhere
  $connection =  mysqli_connect($_ENV['DB_HOSTNAME'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);
  
  if(mysqli_connect_errno()) {
    die("Error connecting to the database: ". mysqli_connect_error());
  }
  
// return [
//     'connections' => [
//         'pgsql' => [
//             'driver' => 'pgsql',
//             'host' => 'ec2-23-21-129-125.compute-1.amazonaws.com',
//             'port' => '5432',
//             'database' => 'd9tdf706bflmrp',
//             'username' => 'dwwbkcnutashrg',
//             'password' => '6b9c7621c1ca612eef83b0db4160c800419ac92a48be2cb4bdd7178973fc84e5',
//             'charset' => 'utf8',
//             'prefix' => '',
//             'schema' => 'public',
//             'sslmode' => 'prefer',
//         ],
//     ],
// ];
?>
