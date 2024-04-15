<?php 
require_once 'config.php';
$url = $_POST['url'];
foreach ($url as $u){
    $sql = "INSERT INTO bravo_cron (url) VALUES ('$u')";
    if($conn->query($sql)){
        echo 'done';
    }else{
        echo "Error: $sql " . mysqli_error($conn);

    }
}