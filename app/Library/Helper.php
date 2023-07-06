<?php

namespace App\Library;

use Illuminate\Support\Str;

class Helper
{
    public static function getCurrentTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function getRandomText($length): string
    {
        return Str::random($length);
    }

    public static function IsNullOrEmptyString($question): bool
    {
        return (!isset($question) || trim($question) === '');
    }

    public static function khongdau($text)
    {
        $marTViet = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",

            "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",

            "ì", "í", "ị", "ỉ", "ĩ",

            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",

            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",

            "ỳ", "ý", "ỵ", "ỷ", "ỹ",

            "đ",

            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă"

        , "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",

            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",

            "Ì", "Í", "Ị", "Ỉ", "Ĩ",

            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ"

        , "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",

            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",

            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",

            "Đ");

        $marKoDau = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",

            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",

            "i", "i", "i", "i", "i",

            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",

            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",

            "y", "y", "y", "y", "y",

            "d",

            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A"

        , "A", "A", "A", "A", "A",

            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",

            "I", "I", "I", "I", "I",

            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O"

        , "O", "O", "O", "O", "O",

            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",

            "Y", "Y", "Y", "Y", "Y",

            "D");

        $str = str_replace($marTViet, $marKoDau, $text);

        return $str;
    }

    public static function getday($datecreate)
    {
        $thang = date("m", $datecreate);
        $ngay = date("d", $datecreate);
        $nam = date("Y", $datecreate);
        $jd = cal_to_jd(CAL_GREGORIAN, $thang, $ngay, $nam);
        $day = jddayofweek($jd, 0);
        switch ($day) {
            case 0:
                $thu = "Chủ Nhật";
                break;
            case 1:
                $thu = "Thứ Hai";
                break;
            case 2:
                $thu = "Thứ Ba";
                break;
            case 3:
                $thu = "Thứ Tư";
                break;
            case 4:
                $thu = "Thứ Năm";
                break;
            case 5:
                $thu = "Thứ Sáu";
                break;
            case 6:
                $thu = "Thứ Bảy";
                break;
        }
        return $thu;
    }

    public static function formatMoney($number, $decimals = 2, $decPoint = '', $thousandsSep = ',') {
        $negative = $number < 0 ? '-' : '';
        $number = abs($number);
        $integerPart = (int) $number;
        $decimalPart = ($decimals > 0) ? $decPoint . substr(abs($number - $integerPart), 2, $decimals) : '';

        $formattedNumber = $negative;
        $formattedNumber .= number_format($integerPart, 0, '', $thousandsSep);
        $formattedNumber .= $decimalPart;

        return $formattedNumber;
    }
}
