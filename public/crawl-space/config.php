<?php

// $servername = "152.53.16.231";
// $username = "datdia";
// $password = "2JytKjf4h8FbxQNCGb1H";
// $dbname = "datdia";
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "booking";

$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
