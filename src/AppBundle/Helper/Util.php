<?php

namespace AppBundle\Helper;

abstract class Util
{
    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomPassword($length=6)
    {
        return str_pad(dechex(mt_rand(0, 0xFFFFFF)), $length, '0', STR_PAD_LEFT);
    }
}
