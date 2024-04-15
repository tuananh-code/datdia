<?php
include '../vendor/autoload.php';
// use processResponse;
use GuzzleHttp\Client;
// var_dump($_POST['location']);die;
function lat_long_convert($get_location, $result)
{
    if ($result) {
        include '../config.php';
        $location_raw = $get_location;
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

        if ($data['features'][0]) {
            $sql_update = "UPDATE bravo_spaces SET map_lat = '$lat', map_lng= '$long' WHERE address = '$location'";
            if (mysqli_query($conn, $sql_update)) {
                echo $lat . '//' . $long . '<br>' . $location;
            } else {
                echo "Error: $sql_update " . mysqli_error($conn);
            }
            mysqli_close($conn);
        } else {
            $sql_space = "SELECT * FROM bravo_spaces WHERE address = '$location'";
            $result = $conn->query($sql_space);
            if ($result) {
                $row = mysqli_fetch_array($result);
                $id_old = $row['id'];
                $id_space = intval($row['id']) + 2;

                $sql_new = "SELECT * FROM bravo_spaces WHERE id = '$id_space'";
                $result_new = $conn->query($sql_new);
                $row_new = mysqli_fetch_array($result_new);

                $lat_update = intval($row_new['map_lat']) + 0.004;
                $long_update = intval($row_new['map_lng']) + 0.004;
                $sql_reupdate = "UPDATE bravo_spaces SET map_lat = '$lat_update', map_lng = '$long_update' WHERE address = '$id_old'";

                if (mysqli_query($conn, $sql_reupdate)) {
                    echo 'Add Lat Long' . $lat_update . '//' . $long_update . '<br>' . $location;
                } else {
                    echo "Error: $sql_reupdate " . mysqli_error($conn);
                }
            } else {
                echo "Error: $sql_space " . mysqli_error($conn);
            }
            echo "Error: Add Lat Long -> $location";
        }
    } else {
        echo 'false';
    }
}
