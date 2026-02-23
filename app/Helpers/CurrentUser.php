<?php
namespace App\Helpers;
use App\Models\User;
use Auth;

class CurrentUser{

    public static function getUserId()
    {
        if(isset(Auth::user()->id)){
            if(Auth::user()->role == 'superadmin'){
                $userId = Auth::user()->id;
            }else{
                $userId = Auth::user()->admin_id;
            }
        }else{
            $userId = null;
        }

        return $userId;
    }

    public static function getUserIdFCU()
    {
        return Auth::user()->id;
    }

    public static function getSuperadminId()
    {
        return User::where('role', 'superadmin')->value('id');
    }

    public static function getOwnerId()
    {
        if(isset(Auth::user()->id)){
            if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
                $userId = Auth::user()->id;
            }else{
                $userId = Auth::user()->admin_id;
            }
        }else{
            $userId = null;
        }

        return $userId;
    }
}