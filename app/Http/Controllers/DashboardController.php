<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\RentReturn;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
        // get last 6 rent data
        $rent = Rent::where('rent_status', 'Berjalan')->limit(6)->orderBy('rent_end_date', 'asc')->get();

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
        $unpaidRent = Rent::selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(rent_total_payment) as total_payment')
            ->where('rent_status', '!=', 'Draft')
            ->where('rent_status_payment', 'Belum Bayar')
            ->first();

        //get rent return fine unpaid
        $unpaidFine = RentReturn::selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(rent_return_total_payment) as total_fine')
            ->where('rent_return_receipt_status', 'Klaim Ganti Rugi')
            ->where('rent_return_payment_status', 'Belum Bayar')
            ->first();

        //get rent return deposit unpaid
        $unpaidDeposit = RentReturn::selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(rent_return_total_payment) as total_deposit')
            ->where('rent_return_receipt_status', 'Pengembalian Deposit')
            ->where('rent_return_payment_status', 'Belum Bayar')
            ->first();

        //get most renter
        $mostRenter = Rent::with('renter')
            ->selectRaw('renter_id, COUNT(*) as total')
            ->where('rent_status', 'Berjalan')
            ->groupBy('renter_id')
            ->orderBy('total', 'desc')
            ->take(6);
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
