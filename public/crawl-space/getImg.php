<?php
$imageUrl = 'https://alonhadat.com.vn/files/properties/2023/10/2/thumbnails/20231002_092234_838158_0.jpg';
$fileName = basename($imageUrl);
@$rawImage = file_get_contents($imageUrl);
if ($rawImage) {
    file_put_contents("img/" . $fileName, $rawImage);
    echo 'Image Saved';
} else {
    echo 'Error Occured';
}


