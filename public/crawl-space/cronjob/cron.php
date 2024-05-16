<?php
include '../config.php';
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
$time = intval(date('H'));
// if ($time == 00 || $time == 01) {
//     for ($i = 0; $i <= 4; $i++) {
//         crawl_estate($url[$i], 1);
//     }
// }
$timeRanges = [
    [0, 1], [2, 3], [4, 5], [6, 7], [8, 9], [10, 11], [12, 13], [14, 15], [16, 17], [18, 19], [20, 21], [22, 23]
];

// Determine the current hour
$currentHour = date('H');

// Find the matching time range
foreach ($timeRanges as $index => $range) {
    if ($currentHour >= $range[0] && $currentHour <= $range[1]) {
        // Execute the crawl_estate function for the corresponding URL range
        $startIndex = $index * 5;
        $endIndex = min($startIndex + 4, count($url) - 1);
        for ($i = $startIndex; $i <= $endIndex; $i++) {
            crawl_estate($url[$i], 1);
        }

        break; // Exit the loop once the range is found
    }
}
