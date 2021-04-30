<?php


namespace Goldcarrot\Base;


class Str extends \Illuminate\Support\Str
{
    public static function lfirst($string): string
    {
        return static::lower(static::substr($string, 0, 1)) . static::substr($string, 1);
    }
}