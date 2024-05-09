<?php
$getAllUrls = [];
include_once 'functionVn.php';
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
    } elseif (strpos($_POST['url'], 'muaban.net') !== false) {
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
    } elseif (strpos($_POST['url'], 'century21') !== false) {
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
        // Set the content type to JSON to get JSON value
        header('Content-Type: application/json');
        // Output the array as JSON
        echo json_encode($array);
    } elseif (strpos($_POST['url'], 'remax.com') !== false) {
        $link = $xpath->query('//*[@class="result-item-details"]/div/h2/a');
        $price = $xpath->query('//*[@class="result-price margin-bottom-10"]');
        $stats = $xpath->query('//*[@class="list-unstyled margin-0 result-basics-grid"]');

        $get_name = [];
        $get_all_href = [];
        $get_address = [];
        $price = [];
        foreach ($link as $h) {
            $href = $h->getAttribute('href');
            $get_all_href[] = $href;
        }
        foreach ($titles as $t) {
            $value_name = trim(str_replace("\n", '', $t->nodeValue));
            $get_name[] = $value_name;
            $get_address[] = $value_name;
        }
        foreach ($prices as $p) {
            $price = trim(str_replace("\n", '', $p->nodeValue));
            $get_price[] = $price;
        }
        foreach ($stats as $s) {
            $stat = explode('Beds', $s->nodeValue);
            $stat2 = explode('Baths', $stat[1]);
            $bed[] = $stat[0];
            $bath[] = $stat2[0];
            $sqm[] = $stat2[1];
        }
    } elseif (strpos($_POST['url'], 'estately.com') !== false) {
        $link = $xpath->query('//*[@class="result-item-details"]/div/h2/a');
        $prices = $xpath->query('//*[@class="result-price margin-bottom-10"]');
        $stats = $xpath->query('//*[@class="list-unstyled margin-0 result-basics-grid"]');
        $get_name = [];
        $get_all_href = [];
        $get_address = [];
        $price = [];
        foreach ($link as $h) {
            $href = $h->getAttribute('href');
            $get_all_href[] = $href;
            $value_name = trim(str_replace("\n", '', $h->nodeValue));
            $get_name[] = $value_name;
            $get_address[] = $value_name;
        }

        foreach ($prices as $p) {
            // $price = get_price($p->nodeValue);
            $get_price[] = get_price($p->nodeValue);
        }
        foreach ($stats as $s) {
            $stat = explode('beds', $s->nodeValue);
            $get_bed[] = trim($stat[0]);
            $stat2 = explode('baths', $stat[1]);
            $get_bath[] = trim($stat2[0]);
            $sqm[] = trim(str_replace(',', '', get_square($stat2[1])[0]));
        }
        $array = [];
        $array = [
            'name' => $get_name,
            'href' => $get_all_href,
            'address' => $get_address,
            'price' => $get_price,
            'bed' => $get_bed,
            'bath' => $get_bath,
            'sqm' => $sqm
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
