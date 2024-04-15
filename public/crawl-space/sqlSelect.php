<?php
$servername = "45.252.249.32";
$username = "datdia_user";
$password = "volam2003";
$dbname = "datdia_booking";

$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql_banner = "SELECT id FROM `media_files` WHERE file_name = 'banner-trang-chu'";
$result_banner = mysqli_query($conn, $sql_banner);
$row_banner = mysqli_fetch_assoc($result_banner);
$banner_id = $row_banner['id'];
var_dump($banner_id);