<?php
namespace App\Helpers;

use App\Models\Rent;

class IDHelper {

    public static function genId($id) {
        $totalDigit = 5;
        $zero = str_repeat('0', $totalDigit - strlen($id));
        $genId = $zero.$id;
        return $genId;    
    }

    public static function genNumberRent($rentId){
        $rent = Rent::where('rent_status', '!=', 'Draft')->find($rentId);
        if(empty($rent)){
            return "Error Number Rent";
        }

        $rentNumber = $rent->rent_number;
        $rentDate = $rent->rent_approved_at;
        $renterName = $rent->renter->renter_name;

        $month = date('m', strtotime($rentDate));
        $year = substr(date('Y', strtotime($rentDate)),2);
        $name = explode(" ", strtoupper($renterName))[0];

        if($month == 1){
            $month = 'I';
        }else if($month == 2){
            $month = 'II';
        }else if($month == 3){
            $month = 'III';
        }else if($month == 4){
            $month = 'IV';
        }else if($month == 5){
            $month = 'V';
        }else if($month == 6){
            $month = 'VI';
        }else if($month == 7){
            $month = 'VII';
        }else if($month == 8){
            $month = 'VIII';
        }else if($month == 9){
            $month = 'IX';
        }else if($month == 10){
            $month = 'X';
        }else if($month == 11){
            $month = 'XI';
        }else if($month == 12){
            $month = 'XII';
        }
        
        $totalDigit = 5;
        $zero = str_repeat('0', $totalDigit - strlen($rentNumber));
        $number = $zero.$rentNumber;

        return $number."/ISB/INVS/".$name.'/'.$month.'/'.$year;
    }
    
}