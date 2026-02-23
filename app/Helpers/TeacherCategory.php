<?php
namespace App\Helpers;
use Auth;

class TeacherCategory{

    public static function getTeacherCategory()
    {
        $data = array('Chairmen','President','Director','Head Master'
                        ,'Ast. Head Master','Teacher','Ast. Teacher','Staff');
        return $data;
    }
}
