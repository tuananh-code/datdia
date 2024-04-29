<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'config.php';
include 'vendor/autoload.php';

$sql = "SELECT * FROM `bravo_spaces` WHERE map_lng < 0 Or map_lat < 0 OR map_lat='' OR map_lat < 8 OR map_lat > 24 OR map_lng < 100 OR map_lng > 110";
$result = $conn->query($sql);
// var_dump($result);die;
if ($result->num_rows > 0) {
  foreach ($result as $row) {
    $id[] = $row['id'];
    $id_location[] = $row['location_id'];
    $location_id = $row['location_id'];
    $located = "SELECT * FROM bravo_locations WHERE id = $location_id";
    $result_location = $conn->query($located);
    $row_location = mysqli_fetch_array($result_location);
    $locations[] = $row_location['name'];
  }
  $ids = implode(',', $id);
  $delete = "DELETE FROM bravo_spaces WHERE id in ($ids)";
  if ($conn->query($delete)) {
    echo 'ok';
  } else {
    echo "Error: $delete " . mysqli_error($conn);
  };
} else {
  echo 'none';
}
