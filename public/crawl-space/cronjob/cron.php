<?php


$servername = "localhost";
$username = "datdia";
$password = "eczFYNuAJR1OviyD1W2E";
$dbname = "datdia";

$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


require './get_link.php';


$sql_path = "SELECT * FROM bravo_cron WHERE id = 1";
$row = mysqli_fetch_array($conn->query($sql_path));
$xpath = $row['xpath'];
$info = $row['info'];
$detail = $row['detail'];

$sql_url = "SELECT * FROM bravo_cron WHERE id != 1";
$result = $conn->query($sql_url);
while ($row_url = mysqli_fetch_array($result)) {
    $url[] = $row_url['url'];
};
// var_dump($url[0]);die;
// count($url)
$date = date('d') % 10;
if ($date == 0 || $date == 1) {
    for ($i = 0; $i <= 11; $i++) {
        crawl_estate($url[$i], 1);
    }
} else if ($date == 2 || $date == 3) {
    for ($i = 12; $i <= 23; $i++) {
        crawl_estate($url[$i], 1);
    }
} else if ($date == 4 || $date == 5) {
    for ($i = 24; $i <= 35; $i++) {
        crawl_estate($url[$i], 1);
    }
} else if ($date == 6 || $date == 7) {
    for ($i = 36; $i <= 47; $i++) {
        crawl_estate($url[$i], 1);
    }
} else if ($date == 8 || $date == 9) {
    for ($i = 48; $i <= 62; $i++) {
        crawl_estate($url[$i], 1);
    }
}
