<?php
// include 'functionVnStr.php' ;
function crawl($url, $page)
{
    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url); // Set the URL to crawl
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($curl, CURLOPT_USERAGENT, 'MyBot'); // Set a user agent to identify your bot

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        echo 'Error: ' . curl_error($curl);
        return;
    }

    // Close cURL
    curl_close($curl);

    // Process the response
    processResponse($response, $page);
}

function processResponse($response)
{
    // include 'config.php';

    // Parse the HTML using a DOM parser library like DOMDocument or SimpleHTMLDom
    // Here's an example using DOMDocument
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);
    libxml_clear_errors();

    $xpath = new DOMXpath($dom);
    $link = 'https://muaban.net/bat-dong-san/ban-nha-mat-tien';
    $user_name = $xpath->query('//*[@id="__NEXT_DATA__"]')->item(0)->nodeValue;
    $array = json_decode($user_name, true); // get Json value
    var_dump($user_name);
    // $href = $_GET['href'];
    // $all_img = $xpath->query('//*[@id="dark_theme"]/section/div/div/div/div/article/h3');
    $all_img = $xpath->query('//*[contains(@class, "title-news")]/a');

    foreach ($all_img as $img) {
        $va = $img->nodeValue;
        echo $va . '<br>';
    }
    die;
    $names = $xpath->query('//*[contains(@class, "item-post")]/a');
    $infos = $xpath->query('//*[contains(@class, "dGTvSk")]/ul');
    $prices = $xpath->query('//*[contains(@class, "price")]');
    $locations = $xpath->query('//*[contains(@class, "hYvkOS")]');
    $user_name = $xpath->query('//*[@id="__NEXT_DATA__"]')->item(0)->nodeValue;
    $img = $xpath->query('//*[contains(@class, "slick-track")]')->item(0);
    $image = $xpath->query('.//img', $img);
    // $get = [];
    // foreach ($user_name as $im) {
    //     $value = $im->nodeValue;
    //     $get[] = $value;
    //     // echo ($value);
    // }
    $array = json_decode($user_name, true);
    // var_dump($get);die;
    var_dump($array['props']['pageProps']);
    die;
    // $all_img = $xpath->query('//*[contains(@class, "kzipBv")]/img'); //*[@id="__next"]/div[2]/div[3]/div/div[1]/div[1]/div[1]/div/div/div[8]/div/div/img
    $all_img = $xpath->query('//*[@id="__next"]/div[2]/div[3]/div/div[1]/div[1]/div[1]/div/div/div[4]/div/div/img');

    // $all_img  = $xpath->query('.//img', $all_img_raw);
    // Lặp qua các hàng (rows) trong bảng
    $get = [];
    foreach ($all_img as $img) {
        $value = $img->getAttribute('src');
        $get[] = $value;
    }
    var_dump($get);
    die;
    $get_name = [];
    $get_href = [];
    foreach ($names as $name) {
        $value_name = $name->nodeValue;
        $href = $name->getAttribute('href');
        $get_href[] = $link . $href;
        $get_name[] = $value_name;
    }
    $get_price = [];
    foreach ($prices as $price) {
        $value_price = $price->nodeValue;
        $get_price[] = $value_price;
    }
    $get_info = [];
    foreach ($infos as $info) {
        $value_info = $info->nodeValue;
        $get_info[] = $value_info;
    }
    $get_location = [];
    foreach ($locations as $location) {
        $value_location = $location->nodeValue;
        $get_location[] = $value_location;
    }

    var_dump($get_price);
    die;
    $rows = $xpath->query('.//tr', $table);
    foreach ($rows as $row) {
        // Lặp qua các ô (cells) trong hàng
        $cells = $xpath->query('.//td', $row);
        foreach ($cells as $cell) {
            // Lấy giá trị của ô
            $value = trim($cell->nodeValue);
            echo $value . "<br>";
        }
    }    // $pages = $xpath->query('//*[@id="chapters"]/div[1]/ul');            // this is all div
    // $href = $xpath->query('//*[@id="left"]/div[1]/div[1]/div[3]/a');
    $img = $xpath->query('//*[@id="left"]/div[1]/div[7]')->item(0); //*[@id="left"]/div[1]/div[2]
    $image = $xpath->query('.//img', $img);
    die;
    $get = [];
    foreach ($texts as $text) {
        $src = $text->nodeValue;
        $get[] = $src;
    }
    // $get = [];
    // $values = $href->item(0);
    // $all = $values->getAttribute('href');
    // $get[] = $values;
    // var_dump ($all);
    // $node = $img->item(0);
    // $get = $node->getAttribute('src');
    // $imageUrl = $link . $get;
    // $fileName = basename($imageUrl);

    // @$rawImage = file_get_contents($imageUrl);
    // if ($rawImage) {
    //     file_put_contents("img/" . $fileName, $rawImage);
    //     echo 'Image Saved';
    // } else {
    //     echo 'Error Occured';
    // }
    // This is allva div
    //*[@id="main-content"]/div/div[1]/div/div[2]/div[4]/div/div/a/img
}

// Start crawling from a specific URL
crawl("https://muaban.net/bat-dong-san/nha-mat-tien/cho-thue-tang-tret-tang-lung-lau-1-toa-nha-294-huynh-v-banh-gia-tl-id68704708", 2);
