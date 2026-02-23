<?php
namespace App\Helpers;

class VisibilityType{

    public static function getVisibilityTypeData()
    {
        $data = array('Published','Un Published','Save As Draft');
        return $data;
    }
}