<?php
namespace App\Helpers;

use App\Models\Cash;
use App\Models\Rent;

Class DataHelper{
    public static function getRentTotal($status=null){
        if(is_null($status)){
            return Rent::count();
        } else {
            return Rent::where('rent_status', $status)->count();
        }
    }

    public static function getDataRent($rentId){
        $rent = Rent::with('renter')->where('rent_id', $rentId)->first();
        if(is_null($rent)){
            return null;
        }
        return $rent;
    }

    public static function getRentExtend($rentId){
        $rent = Rent::where('rent_id_extend', $rentId)->first();
        if(is_null($rent)){
            return null;
        }

        return [
            'rent_id' => $rent->rent_id,
            'rent_status' => $rent->rent_status,
        ];
    }

    public static function getCashBalance(){
        $cash = Cash::get();
        $cashBalance = 0;
        if($cash->count() > 0){
            $cashBalance = $cash->first()->cash_balance;
        } else {
            $cashBalance = 0;
        }
        return $cashBalance;
    }
    
}