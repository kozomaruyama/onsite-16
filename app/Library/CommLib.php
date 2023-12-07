<?php

namespace app\Library;

use App\Models\Holyday;

class CommLib
{
    public static function tax($amount,$state) {
        if ($state==1) {
            return round(($amount * 10) / 110);
        } else if ($state==2) {
            return round($amount / 10);
        } else {
            return 0;
        }
    }
    public static function workDay($day,$state) {
        if ($state!=0) {
            $flag=true;
            do {
                if (date('w', strtotime($day))==0) {
                    $day = date('Y-m-d', strtotime($day . " $state day"));
                } else if (Holyday::where('day',$day)->exists()) {
                    $day = date('Y-m-d', strtotime($day . " $state day"));
                } else {
                    $flag=false;
                }
            }
            while ($flag);
        }
        return $day;
    }
}