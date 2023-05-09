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
    }}
