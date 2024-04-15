<?php
include 'vendor/autoload.php';
include 'config_update.php';

// use processResponse;
use GuzzleHttp\Client;
// var_dump($_POST['location']);die;
$sql_update_map = "SELECT * FROM bravo_spaces WHERE id BETWEEN 12 and 732";
$result = mysqli_query($conn, $sql_update_map);
// $row = mysqli_fetch_array($result);
$all = [];
$all_address = [];

$all_lat = [];
$all_lng = [];
while ($row = mysqli_fetch_array($result)) {
    $row_address = $row['address'] . '<br>';
    $row_lat = $row['map_lat'];
    $row_lng = $row['map_lng'];
    $all_address[] = $row_address;
    $all_lat[] = $row_lat;
    $all_lng[] = $row_lng;
    $all[] = $row['content'] . '<br>';
}
// var_dump($all);
// var_dump((strlen($all_address)));die;
// if(strlen($all_address[190])>100){
//     $parts = explode(',', $all_address[190]);

//     // Lấy phần tử đầu tiên
//     $value = trim($parts[0]);

//     echo $value;die;
// }die;
for ($i = 0; $i < count($all_address); $i++) {
    $location_raw = $all_address[$i];
    if (strlen($location_raw) > 100) {
        $parts = explode(',', $location_raw);
        // Lấy phần tử đầu tiên
        $location = trim($parts[0]);
    } else {
        if (strlen($location_raw) < 6) {
            $location = $all_address[$i + 2];
        } else {
            $location = $location_raw;
        };
    };
    // if(strlen($location)> 100)
    $accessToken = "sk.eyJ1IjoidHVhbmFuaDExMjIxMiIsImEiOiJjbG5mbG9zM2owbjdpMmtvaHg1cjg1NXR5In0.4JvqB7ddH48KlbTIRRfGbQ";

    $client = new Client();
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
    // echo $location . '<br>';die;
    // echo $i;
    // echo $location . '<br>';
    // echo "Latitude: " . $lat;
    // echo "Longitude: " . $long . "<br>";
    // var_dump($data['features'][0]);die;
    // if (!empty($data['features'])) {

    // } else {
    //     echo "No results found.";
    // }
    if ($data['features'][0]) {
        $sql_update = "UPDATE bravo_spaces SET map_lat = '$lat', map_lng= '$long' WHERE address = '$location'";
        $sql_location = "UPDATE bravo_locations set status = 'publish', created_at = now(), updated_at = now() WHERE id between 1 and 100";
        if (mysqli_query($conn, $sql_update)) {
            mysqli_query($conn, $sql_location);
            echo $i;
        } else {
            echo "Error: $sql_update " . mysqli_error($conn);
        }
        // mysqli_close($conn);
    }
}
