<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'config.php';
include 'vendor/autoload.php';


$sql = "SELECT * FROM bravo_spaces WHERE map_lat = ''";
$result = $conn->query($sql);
// var_dump($result);die;
foreach ($result as $row) {
  $id = $row['id'];
  $delete = "DELETE FROM bravo_spaces WHERE id = $id";
  if ($conn->query($delete)) {
    echo 'ok';
  } else {
    echo "Error: $delete " . mysqli_error($conn);

  };
}
