<?php
function vn_to_str($str)
{

    $unicode = array(

        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

        'd' => 'đ',

        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

        'i' => 'í|ì|ỉ|ĩ|ị',

        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

        'D' => 'Đ',

        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

    );

    foreach ($unicode as $nonUnicode => $uni) {

        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $pattern = '/[^a-zA-Z0-9\s]+/';
    $string = preg_replace($pattern, '', $str);

    $str = str_replace(' ', '-', $string);

    return $str;
}

function get_number($string)
{
    $pattern = '/\$[\d,]+/'; // Regular expression pattern to match numbers preceded by a dollar sign
    preg_match_all($pattern, $string, $matches);

    // Extract matched numbers and remove commas
    $numbers = array_map(function ($match) {
        return (int)str_replace(['$', ','], '', $match);
    }, $matches[0]);
    return $numbers;
}

function get_price($string)
{
    $number = preg_replace('/[^0-9]/', '', $string);
    return $number;  // Output: 849000
}
function get_square($string)
{
    preg_match_all('/\b\d{1,3}(?:,\d{3})*(?:\.\d+)?\b/', $string, $matches);
    $numbers = $matches[0];
    return $numbers;
}

function get_phone($string)
{
    $cleaned_number = preg_replace('/\D/', '', $string);
    return $cleaned_number;
}