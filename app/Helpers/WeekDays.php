<?php
namespace App\Helpers;
use Auth;

class WeekDays{

    public static function getWeekDays()
    {
        $sevenDays = array('Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday');

        return $sevenDays;
    }
}