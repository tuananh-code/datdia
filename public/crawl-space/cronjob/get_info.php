<?php
include '../vendor/autoload.php';
include '../functionVn.php';
include_once 'lat_long_convert.php';

// use BackblazeB2\Client;
// use BackblazeB2\Bucket\Bucket;
// use BackblazeB2\Object\File;

function crawl_info($url, $page, $get_name)
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
    // var_dump($url);
    // Process the response
    processResponse_info($response, $url, $get_name);
    // getAllPages($response);
}

function processResponse_info($response, $url, $get_name)
{
    include '../config.php';
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);
    libxml_clear_errors();
    $xpath = new DOMXpath($dom);


    // var_dump($s3client);
    // die;
    $info_name = $get_name;
    // var_dump($info_name);
    $prices = $xpath->query('//*[contains(@class, "price")]'); //*[@id="__next"]/div[2]/div[3]/div/div[1]/div[2]/div[2]/div[1]
    $locations = $xpath->query('//*[contains(@class, "address")]');
    $descriptions = $xpath->query('//*[contains(@class, "fGkbZy")]/text()');
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
    // die;
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
        'img' => $get_img,
        'reverse' => array_reverse($get_img),
        'description' => $convert_description,
        'info' => $get_info
    ];
    // Set the content type to JSON to get JSON value
    //header('Content-Type: application/json');
    // Output the array as JSON
    //echo json_encode($array);die;

    $sql_exist = "SELECT * FROM `bravo_spaces` WHERE title = '$info_name'";
    $result_exist = $conn->query($sql_exist);
    @$row_exist = mysqli_fetch_array($result_exist);
    if (!@$row_exist) {
        if ($array) {
            // Download img to file 
            // $get_row_img = [];
            // $key_id = 'a6ad8ef03fd8';
            // $app_key = '005702a65a8350c9161dab30d79faec4bb8e58a4ae';
            // $bucket_name = 'servertoidayhoc';

            // $client = new Client($key_id, $app_key);
            for ($slide = 0; $slide < count($get_img); $slide++) {

                // Upload img to Aws3
                // $imageUrl = $get_img[$slide];
                // $file_name_raw = basename($imageUrl);
                // $file_name = pathinfo($file_name_raw, PATHINFO_FILENAME);
                // // var_dump($file_name);die;
                // $file_path = 'real_estate/' . $file_name_raw;
                // $extension = pathinfo($file_name_raw, PATHINFO_EXTENSION);

                // // While Aws3 require PHP >= 8.. ->install in Terminal: composer install --ignore-platform-reqs
                // // Download the image and save it to a temporary file, Create temp file by php
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
                //     // Check exist
                //     $select_img = "SELECT * FROM media_files WHERE file_name = '$file_name'";
                //     $result_img = mysqli_query($conn, $select_img);
                //     $row_img = mysqli_fetch_assoc($result_img);
                //     if ($row_img) {
                //         // echo 'img exist' . ', ';
                //     } else {
                //         $sql_media = "INSERT INTO media_files(file_name, file_path, file_size, file_type, file_extension) VALUES ('$file_name', 'https://s3.ap-southeast-1.amazonaws.com/datdia/$file_path', '', 'image/$extension', '$extension')";
                //         //Add data in MySQL
                //         if (mysqli_query($conn, $sql_media)) {
                //             // echo "Records added successfully." . '<br>';
                //             // echo "https://s3.ap-southeast-1.amazonaws.com/datdia/$file_path" . '//';
                //         } else {
                //             echo "ERROR: Could not able to execute $sql_media. " . mysqli_error($conn);
                //         }
                //         //Close connection
                //     }
                // } catch (Exception $exception) {
                //     echo "Failed to upload $file_name with error: " . $exception->getMessage();
                //     exit("Please fix error with file upload before continuing.");
                // }

                // $sql_image = "SELECT id FROM `media_files` WHERE file_name = '$file_name'";
                // $result_image = mysqli_query($conn, $sql_image);
                // $row_image = mysqli_fetch_assoc($result_image);
                // $image_id = $row_image['id'];
                // $get_row_img[] = $image_id;


                // Upload B2
                $imageUrl = $get_img[$slide];
                $file_name_raw = basename($imageUrl);
                $file_name = pathinfo($file_name_raw, PATHINFO_FILENAME);
                // var_dump($file_name);die;
                $file_path = 'datdia.com/' . $file_name_raw;
                $extension = pathinfo($file_name_raw, PATHINFO_EXTENSION);

                // While Aws3 require PHP >= 8.. ->install in Terminal: composer install --ignore-platform-reqs
                // Download the image and save it to a temporary file, Create temp file by php
                // $temp_path = tempnam(sys_get_temp_dir(), 'img');
                // $get_file = file_get_contents($imageUrl);
                // file_put_contents($temp_path, file_get_contents($imageUrl));

                // $existing_file = $client->fileExists([
                //     'BucketName' => $bucket_name,
                //     'FileName' => $file_name,
                // ]);

                // if (!$existing_file) {
                // try {
                //     $file_upload = $client->upload([
                //         'BucketName' => $bucket_name,
                //         'FileName' => $file_path,
                //         'Body' => $get_file,
                //     ]);
                // if ($file_upload) {
                // $public_url = 'https://f005.backblazeb2.com/file/' . $bucket_name . '/' . $file_path;
                $select_img = "SELECT * FROM media_files WHERE file_name = '$file_name'";
                $result_img = mysqli_query($conn, $select_img);
                $row_img = mysqli_fetch_assoc($result_img);
                if ($row_img) {
                    // echo 'img exist' . ', ';
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
                // }
                // } catch (Exception $e) {
                //     // Handle any errors that occurred during the upload process
                //     echo 'Error uploading image: ' . $e->getMessage();
                // };
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
            $banner_img = $get_row_img[0];

            //   unlink($temp_path);

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
                $get_phone = str_replace(' ', '', $get_phone);
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
        lat_long_convert($get_location, true);
    } else {
        echo 'exist';
        lat_long_convert($get_location, false);
    }
}
