<?php
require_once 'get_info.php';
function crawl_estate($url, $page)
{
    // Initialize cURL
    // global $url;
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url); // Set the URL to crawl_tam_su
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($curl, CURLOPT_USERAGENT, 'MyBot'); // Set a user agent to identify your bot
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // Fix SSL certificate on win
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Fix SSL certificate on win

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
    processResponse_estate($response, $url);
    // getAllPages($response);
}


function processResponse_estate($response, $url)
{
    //Connect server
    include '../config.php';

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);
    libxml_clear_errors();
    $xpath = new DOMXpath($dom);
    if (strpos($url, 'century21')) {
        $link = $xpath->query('//*[@class="propertyTile "]/a');
        $titles = $xpath->query('//*[@class="title"]');
        $address = $xpath->query('//*[@class="streetaddress oneline"]');
        $bed = $xpath->query('//*[@class="icons"]/span[1]');
        $bath = $xpath->query('//*[@class="icons"]/span[2]');

        // Lặp qua các hàng (rows) trong bảng
        $get_name = [];
        $get_all_href = [];
        foreach ($link as $h) {
            $href = $h->getAttribute('href');
            $get_all_href[] = $href;
        }
        foreach ($titles as $t) {
            $value_name = trim(str_replace("\n", '', $t->nodeValue));
            $get_name[] = $value_name;
        }
        $get_address = [];
        foreach ($address as $a) {
            $value_address = $a->nodeValue;
            $get_address[] = $value_address;
        }
        $get_bed = [];
        foreach ($bed as $b) {
            $value_bed = $b->nodeValue;
            $get_bed[] = $value_bed;
        }
        $get_bath = [];
        foreach ($bath as $h) {
            $value_bath = $h->nodeValue;
            $get_bath[] = $value_bath;
        }
        $array = [];
        $array = [
            'name' => $get_name,
            'href' => $get_all_href,
            'address' => $get_address,
            'bed' => $get_bed,
            'bath' => $get_bath,
        ];
        for ($i = 0; $i < count($get_all_href); $i++) {
        // for ($i = 0; $i < 4; $i++) {
            crawl_info($get_all_href[$i], 2, $get_name[$i], $get_address[$i], $get_bed[$i], $get_bath[$i]);
        }
    } else {
        $link = 'https://muaban.net';
        $names = $xpath->query('//*[contains(@class, "kHcxnr")]/a');
        $infos = $xpath->query('//*[contains(@class, "kHcxnr")]/ul');
        $prices = $xpath->query('//*[contains(@class, "price")]');
        $locations = $xpath->query('//*[contains(@class, "kNhTeC")]');
        // var_dump($link);die;
        // Lặp qua các hàng (rows) trong bảng
        $get_name = [];
        $get_all_href = [];
        foreach ($names as $name) {
            $value_name = $name->nodeValue;
            $get_name[] = $value_name;
            $href = $name->getAttribute('href');
            $get_all_href[] = $link . $href;
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
        $array = [];
        $array = [
            'name' => $get_name,
            'href' => $get_all_href,
            'info' => $get_info,
            'price' => $get_price,
            'location' => $get_location,
        ];
        // Set the content type to JSON to get JSON value
        // header('Content-Type: application/json');
        // Output the array as JSON
        // echo json_encode($array);die;
        // count($get_all_href)
        for ($i = 0; $i < count($get_all_href); $i++) {
            crawl_info($get_all_href[$i], 2, $get_name[$i], $get_address = '', $get_bed = '', $get_bath = '');
        }
    }
}
