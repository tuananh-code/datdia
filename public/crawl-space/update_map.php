<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'config.php';
include 'vendor/autoload.php';

use GuzzleHttp\Client;

$accessToken = "sk.eyJ1IjoidHVhbmFuaDExMjIxMiIsImEiOiJjbG5mbG9zM2owbjdpMmtvaHg1cjg1NXR5In0.4JvqB7ddH48KlbTIRRfGbQ";
$client = new Client();
if ($_POST['action'] == 'update') {
  $sql = "SELECT * FROM `bravo_spaces` WHERE map_lng < 0 Or map_lat < 0 OR map_lat='' OR map_lat < 8 OR map_lat > 24 OR map_lng < 100 OR map_lng > 110";
  $result = $conn->query($sql);
  // var_dump($result);die;
  if ($result->num_rows > 0) {
    foreach ($result as $row) {
      if ($row['location_id'] > 8) {
        $id[] = $row['id'];
        $address = $row['address'];
        $address2 = explode(',', $address);
        $address3 = array_slice($address2, -3);
        $location = implode(',', $address3);
        $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . $location . ".json?proximity=ip&access_token=" . $accessToken . "";
        // var_dump($url);die;
        // $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . urlencode($location) . ".json";
        $response = $client->request('GET', $url, [
          'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
          ],
        ]);


        $data = json_decode($response->getBody(), true);
        $lat = $data['features'][0]['center'][1];
        $long = $data['features'][0]['center'][0];

        if ($data['features'][0]) {
          $sql_update = "UPDATE bravo_spaces SET map_lat = '$lat', map_lng= '$long' WHERE address = '$address'";
          if (mysqli_query($conn, $sql_update)) {
            $sql2 = "SELECT * FROM `bravo_spaces` WHERE map_lng < 0 Or map_lat < 0 OR map_lat='' OR map_lat < 8 OR map_lat > 24 OR map_lng < 100 OR map_lng > 110";
            $result2 = $conn->query($sql2);
            foreach ($result2 as $row2) {
              $id2 = $row2['id'];
              $delete = "DELETE FROM bravo_spaces WHERE id = $id2";
              if ($conn->query($delete)) {
                echo 'delete ok';
              } else {
                echo "Error: $delete " . mysqli_error($conn);
              };
            }
          } else {
            echo "Error: $sql_update " . mysqli_error($conn);
          }
        }
      }
    }
  } else {
    echo 'none';
  }
} else {
  $sql = "SELECT map_lat, map_lng FROM bravo_spaces GROUP BY map_lat, map_lng HAVING ( COUNT(*) > 1 )";
  $result = $conn->query($sql);
  foreach ($result as $row) {
    $map_lat = $row['map_lat'];
    $map_lng = $row['map_lng'];
    $sql_exist = "SELECT title, location_id, map_lat, map_lng FROM bravo_spaces WHERE map_lat = $map_lat AND map_lng = $map_lng";
    $result_exist = $conn->query($sql_exist);
    foreach ($result_exist as $row_exist) {
      $location_id = $row_exist['location_id'];
      if ($location_id == 13) {
        $random_lat = mt_rand(99, 999) / mt_rand(100000, 200000);
        $random_long = mt_rand(99, 999) / mt_rand(100000, 200000);
      } else {
        $random_lat = mt_rand(99, 999) / mt_rand(10000, 20000);
        $random_long = mt_rand(99, 999) / mt_rand(10000, 20000);
      }

      $title = $row_exist['title'];
      $lat = $row_exist['map_lat'] + $random_lat;
      $long = $row_exist['map_lng'] + $random_long;
      $sql_update = "UPDATE bravo_spaces SET map_lat = $lat, map_lng = $long WHERE title = '$title'";
      if ($conn->query($sql_update)) {
        echo 'done';
      } else {
        echo "Error: $sql_update " . mysqli_error($conn);
      }
    }
  }
}
