<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'config.php';
include 'vendor/autoload.php';

use GuzzleHttp\Client;

$sql = "SELECT * FROM `bravo_spaces` WHERE map_lng < 0 Or map_lat < 0 OR map_lat='' OR map_lat < 8 OR map_lat > 24 OR map_lng < 100 OR map_lng > 110";
$result = $conn->query($sql);
// var_dump($result);die;
$accessToken = "sk.eyJ1IjoidHVhbmFuaDExMjIxMiIsImEiOiJjbG5mbG9zM2owbjdpMmtvaHg1cjg1NXR5In0.4JvqB7ddH48KlbTIRRfGbQ";
$client = new Client();
if ($result->num_rows > 0) {
  foreach ($result as $row) {
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
        echo $lat . '//' . $long . '<br>' . $address;
      } else {
        echo "Error: $sql_update " . mysqli_error($conn);
      }
    }
  }
  // $ids = implode(',', $id);
  // $delete = "DELETE FROM bravo_spaces WHERE id in ($ids)";
  // if ($conn->query($delete)) {
  //   echo 'ok';
  // } else {
  //   echo "Error: $delete " . mysqli_error($conn);
  // };
} else {
  echo 'none';
}
