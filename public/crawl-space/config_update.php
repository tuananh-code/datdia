<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "booking_update";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>