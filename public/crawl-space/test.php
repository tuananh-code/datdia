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
    $prices = $xpath->query('//*[@class="pricetext"]')->item(0)->nodeValue;
    $contents = $xpath->query('//*[@class="contentRegion"]/p');
    $contact = $xpath->query('//*[@class="contact-mobile"]')->item(0)->nodeValue;
    $mail = $xpath->query('//*[@class="contact-email"]')->item(0)->nodeValue;
    $contact_name = $xpath->query('//*[@class="name"]')->item(0)->nodeValue;
    $squares = $xpath->query('//*[@class="kv-list stroked"]/li/span[2]')->item(0)->nodeValue;
    $image = $xpath->query('//*[@id="slideshow"]/div/span/link');
    // $link = $xpath->query('//*[@class="propertyTile "]/a');
    // $titles = $xpath->query('//*[@class="title"]');
    // $address = $xpath->query('//*[@class="streetaddress oneline"]');
    // $bed = $xpath->query('//*[@class="icons"]/span[1]');
    // $bath = $xpath->query('//*[@class="icons"]/span[2]');
    // $link = $xpath->query('//*[@id="swipeable"]/div/a');
    //*[@id="swipeable"]/div/a
    var_dump($mail);
    die;
    foreach ($image as $c) {
        $img = $c->getAttribute('href');
        echo "<img src='$img'>";
    }
    $price = get_number($prices);
    $square = get_square($squares);

    foreach ($link as $h) {
        $href[] = $h->getAttribute('href');
    }
    foreach ($titles as $t) {
        $title[] = $t->nodeValue;
    }
    foreach ($prices as $p) {
        $price[] = $p->nodeValue;
        // $check = get_number($p->nodeValue);
        // if ($check[0]) {
        //     $price[] = $check[0];
        // }
    }
    foreach ($address as $d) {
        $add[] = $d->nodeValue;
    }
    foreach ($bed as $b) {
        $info_bed[] = $b->nodeValue;
    }
    foreach ($bath as $ba) {
        $info_bath[] = $ba->nodeValue;
    }
}

// Start crawling from a specific URL
crawl("https://www.century21.com.au/property/residential/buy/qld/4032/chermside/578874", 2);
// crawl("https://www.zillow.com/new-york-ny/", 2);
