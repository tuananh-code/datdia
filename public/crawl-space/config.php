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
