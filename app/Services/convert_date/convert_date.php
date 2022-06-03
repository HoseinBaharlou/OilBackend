<?php

namespace App\Services\convert_date;
use Hekmatinasser\Verta\Verta;

class convert_date{
    public static function jalali($dateTime){
        $date = explode(' ',$dateTime);
        $explodeDate = explode('-',$date[0]);
        $changeToJalali = Verta::getJalali($explodeDate[0],$explodeDate[1],$explodeDate[2]);
        $changeToJalali[1] = $changeToJalali[1] <= 9 ? '0'.$changeToJalali[1] : $changeToJalali[1];
        $changeToJalali[2] = $changeToJalali[2] <= 9 ? '0'.$changeToJalali[2] : $changeToJalali[2];

        return implode('-',$changeToJalali).' '.$date[1];
    }
}
