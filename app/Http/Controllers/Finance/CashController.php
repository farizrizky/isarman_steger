<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use Illuminate\Http\Request;

class CashController extends Controller
{
    public function initialBalanceCash(){
        $cash = Cash::get();
        if($cash->count() > 0){
            $data = [
                'cash_initial_balance' => $cash->first()->cash_initial_balance,
                'cash_balance' => $cash->first()->cash_balance
            ];
        } else {
             $data = [
                'cash_initial_balance' => null,
                'cash_balance' => null,
            ];
        }
        return view('finance.cash-initial-balance', $data);
    }

    public function saveIntialBalanceCash(Request $request){
        $cash = Cash::count();
        if($cash > 0){
            $cashData = Cash::first();
            $cashData->update([
                'cash_initial_balance' => $request->cash_initial_balance,
            ]);
            $sweetalert =  [
                'state' => "success",
                'title' => "Berhasil",
                'message' => "Kas awal berhasil diubah",
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        Cash::create([
            'cash_initial_balance' => $request->cash_initial_balance,
            'cash_balance' => $request->cash_initial_balance,
        ]);

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Kas awal berhasil dibuat",
        ];
        return back()->with('sweetalert', $sweetalert);
    }

    public static function getCashInfo($type){
        $cash = Cash::get();
        $cashBalance = 0;
        if($cash->count() > 0){
            $amount = $cash->first()->toArray()['cash_'.$type];
        } else {
           $amount = null;
        }
        return $amount;
    }

    public static function updateCash($type, $amount){
        $cash = Cash::get();
        if($cash->count() > 0){
            if($type == 'balance'){
                $cashData = $cash->first();
                $cashData->update([
                    'cash_balance' => $amount,
                ]);
            }else if($type == 'income_total'){
                $cashData = $cash->first();
                $cashData->update([
                    'cash_income_total' => $amount,
                ]);
            }else if($type == 'expense_total'){
                $cashData = $cash->first();
                $cashData->update([
                    'cash_expense_total' => $amount,
                ]);
            }else{
                return false;
            }
            return $cashData->cash_balance;
        } else {
           return null;
        }
    }
}
