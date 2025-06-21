<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\RentReturn;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
        // get last 6 rent data
        $rent = Rent::limit(6)->orderBy('rent_end_date', 'asc')->get();

        // get last 30 days rent data
        $rent30Days = Rent::where('rent_status', '!=', 'Draft')
            ->whereBetween('rent_start_date', [now()->subDays(30), now()])
            ->selectRaw('DATE(rent_start_date) as rent_start_date, COUNT(*) as total')
            ->groupBy('rent_start_date')
            ->orderBy('rent_start_date', 'asc')
            ->get();
        $rent30Days = $rent30Days->keyBy('rent_start_date');

        $label = [];
        $dataRent = [];
        foreach($rent30Days as $date => $rents){
            $label[] = date('d-m-Y', strtotime($date));
            $dataRent[] = $rents->total;
        }
        
        //get unpaid rent
        $unpaidRent = Rent::where('rent_status', '!=', 'Draft')
            ->where('rent_status_payment', 'Belum Bayar')
            ->count();

        //get rent return fine unpaid
        $unpaidFine = RentReturn::where('rent_return_receipt_status', 'Klaim Ganti Rugi')
            ->where('rent_return_payment_status', 'Belum Bayar')
            ->count();

        //get rent return deposit unpaid
        $unpaidDeposit = RentReturn::where('rent_return_receipt_status', 'Pengembalian Deposit')
            ->where('rent_return_payment_status', 'Belum Bayar')
            ->count();

        $data = [
            'rent' => $rent,
            'label' => $label,
            'data_rent' => $dataRent,
            'unpaid_rent' => $unpaidRent,
            'unpaid_fine' => $unpaidFine,
            'unpaid_deposit' => $unpaidDeposit,
        ];
        
        return view('dashboard.dashboard1', $data);
    }
}
