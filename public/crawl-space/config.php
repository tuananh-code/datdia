<?php

$servername = "localhost";
$username = "datdia";
$password = "2JytKjf4h8FbxQNCGb1H";
$dbname = "datdia";

$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
