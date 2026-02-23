<?php
namespace App\Helpers;
use App\Models\User;
use App\Models\Classname;
use Auth;

class GetAllClass{

    //To get all the class data...
    public static function getAllClassInfo()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the class data...
        $classData = Classname::orderBy('id', 'desc')->where('user_id', $userId)->get();

        return $classData;
    }
}