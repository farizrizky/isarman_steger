<?php
namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
Class DateHelper{

    public static function dateFormat($date){
        return date('d-m-Y', strtotime($date));
    }

    public static function fullDateFormat($date){
        App::setLocale('id');
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        return $carbon->translatedFormat('l, d F Y') . ' Pukul ' . $carbon->format('H:i');
    }

    public static function fullDateFormatWithoutTime($date){
        App::setLocale('id');
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        return $carbon->translatedFormat('l, d F Y');
    }

    public static function dateTimeFormat($date){
        return date('d-m-Y H:i:s', strtotime($date));
    }

    public static function timeFormat($date){
        return date('H:i:s', strtotime($date));
    }

    public static function monthName($date){
        $month = date('F', strtotime($date));
        if($month == 'January'){
            return 'Januari';
        }else if($month == 'February'){
            return 'Februari';
        }else if($month == 'March'){
            return 'Maret';
        }else if($month == 'April'){
            return 'April';
        }else if($month == 'May'){
            return 'Mei';
        }else if($month == 'June'){
            return 'Juni';
        }else if($month == 'July'){
            return 'Juli';
        }else if($month == 'August'){
            return 'Agustus';
        }else if($month == 'September'){
            return 'September';
        }else if($month == 'October'){
            return 'Oktober';
        }else if($month == 'November'){
            return 'November';
        }else if($month == 'December'){
            return 'Desember';
        }
    }

    public static function dayName($date){
        $day = date('l', strtotime($date));
        if($day == 'Sunday'){
            return 'Minggu';
        }else if($day == 'Monday'){
            return 'Senin';
        }else if($day == 'Tuesday'){
            return 'Selasa';
        }else if($day == 'Wednesday'){
            return 'Rabu';
        }else if($day == 'Thursday'){
            return 'Kamis';
        }else if($day == 'Friday'){
            return 'Jumat';
        }else if($day == 'Saturday'){
            return 'Sabtu';
        }
    }

    public static function daysDiffFromNow($dateCompare){
        $dateNow = date('Y-m-d H:i:s');
        $dateCompare = date('Y-m-d H:i:s', strtotime($dateCompare));
        $dateNow = strtotime($dateNow);
        $dateCompare = strtotime($dateCompare);
        $diff = $dateCompare - $dateNow;
        $days = floor($diff / (60 * 60 * 24));

        return $days+1;
    }
    
}