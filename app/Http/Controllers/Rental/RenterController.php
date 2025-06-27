<?php

namespace App\Http\Controllers\Rental;
use App\Http\Controllers\Controller;

use App\Models\Renter;
use App\Models\RenterDeposit;
use App\Models\Stock;
use Illuminate\Http\Request;

class RenterController extends Controller
{
    
    public function renter(){
        $renter = Renter::with('rent')->orderBy('created_at', 'desc')->get();
        $data = [
            'renter' => $renter,
        ];
        return view('rental.renter-list', $data);
    }

    public function createFormRenter(){
        return view('rental.renter-create-form');
    }

    public function updateFormRenter($id){
        $renter = Renter::find($id);
        if(is_null($renter)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewa tidak ditemukan",
            ];
            return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
        }

        $data = [
            'renter' => $renter,
        ];
        return view('rental.renter-update-form', $data);
    }

    public function createRenter(Request $request){
        $renter = Renter::create($request->all());
        $renterId = $renter->renter_id;
        $pathFile = $request->file('renter_identity_file');
        $fileExtension = $pathFile->getClientOriginalExtension();
        $fileName = 'identity_'.$renterId.'.'.$fileExtension;
        $pathFile = $pathFile->storeAs('renter', $fileName, 'public');
        Renter::find($renterId)->update([
            'renter_identity_photo' => $pathFile,
        ]);

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Penyewa berhasil dibuat",
        ];
        return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
    }

    public function updateRenter(Request $request, $id){
        $renter = Renter::find($id);
        if(is_null($renter)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewa tidak ditemukan",
            ];
            return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
        }

        $renter->update($request->all());
        if($request->hasFile('renter_identity_file')){
            $pathFile = $request->file('renter_identity_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'identity_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('renter', $fileName, 'public');
            Renter::find($id)->update([
                'renter_identity_photo' => $pathFile,
            ]);
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Penyewa berhasil diubah",
        ];
        return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
    }

    public function deleteRenter($id){
        $renter = Renter::with('rent')->find($id);
        if(is_null($renter)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewa tidak ditemukan",
            ];
            return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
        }

        if($renter->rent->where('rent_status', '!=', 'Selesai')->count() > 0){
            $sweetalert =  [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Penyewa tidak dapat dihapus karena masih memiliki draft sewa atau sewa yang aktif",
            ];
            return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
        }

        $renter->delete();

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Penyewa berhasil dihapus",
        ];
        return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
    }

    public function detailRenter($renterId){
        $renter = Renter::with('rent')->find($renterId);
        if(is_null($renter)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewa tidak ditemukan",
            ];
            return redirect('/sewa/penyewa')->with('sweetalert', $sweetalert);
        }
        $data = [
            'renter' => $renter
        ];
        return view('rental.renter-detail', $data);
    }

    public function resetStock(){
        $stock = Stock::get();
        foreach ($stock as $item) {
            $stockTotal = $item->stock_total;
            $item->stock_available = $stockTotal;
            $item->stock_decreased = 0;
            $item->stock_rented = 0;
            $item->stock_damaged = 0;
            $item->stock_lost = 0;
            $item->stock_on_repair = 0;
            
            $item->save();
        }
    }
}
