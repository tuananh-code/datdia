<?php 
include 'vendor/autoload.php';
use GuzzleHttp\Client;
$location = 'Nhà số 59 Lô 7A, khu X2A, Đường Hưng Thịnh, Phường Yên Sở, Quận Hoàng Mai, Hà Nội';
$accessToken = "sk.eyJ1IjoidHVhbmFuaDExMjIxMiIsImEiOiJjbG5mbG9zM2owbjdpMmtvaHg1cjg1NXR5In0.4JvqB7ddH48KlbTIRRfGbQ";
$client = new Client();

$url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . $location . ".json?proximity=ip&access_token=" . $accessToken . "";
$response = $client->request('GET', $url, [
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
    ],
]);
$data = json_decode($response->getBody(), true);

$lat = $data['features'][0]['center'][1];
$long = $data['features'][0]['center'][0];
if (!empty($data['features'])) {
    echo "Latitude: " . $lat . "<br>";
    echo "Longitude: " . $long . "<br>";
} else {
    echo "No results found.";
}
