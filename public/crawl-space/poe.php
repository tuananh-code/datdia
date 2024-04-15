<?php
$getAllUrls = [];

// error_reporting(0);
// var_dump($_POST);
function crawl($url, $page)
{
    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url); // Set the URL to crawl
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
    processResponse($response, $page);
    // getAllPages($response);
}

function processResponse($response)
{
    //Connect server
    include 'config.php';


    // Parse the HTML using a DOM parser library like DOMDocument or SimpleHTMLDom
    // Here's an example using DOMDocument

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);
    libxml_clear_errors();
    // var_dump($response);die;
    $xpath = new DOMXpath($dom);
    if (strpos($_POST['url'], 'alonhadat') !== false) {
        $link = 'https://alonhadat.com.vn';
        $pageAjax = intval($_POST['page']);
        $xPathAjax = $xpath->query($_POST['xPath']);
        $names = $xpath->query($_POST['xPath'] . '/div[1]/div[1]/a'); //*[@id="left"]/div[1]/div[1]/div[1]/div[1]/a
        $description = $xpath->query($_POST['xPath'] . '/div[4]/div[1]/text()'); //*[@id="left"]/div[1]/div[1]/div[4]/div[1]/text()
        $info_private = $xpath->query($_POST['xPath'] . '/div[4]/div[2]'); //*[@id="left"]/div[1]/div[1]/div[4]/div[2]
        $info_public = $xpath->query($_POST['xPath'] . '/div[4]/div[3]'); //*[@id="left"]/div[1]/div[1]/div[4]/div[3]/div[1]
        $price = $xpath->query($_POST['xPath'] . '/div[4]/div[4]/div[1]/text()'); //*[@id="left"]/div[1]/div[1]/div[4]/div[4]/div[1]/text()
        $location = $xpath->query($_POST['xPath'] . '/div[4]/div[4]/div[2]');

        $getValues = [];
        foreach ($xPathAjax as $dataAjax) {
            $value = $dataAjax->nodeValue;
            $getValues[] = $value;
            // echo $value . ' - ';
        };
        // Get name
        $get_names = [];
        $get_href = [];
        foreach ($names as $name) {
            $get_name = $name->nodeValue;
            $href = $link . $name->getAttribute('href');
            $get_names[] = $get_name;
            $get_href[] = $href;
        };
        // var_dump($get_names);
        // description
        // $descriptions = $description->item(0);
        // $get_description = $descriptions->nodeValue;

        $get_description = [];
        foreach ($description as $descriptions) {
            $value_description = $descriptions->nodeValue . ' ';
            $get_description[] = $value_description;
        };

        $get_info_private = [];
        foreach ($info_private as $infos) {
            $value_info = $infos->nodeValue;
            $get_info_private[] = $value_info;
        };
        $convert_private = implode('', $get_info_private);

        $get_info_public = [];
        foreach ($info_public as $infos_2) {
            $value_infos = $infos_2->nodeValue;
            $get_info_public[] = $value_infos;
        };
        $convert_public = implode('', $get_info_public);
        // $prices = $price->item(0);
        // $get_price = $prices->nodeValue;

        $get_price = [];
        foreach ($price as $prices) {
            $value_price = $prices->nodeValue;
            $trim = trim($value_price);
            $get_price[] = $trim;
        };
        // var_dump($get_price[8] == "  ");
        // $locations = $location->item(0);
        // $get_location = $locations->nodeValue;
        $get_location = [];
        foreach ($location as $locations) {
            $value_location = $locations->nodeValue . ', ';
            $get_location[] = $value_location;
        };
        // $convert_location = implode('', $get_location);
        $array = [];
        $array[] = [
            'all-description' => $getValues,
            'name' => $get_names,
            'description' => $get_description,
            'info_private' => $get_info_private,
            'info_public' => $get_info_public,
            'price' => $get_price,
            'location' => $get_location,
            'href' => $get_href
        ];
        // Set the content type to JSON to get JSON value
        header('Content-Type: application/json');
        // Output the array as JSON
        echo json_encode($array);
        // die;
    } else {
        $link = 'https://muaban.net';
        $names = $xpath->query('//*[contains(@class, "dGTvSk")]/a');
        $infos = $xpath->query('//*[contains(@class, "dGTvSk")]/ul');
        $prices = $xpath->query('//*[contains(@class, "price")]');
        $locations = $xpath->query('//*[contains(@class, "hYvkOS")]');
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
        header('Content-Type: application/json');
        // Output the array as JSON
        echo json_encode($array);
    }
}
// var_dump($getAllUrls);
// Start crawling from a specific URL

crawl($_POST['url'], 2);
// if(processResponse($response)){

// }
// getAllPages();
