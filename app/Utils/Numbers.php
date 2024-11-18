<?php

namespace App\Utils;

class Numbers
{
    public static function format($number, $decimal = 0)
    {
        if($number == 0)
        { return '-'; }
        return number_format($number, $decimal, ',', ' ');
    }
}
