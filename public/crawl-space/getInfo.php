<?php
// include 'vendor/autoload.php';
include 'functionVn.php';
// include 'lat_long_convert.php';

// use Aws\S3\S3Client;
// use Aws\Exception\AwsException;

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
    processResponse($response);
    // getAllPages($response);
}

function processResponse($response)
{
    //Connect server
    include 'config.php';
    // Set that accept icon and wide special character
    // Set Mysql to accept like the top
    // ALTER TABLE bravo_real_estate MODIFY COLUMN content TEXT
    // CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    // Parse the HTML using a DOM parser library like DOMDocument or SimpleHTMLDom
    // Here's an example using DOMDocument

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);
    libxml_clear_errors();
    $xpath = new DOMXpath($dom);

    // Upload S3
    // $s3client = new S3Client([
    //     'version' => '2006-03-01',
    //     'region' => 'ap-southeast-1',
    //     'credentials' => [
    //         'key'    => 'AKIAVJH5OBNALLPJXXNB',
    //         'secret' => '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'
    //     ]
    // ]);
    // var_dump($_POST);
    // die;
    if (strpos($_POST['href'], 'alonhadat') !== false) {
        $link = 'https://alonhadat.com.vn';
        $href = $_POST['href'];
        $names = $_POST['infoName'];
        $prices = $xpath->query('//*[@class="price"]'); //
        $locations = $xpath->query('//*[@class="address"]'); //
        // Get all Text in every tag in HTML
        $more_info = $xpath->query('//*[contains(@class, "detail")]/p | //*[contains(@class, "detail")]/text() | //*[contains(@class, "detail")]/p/span | //*[contains(@class, "detail")]/ul/li/span');
        $info_private = $_POST['infoPrivate'];
        $info_public = $_POST['infoPublic'];
        // var_dump($_POST["infoPrivate"]);die;
        // Check img in every kind
        $img_info = $xpath->query($_POST['moreInfo'])->item(0); //*[@id="left"]/div[1]/div[2]
        $img_thumb = str_replace('2', '7', $_POST['moreInfo']);
        $img_check = $xpath->query('.//img', $img_info);
        foreach ($img_check as $check) {
            $all = $check->getAttribute('src');
        }
        // $img_check_all = $img_check->getAttribute('src');
        $img_info_thumb = $xpath->query($img_thumb)->item(0);

        if (@$all) {
            $get_img_info = $xpath->query('.//img', $img_info);
        } else {
            $get_img_info = $xpath->query('.//img', $img_info_thumb);
        }
        $details = $xpath->query($_POST['detail']);

        // $get_info_private = [];
        $get_all_description = [];
        foreach ($more_info as $get_info) {
            $values_info = $get_info->nodeValue;
            $trim = trim($values_info);
            $get_all_description[] = $trim;
        };

        $get_price = $prices->item(0)->nodeValue;
        $get_location = $locations->item(0)->nodeValue;

        $filtered_description = array_filter($get_all_description, function ($value) {
            return $value !== '';
        });
        $get_img = [];
        foreach ($get_img_info as $img_info_all) {
            $value_img = $img_info_all->getAttribute('src');
            $get_img[] = $link . $value_img;
        }

        if (strlen($info_private) > 5) {
            $convert_info_private = str_replace('m', 'm, ', $info_private);
            $convert_info_private_2 = str_replace('u', 'u, ', $convert_info_private);
            $convert_info_private_result = str_replace('chỗ', ', chỗ', $convert_info_private_2);
        } else {
            $convert_info_private_result = $info_private;
        }

        $array = [];
        $array[] = [
            'info_description' => $filtered_description,
            'info_name' => $names,
            'info_price' => $get_price,
            'info_location' => $get_location,
            'info_private' => $convert_info_private_result,
            'info_public' => $info_public,
            'info_href' => $href
        ];
        // Set the content type to JSON to get JSON value
        header('Content-Type: application/json');
        // Output the array as JSON
        echo json_encode($array[0]);
    } else if (strpos($_POST['href'], 'muaban') !== false) {
        // var_dump($_POST);die;
        $info_name = $_POST['infoName'];
        $prices = $xpath->query('//*[contains(@class, "price")]'); //*[@id="__next"]/div[2]/div[3]/div/div[1]/div[2]/div[2]/div[1]
        $locations = $xpath->query('//*[contains(@class, "address")]');
        $info_basic = $xpath->query('//*[@id="__next"]/div[2]/div[3]/div/div[1]/div[2]/div[6]/div[1]/ul/li');
        $phones = $xpath->query('//*[contains(@class, "phone-hidden")]');
        $all_img = $xpath->query('//*[contains(@class, "jeNeAh")]/img');
        // Get contact_name
        $user_name = $xpath->query('//*[@id="__NEXT_DATA__"]')->item(0)->nodeValue;
        $array = json_decode($user_name, true); // get Json value
        // header('Content-Type: application/json');
        $body = explode('<div', $array['props']['pageProps']['detail']['body']);
        $descriptions = '<p style="font-size:16px">' . $body[0] . '</p>';
        $contact_name = $array['props']['pageProps']['detail']['contact_name'];
        $all_img_slide = $array['props']['pageProps']['detail']['images'];
        $get_img = [];

        if (!empty($all_img_slide)) {
            for ($im = 0; $im < count($all_img_slide); $im++) {
                $value_slide = $all_img_slide[$im]['url'];
                $get_img[] = $value_slide;
            }
        } else {
            $all_img = $xpath->query('//*[contains(@class, "jeNeAh")]/img')->item(0);
            $get_img[] = $all_img->getAttribute('src');
            // var_dump($all_img->getAttribute('src'));die;
        }

        // var_dump($get_img[0]);
        // die;
        $get_price = $prices->item(0)->nodeValue;
        // Transfer word to number
        $number1 = 0;
        preg_match('/(\d+)\s*tỷ/', $get_price, $matches);
        if (isset($matches[1])) {
            $number1 = intval($matches[1]);
        }
        $number2 = 0;
        preg_match('/(\d+)\s*triệu/', $get_price, $matches);
        if (isset($matches[1])) {
            $number2 = intval($matches[1]);
        }
        // $replace = str_replace(' ', '', $get_price);
        // $replace2 = str_replace('tỷ', '', $replace);
        // $replace3 = str_replace('triệu', '', $replace2);
        $billion = strpos($get_price, 'tỷ');
        $million = strpos($get_price, 'triệu');
        if (($billion && $million) !== false) {
            // var_dump('true');
            $new1 = $number1 * 1000000000 + $number2 * 1000000;
        } else {
            if ($billion !== false) {
                $new1 = $number1 * 1000000000;
            } else {
                $new1 = $get_price;
            };
        };
        $get_number = intval(preg_replace('/[^0-9]/', '', $new1));

        // Get location and slug to push in SQL
        $get_location = $locations->item(0)->nodeValue;
        $location_last = explode(',', $get_location);
        $location_city = trim(end($location_last));
        $location_slug_check = vn_to_str(mb_strtolower($location_city));
        // var_dump($location_slug_check);die;
        if ($location_slug_check == 'tphcm') {
            $location_slug = 'ho-chi-minh';
        } else {
            $location_slug = str_replace('--', '-', $location_slug_check);
        }
        $convert_description = str_replace("'", "''", $descriptions);
        // var_dump($get_description);die;
        $get_info = [];
        foreach ($info_basic as $info) {
            $values_info = $info->nodeValue;
            $get_info[] = $values_info;
        }

        $bedroom = null;
        $bathroom = null;
        $square = null;
        $floors = null;
        foreach ($get_info as $item) {
            if (strpos($item, "Số phòng vệ sinh:") === 0) {
                $bathroom = (int) explode(":", $item)[1];
            } elseif (strpos($item, "Số phòng ngủ:") === 0) {
                $bedroom = (int) explode(":", $item)[1];
            } elseif (strpos($item, "Diện tích đất:") === 0) {
                $square = explode(":", $item)[1];
            } elseif (strpos($item, "Diện tích sử dụng:") === 0) {
                $square = explode(":", $item)[1];
            } elseif (strpos($item, "Tổng số tầng:") === 0) {
                $floors = explode(":", $item)[1];
            }
        }
        $pattern = '/^([\d,\.]+)\s*m²/';
        preg_match($pattern, $square, $matches);
        $get_square_raw = isset($matches[1]) ? $matches[1] : null;
        $get_square = intval($get_square_raw);
        // var_dump(intval($get_square));die;
        if ($bathroom == null) {
            $get_bathroom = 0;
        } else {
            $get_bathroom = $bathroom;
        }
        if ($bedroom == null) {
            $get_bedroom = 0;
        } else {
            $get_bedroom = $bedroom;
        }
        if ($floors == null) {
            $get_floor = 0;
        } else {
            $get_floor = $floors;
        }
        $get_phone_raw = [];
        foreach ($phones as $phone) {
            $values_phone = $phone->getAttribute('data-phone');
            $get_phone_raw[] = $values_phone;
        }
        $get_phone = $get_phone_raw[0];
        // var_dump($get_phone);
        // die;
        // $get_img = [];
        // foreach ($all_img as $img) {
        //     $value_img = $img->getAttribute('src');
        //     $get_img[] = $value_img;
        // }
        // var_dump(now());die;
        $slug = vn_to_str(mb_strtolower(trim($info_name)));
        $convert_slug = str_replace('--', '-', $slug);

        $array = [];
        $array = [
            'name' => $info_name,
            'price' => $get_price,
            'location' => $get_location,
            'phone' => $get_phone,
            'img' => $get_img[0],
            'description' => $convert_description,
            'info' => $get_info
        ];
        // Set the content type to JSON to get JSON value
        header('Content-Type: application/json');
        // // Output the array as JSON
        echo json_encode($array);
        // die;
    } else if (strpos($_POST['href'], 'century21') !== false) {
        $info_name = str_replace("'", "''", $_POST['infoName']);
        $slug = vn_to_str(mb_strtolower(trim(str_replace("'", "''", $info_name))));
        $convert_slug = str_replace('--', '-', $slug);
        $get_location = $_POST['infoAddress'] . ', Australia';
        $get_bedroom = $_POST['infoBed'];
        $get_bathroom = $_POST['infoBath'];
        //price
        $prices = $xpath->query('//*[@class="pricetext"]')->item(0)->nodeValue;
        $contents = $xpath->query('//*[@class="contentRegion"]/p');
        $contact = $xpath->query('//*[@class="contact-mobile"]')->item(0)->nodeValue;
        $contact_name = $xpath->query('//*[@class="name"]')->item(0)->nodeValue;
        $squares = $xpath->query('//*[@class="kv-list stroked"]/li/span[2]')->item(0)->nodeValue;
        $get_square = get_square($squares);

        $image = $xpath->query('//*[@id="slideshow"]/div/span/link');
        $get_numbers = get_number($prices);

        if ($get_numbers[0]) {
            //content
            foreach ($contents as $c) {
                $content[] = str_replace("'", "''", $c->nodeValue);
            }
            $convert_description = '<p style="font-size:16px">' . implode("<br>", $content) . '</p>';

            //img
            foreach ($image as $i) {
                $get_img[] = $i->getAttribute('href');
            }
            $get_number = $get_numbers[0];
            $get_phone = $contact;
            if (!is_numeric($get_square)) {
                $get_square = 0;
            }
            if (!is_numeric($get_bedroom)) {
                $get_bedroom = 0;
            }
            if (!is_numeric($get_bathroom)) {
                $get_bathroom = 0;
            }
            $get_floor = 0;
            $array = [];
            $array = [
                'name' => $info_name,
                'price' => $get_price,
                'location' => $get_location,
                'phone' => $get_phone,
                'img' => $get_img[0],
                'description' => $convert_description,
                'info' => $get_info
            ];
            // Set the content type to JSON to get JSON value
            header('Content-Type: application/json');
            // // Output the array as JSON
            echo json_encode($array);
        } else {
            $array = null;
            echo $array;
        }
    };

    if ($array) {
        // Download img to file 
        $get_row_img = [];
        for ($slide = 0; $slide < count($get_img); $slide++) {

            // Upload img to Aws3
            $imageUrl = $get_img[$slide];
            $file_name_raw = basename($imageUrl);
            $file_name = pathinfo($file_name_raw, PATHINFO_FILENAME);
            // var_dump($file_name);die;
            // $file_path = 'real_estate/' . $file_name_raw;
            $extension = pathinfo($file_name_raw, PATHINFO_EXTENSION);

            // While Aws3 require PHP >= 8.. ->install in Terminal: composer install --ignore-platform-reqs
            // Download the image and save it to a temporary file, Create temp file by php
            // $temp_path = tempnam(sys_get_temp_dir(), 'img');
            // file_put_contents($temp_path, file_get_contents($imageUrl));
            // try {
            //     $s3client->putObject([
            //         'Bucket' => 'datdia',
            //         'Key' => $file_path,
            //         'SourceFile' => $temp_path,
            //         'ACL'    => 'public-read', // make file 'public', Set ACL to read
            //     ]);
            //     // While Aws3 require PHP >= 8.. ->install in Terminal: composer install --ignore-platform-reqs
            //     echo "Uploaded $file_name to $bucket_name.\n";
            //     echo $file_Path;
            //     echo "https://s3.ap-southeast-1.amazonaws.com/datdia/$file_path";
            //     $link = "https://s3.ap-southeast-1.amazonaws.com/datdia/$file_path";
            //     // Check exist
            $select_img = "SELECT * FROM media_files WHERE file_name = '$file_name'";
            $result_img = mysqli_query($conn, $select_img);
            $row_img = mysqli_fetch_assoc($result_img);
            if ($row_img) {
            } else {
                $sql_media = "INSERT INTO media_files(file_name, file_path, file_size, file_type, file_extension) VALUES ('$file_name', '$imageUrl', '', 'image/$extension', '$extension')";
                //Add data in MySQL
                if (mysqli_query($conn, $sql_media)) {
                    // echo "Records added successfully." . '<br>';
                    // echo "https://s3.ap-southeast-1.amazonaws.com/datdia/$file_path" . '//';
                } else {
                    echo "ERROR: Could not able to execute $sql_media. " . mysqli_error($conn);
                }
                //Close connection
            }
            // } catch (Exception $exception) {
            //     echo "Failed to upload $file_name with error: " . $exception->getMessage();
            //     exit("Please fix error with file upload before continuing.");
            // }

            $sql_image = "SELECT id FROM `media_files` WHERE file_name = '$file_name'";
            $result_image = mysqli_query($conn, $sql_image);
            $row_image = mysqli_fetch_assoc($result_image);
            $image_id = $row_image['id'];
            $get_row_img[] = $image_id;
        };
        if (count($get_row_img) < 2) {
            $convert_row_img = $get_row_img[0];
        } else {
            $convert_row_img = implode(',', array_reverse($get_row_img));
        }
        // var_dump($convert_row_img);die;
        $banner_img = $get_row_img[0];
        // unlink($temp_path);
        if (strpos($_POST['href'], 'century21') !== false) {
            $sql_location = "SELECT * FROM `bravo_locations` WHERE `name` = 'Australia'";
            $result_location = mysqli_query($conn, $sql_location);
            $row_location = mysqli_fetch_assoc($result_location);
            $location_id = $row_location['id'];
        } else {
            $sql_location = "SELECT * FROM `bravo_locations` WHERE `name` = '$location_city'";
            $result_location = mysqli_query($conn, $sql_location);
            $row_location = mysqli_fetch_assoc($result_location);
            // Get id-autoIncrement location
            $status = "SHOW TABLE STATUS WHERE Name = 'bravo_locations'"; //
            $id = $conn->query($status); //
            // Get the ID:'auto-increment' value to add in relationship table
            $row_id = $id->fetch_assoc(); //
            $id_auto = $row_id['Auto_increment'];
            if ($row_location) {
                // echo 'Location exist';
            } else {
                $sql_located = "INSERT INTO bravo_locations (name, content, slug, image_id, map_lat, map_lng, map_zoom, _lft, _rgt, status) VALUES ('$location_city', '', '$location_slug', 121, '', '', 12, ($id_auto * 2) - 1, $id_auto * 2, 'publish')";
                if (mysqli_query($conn, $sql_located)) {
                    // echo "Location Added";
                } else {
                    echo "Error: $sql_located " . mysqli_error($conn);
                }
            }
            $location_id = $row_location['id'];
        }
        $sql_check_space = "SELECT * FROM `bravo_spaces` WHERE title = '$info_name'";
        $result_check_space = mysqli_query($conn, $sql_check_space);
        $row_check_space = mysqli_fetch_assoc($result_check_space);

        //Select id banner image
        $sql_banner = "SELECT id FROM `media_files` WHERE file_name = 'banner-trang-chu'";
        $result_banner = mysqli_query($conn, $sql_banner);
        $row_banner = mysqli_fetch_assoc($result_banner);
        $banner_id = $row_banner['id'];

        if ($row_check_space) {
            // echo 'Estate Exist';
        } else {
            $post_date = date('Y-m-d H:m:s');
            $sql_space = "INSERT INTO bravo_spaces (title, slug, content, image_id, banner_image_id, location_id, address, map_lat, map_lng, map_zoom, gallery, price, bed, bathroom, square, max_guests, contact, contact_name, created_at, updated_at, status) VALUES ('$info_name', '$convert_slug', '$convert_description', '$banner_img', '$banner_id', '$location_id', '$get_location', '', '', 12, '$convert_row_img', '$get_number', '$get_bedroom', '$get_bathroom', '$get_square', '$get_floor', '$get_phone', '$contact_name', '$post_date', '$post_date', 'publish')";
            // var_dump($sql_space);die;
            if (mysqli_query($conn, $sql_space)) {
                // echo "Real_estate Added";
            } else {
                echo "Error: $sql_space " . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
}
// var_dump($getAllUrls);
// Start crawling from a specific URL

crawl($_POST['href'], 2);
